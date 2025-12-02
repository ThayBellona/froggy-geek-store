<?php
require_once '../daos/ProdutoDAO.php';

// Verifica se veio um ID lá no link (ex: excluir-produto.php?id=5)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $dao = new ProdutoDAO();
    $dao->excluir($id);
}

// Depois de apagar, volta para a tela de admin
header("Location: ../visoes/admin/form-adicionar.php");
exit;
?>