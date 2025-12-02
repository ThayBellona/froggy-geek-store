<?php
session_start();
require_once '../daos/AvaliacaoDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario'])) {
    $idProd = $_POST['id_produto'];
    $nota = $_POST['nota'];
    $comentario = $_POST['comentario'];
    $idUser = $_SESSION['id_usuario'];

    $dao = new AvaliacaoDAO();
    $dao->inserir($idProd, $idUser, $nota, $comentario);

    header("Location: ../visoes/produto.php?id=" . $idProd);
    exit;
} else {
    header("Location: index.php");
}
?>