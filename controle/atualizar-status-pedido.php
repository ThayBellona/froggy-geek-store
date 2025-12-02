<?php
session_start();
require_once '../daos/PedidoDAO.php';
require_once '../daos/ProdutoDAO.php';
require_once '../conexao/Conexao.php'; 

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPedido = $_POST['id_pedido'];
    $novoStatus = $_POST['novo_status'];

    $pedidoDAO = new PedidoDAO();
    $produtoDAO = new ProdutoDAO();

    // 1. Busca o status ATUAL antes de mudar
    // (Poderíamos fazer uma busca simples aqui, mas vamos assumir a lógica direta)
    
    // LÓGICA DE ESTOQUE:
    // Se o novo status for CANCELADO, devolvemos os itens ao estoque.
    if ($novoStatus == 'Cancelado') {
        
        // Busca os itens desse pedido
        $itens = $pedidoDAO->buscarItensDoPedido($idPedido);
        
        foreach ($itens as $item) {
            // $item['id_produto'] e $item['quantidade']
            // Devolve ao estoque: Estoque Atual + Quantidade do Pedido
            
            $prodAtual = $produtoDAO->buscarPorId($item['id_produto']);
            if ($prodAtual) {
                $novoEstoque = $prodAtual->getEstoque() + $item['quantidade'];
                $produtoDAO->atualizarEstoque($item['id_produto'], $novoEstoque);
            }
        }
    }

    // 2. Atualiza o status no banco
    $pedidoDAO->atualizarStatus($idPedido, $novoStatus);

    header("Location: ../visoes/admin/gerenciar-vendas.php");
    exit;
}
?>