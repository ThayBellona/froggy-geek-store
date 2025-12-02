<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
    
    $idProduto = $_POST['id_produto'];
    $tamanho = isset($_POST['tamanho']) ? $_POST['tamanho'] : 'U'; // U = Único se não vier nada

    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = array();
    }

    $chaveCarrinho = $idProduto . '_' . $tamanho;

    if (isset($_SESSION['carrinho'][$chaveCarrinho])) {
        $_SESSION['carrinho'][$chaveCarrinho]++;
    } else {
        $_SESSION['carrinho'][$chaveCarrinho] = 1;
    }
}

header("Location:../visoes/carrinho.php");
exit;
?>