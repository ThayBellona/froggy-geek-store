<?php
session_start();
require_once '../daos/UsuarioDAO.php';

// Segurança: Apenas Admin pode fazer isso
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { 
    header("Location: ../index.php"); exit; 
}

$dao = new UsuarioDAO();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

// Proteção: Não pode alterar o próprio usuário logado
if ($id == $_SESSION['id_usuario']) {
    header("Location: ../visoes/admin/gerenciar-usuarios.php?msg=erro_proprio");
    exit;
}

if ($id && $acao) {
    
    // AÇÃO 1: RESETAR SENHA
    if ($acao == 'resetar') {
        $senhaPadrao = "1234"; // Defina a senha padrão aqui
        if ($dao->resetarSenha($id, $senhaPadrao)) {
            header("Location: ../visoes/admin/gerenciar-usuarios.php?msg=senha_resetada");
        }
    }
    
    // AÇÃO 2: VIRAR ADMIN
    if ($acao == 'promover') {
        if ($dao->alterarNivel($id, 1)) {
            header("Location: ../visoes/admin/gerenciar-usuarios.php?msg=promovido");
        }
    }

    // AÇÃO 3: VIRAR CLIENTE (REBAIXAR)
    if ($acao == 'rebaixar') {
        if ($dao->alterarNivel($id, 0)) {
            header("Location: ../visoes/admin/gerenciar-usuarios.php?msg=rebaixado");
        }
    }

    // AÇÃO 4: EXCLUIR (Movido para cá para organizar)
    if ($acao == 'excluir') {
        $dao->excluir($id);
        header("Location: ../visoes/admin/gerenciar-usuarios.php?msg=excluido");
    }

} else {
    header("Location: ../visoes/admin/gerenciar-usuarios.php");
}
?>