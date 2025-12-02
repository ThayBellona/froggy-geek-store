<?php
session_start();

if (isset($_GET['id'])) {
    // Agora o ID é uma string composta (ex: "5_M" ou "12_GG")
    $chave = $_GET['id'];
    
    // Verifica se existe no array da sessão e remove
    if (isset($_SESSION['carrinho'][$chave])) {
        unset($_SESSION['carrinho'][$chave]);
    }
}

// Redireciona de volta para a visualização do carrinho
header("Location: ../visoes/carrinho.php");
exit;
?>