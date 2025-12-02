<?php
require_once '../daos/PedidoDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_pedido'];
    $status = $_POST['novo_status'];

    $dao = new PedidoDAO();
    $dao->atualizarStatus($id, $status);

    // Volta para o painel admin
    header("Location: visoes/admin/painel.php");
    exit;
}
?>