<?php
session_start();
require_once '../daos/SuporteDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario'])) {
    $dao = new SuporteDAO();
    
    // Captura o ID do pedido (se existir)
    $idPedido = isset($_POST['id_pedido']) ? $_POST['id_pedido'] : null;
    
    $dao->criar($_SESSION['id_usuario'], $_POST['tipo'], $_POST['mensagem'], $idPedido);
    
    header("Location: ../visoes/perfil.php?msg=sucesso");
} else {
    header("Location: index.php");
}
?>