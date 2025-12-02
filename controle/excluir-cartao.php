<?php
session_start();
require_once '../daos/CartaoDAO.php';

if (isset($_GET['id']) && isset($_SESSION['id_usuario'])) {
    $dao = new CartaoDAO();
    $dao->excluir($_GET['id'], $_SESSION['id_usuario']);
}

header("Location: ../visoes/perfil.php");
exit;
?>