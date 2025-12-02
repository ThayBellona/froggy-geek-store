<?php
session_start();

// AJUSTE DE CAMINHOS (Saindo da pasta 'controle')
require_once '../conexao/Conexao.php';
require_once '../daos/ProdutoDAO.php';
require_once '../daos/CartaoDAO.php';
require_once '../daos/CupomDAO.php';

// 1. Verificações de Segurança
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['carrinho'])) {
    header("Location: ../index.php"); // Volta para a raiz
    exit;
}

$idUsuario = $_SESSION['id_usuario'];
$carrinho = $_SESSION['carrinho'];

// Dados do Formulário
$metodoPagamento = isset($_POST['pagamento']) ? $_POST['pagamento'] : 'Cartão de Crédito';
$cartaoSelecionado = isset($_POST['cartao_selecionado']) ? $_POST['cartao_selecionado'] : 'novo';

try {
    $conn = Conexao::getConexao();
    $daoProduto = new ProdutoDAO();
    $daoCartao = new CartaoDAO();
    $daoCupom = new CupomDAO();
    
    // === INÍCIO DA TRANSAÇÃO ===
    $conn->beginTransaction(); 

    // A. Calcular Total e Verificar Estoque (Tamanhos)
    $totalItens = 0;

    foreach ($carrinho as $chave => $qtdCompra) {
        // A chave é "ID_TAMANHO" (ex: 15_M)
        $partes = explode('_', $chave);
        $idProd = $partes[0];
        $tam = isset($partes[1]) ? $partes[1] : null;

        // Busca dados do produto
        $stmt = $conn->prepare("SELECT nome, preco FROM produtos WHERE id = ?");
        $stmt->execute([$idProd]);
        $dadosProd = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dadosProd) throw new Exception("Produto ID $idProd não encontrado.");

        // VERIFICAÇÃO RIGOROSA DE ESTOQUE POR TAMANHO
        if ($tam && $tam !== 'U') {
            $stmtEstoque = $conn->prepare("SELECT quantidade FROM estoque_tamanhos WHERE id_produto = ? AND tamanho = ?");
            $stmtEstoque->execute([$idProd, $tam]);
            $qtdEstoque = $stmtEstoque->fetchColumn();

            if ($qtdEstoque < $qtdCompra) {
                throw new Exception("O produto '{$dadosProd['nome']}' (Tamanho $tam) acabou ou não tem estoque suficiente.");
            }
        } else {
            // Fallback para produtos sem tamanho (se houver)
            $stmtEstoque = $conn->prepare("SELECT estoque FROM produtos WHERE id = ?");
            $stmtEstoque->execute([$idProd]);
            if ($stmtEstoque->fetchColumn() < $qtdCompra) {
                throw new Exception("O produto '{$dadosProd['nome']}' está esgotado.");
            }
        }

        $totalItens += ($dadosProd['preco'] * $qtdCompra);
    }

    // B. Aplicar Desconto do Cupom
    $desconto = 0;
    $idCupom = null;
    
    if (isset($_SESSION['cupom_ativo'])) {
        $desconto = $totalItens * ($_SESSION['cupom_ativo']['desconto'] / 100);
        
        // Busca ID do cupom para salvar no pedido
        $stmtCupom = $conn->prepare("SELECT id FROM cupons WHERE codigo = ?");
        $stmtCupom->execute([$_SESSION['cupom_ativo']['codigo']]);
        $idCupom = $stmtCupom->fetchColumn();
        
        // Marca como usado
        $daoCupom->marcarComoUsado($idUsuario, $_SESSION['cupom_ativo']['codigo']);
    }
    
    $totalFinal = $totalItens - $desconto;

    // C. Salvar Cartão (Se for Novo e Solicitado)
    $nomeCartaoGravado = $metodoPagamento; 

    if ($metodoPagamento == 'Cartão de Crédito') {
        if ($cartaoSelecionado === 'novo') {
            $numCartao = $_POST['numero'];
            
            // Salva se o checkbox estiver marcado
            if (isset($_POST['salvar_cartao'])) {
                $apelido = !empty($_POST['apelido']) ? $_POST['apelido'] : 'Meu Cartão';
                $nomeTitular = $_POST['nome_cartao'];
                $validade = $_POST['validade'];
                
                $daoCartao->salvar($idUsuario, $apelido, $numCartao, $nomeTitular, $validade);
            }
            $nomeCartaoGravado = "Cartão Final " . substr(str_replace(' ', '', $numCartao), -4);
        } else {
            // Se usou cartão salvo, podemos buscar o final dele para registrar
            $stmtC = $conn->prepare("SELECT numero_final FROM cartoes WHERE id = ?");
            $stmtC->execute([$cartaoSelecionado]);
            $finalSalvo = $stmtC->fetchColumn();
            $nomeCartaoGravado = "Cartão Salvo (Final $finalSalvo)";
        }
    }

    // D. Criar o Pedido
    $sqlPedido = "INSERT INTO pedidos (id_usuario, valor_total, metodo_pagamento, status, id_cupom) 
                  VALUES (?, ?, ?, 'Pendente', ?)";
    $stmt = $conn->prepare($sqlPedido);
    $stmt->execute([$idUsuario, $totalFinal, $nomeCartaoGravado, $idCupom]);
    
    $idPedido = $conn->lastInsertId();

    // E. Inserir Itens e Baixar Estoque
    foreach ($carrinho as $chave => $qtdCompra) {
        $partes = explode('_', $chave);
        $idProd = $partes[0];
        $tam = isset($partes[1]) ? $partes[1] : 'U';

        $prod = $daoProduto->buscarPorId($idProd);

        // 1. Salva Item
        $sqlItem = "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
        $stmtItem = $conn->prepare($sqlItem);
        $stmtItem->execute([$idPedido, $idProd, $qtdCompra, $prod->getPreco()]);

        // 2. Baixa Estoque Específico
        if ($tam !== 'U') {
            $sqlUpdate = "UPDATE estoque_tamanhos SET quantidade = quantidade - ? WHERE id_produto = ? AND tamanho = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute([$qtdCompra, $idProd, $tam]);
        }
        
        // 3. Baixa Estoque Geral (Total)
        $sqlUpdateGeral = "UPDATE produtos SET estoque = estoque - ? WHERE id = ?";
        $conn->prepare($sqlUpdateGeral)->execute([$qtdCompra, $idProd]);
    }

    // === FIM DA TRANSAÇÃO ===
    $conn->commit();

    // F. Gamificação (Sorteio de Cupom)
    $cupomGanho = $daoCupom->sortearCupomParaUsuario($idUsuario);
    
    $linkDestino = "../visoes/sucesso.php?pedido=$idPedido"; 
    if ($cupomGanho) {
        $linkDestino .= "&cupom_ganho=$cupomGanho";
    }

    // Limpeza
    unset($_SESSION['carrinho']);
    unset($_SESSION['cupom_ativo']);
    
    header("Location: " . $linkDestino);
    exit;

} catch (Exception $e) {
    if (isset($conn)) $conn->rollBack();
    
    // Página de erro simples
    echo "<div style='padding:50px; text-align:center; font-family:sans-serif;'>
            <h2 style='color:red'>Ops! Algo deu errado na compra.</h2>
            <p>" . $e->getMessage() . "</p>
            <a href='../visoes/carrinho.php'>Voltar ao Carrinho</a>
         </div>";
}
?>