<?php
require_once '../conexao/Conexao.php';
require_once '../modelos/Produto.php';
require_once '../daos/ProdutoDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $desconto = isset($_POST['desconto']) ? (int)$_POST['desconto'] : 0;

    // Tamanhos
    $tamanhos = [
        'P'  => (int)$_POST['qtd_P'],
        'M'  => (int)$_POST['qtd_M'],
        'G'  => (int)$_POST['qtd_G'],
        'GG' => (int)$_POST['qtd_GG']
    ];

    
    // 1. Onde salvar fisicamente (saindo da pasta controle)
    $diretorioFisico = "../uploads/"; 
    
    // 2. O que salvar no banco (caminho limpo para o site usar)
    $caminhoBanco = "uploads/";

    if(!is_dir($diretorioFisico)){ mkdir($diretorioFisico, 0777, true); }
    
    $arquivo = $_FILES['imagem'];
    $nomeArquivo = uniqid() . "_" . $arquivo['name'];
    
    $caminhoCompletoFisico = $diretorioFisico . $nomeArquivo; 
    $caminhoFinalBanco = $caminhoBanco . $nomeArquivo;       

    if(move_uploaded_file($arquivo['tmp_name'], $caminhoCompletoFisico)) {
        
        $produto = new Produto();
        $produto->setNome($nome);
        $produto->setPreco($preco);
        $produto->setCategoria($categoria);
        
        $produto->setImagem($caminhoFinalBanco); 
        
        $produto->setDesconto($desconto);

        $produtoDAO = new ProdutoDAO();
        
        if($produtoDAO->inserir($produto, $tamanhos)) {
            header("Location: ../visoes/admin/form-adicionar.php");
            exit;
        } else {
            echo "Erro ao salvar no banco.";
        }
    } else {
        echo "Erro ao mover arquivo. Verifique permissões da pasta uploads.";
    }
}
?>