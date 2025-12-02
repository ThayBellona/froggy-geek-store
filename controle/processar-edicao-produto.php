<?php
require_once '../modelos/Produto.php';
require_once '../daos/ProdutoDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    $tamanhos = [
        'P'  => (int)$_POST['qtd_P'],
        'M'  => (int)$_POST['qtd_M'],
        'G'  => (int)$_POST['qtd_G'],
        'GG' => (int)$_POST['qtd_GG']
    ];

    $produto = new Produto();
    $produto->setId($id);
    $produto->setNome($_POST['nome']);
    $produto->setPreco($_POST['preco']);
    $produto->setCategoria($_POST['categoria']);
    
    $desconto = isset($_POST['desconto']) ? (int)$_POST['desconto'] : 0;
    $produto->setDesconto($desconto);

    if (!empty($_FILES['imagem']['name'])) {
        $diretorioFisico = "../uploads/";
        $caminhoBanco = "uploads/";

        if(!is_dir($diretorioFisico)){ mkdir($diretorioFisico, 0777, true); }

        $nomeArquivo = uniqid() . "_" . $_FILES['imagem']['name'];
        
        // Move usando ../
        move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorioFisico . $nomeArquivo);
        
        $produto->setImagem($caminhoBanco . $nomeArquivo);
    } else {
        $daoTemp = new ProdutoDAO();
        $pTemp = $daoTemp->buscarPorId($id);
        $produto->setImagem($pTemp->getImagem());
    }

    $dao = new ProdutoDAO();
    if ($dao->atualizar($produto, $tamanhos)) {
        header("Location: ../visoes/admin/form-adicionar.php");
    } else {
        echo "Erro ao atualizar.";
    }
}
?>