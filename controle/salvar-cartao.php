<?php
session_start();
require_once '../daos/CartaoDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario'])) {
    $dao = new CartaoDAO();
    
    $apelido = $_POST['apelido'];
    $numero = $_POST['numero'];
    $nome = $_POST['nome_cartao'];
    $validade = $_POST['validade'];
    
    if ($dao->salvar($_SESSION['id_usuario'], $apelido, $numero, $nome, $validade)) {
        header("Location: ../visoes/perfil.php?msg=cartao_ok");
    } else {
        header("Location: ../visoes/perfil.php?msg=erro");
    }
} else {
    header("Location: ../visoes/perfil.php");
}
?>