<?php
session_start();
require_once '../daos/CupomDAO.php';

$origem = isset($_POST['origem']) ? $_POST['origem'] : 'checkout'; // Padrão checkout

if (isset($_POST['codigo_cupom'])) {
    $codigo = $_POST['codigo_cupom'];
    $dao = new CupomDAO();
    $cupom = $dao->buscarPorCodigo($codigo);

    if ($cupom) {
        $_SESSION['cupom_ativo'] = [
            'codigo' => $cupom['codigo'],
            'desconto' => $cupom['desconto_percentual']
        ];
        $msg = "cupom_ok";
    } else {
        unset($_SESSION['cupom_ativo']);
        $msg = "cupom_erro";
    }
} else {
    // Remover cupom
    if(isset($_GET['remover'])) unset($_SESSION['cupom_ativo']);
    $origem = isset($_GET['origem']) ? $_GET['origem'] : 'checkout';
    $msg = "removido";
}

// Redireciona para a página certa
if ($origem == 'carrinho') {
    header("Location: ../visoes/carrinho.php?msg=$msg");
} else {
    header("Location: ../visoes/checkout.php?msg=$msg");
}
?>