<?php
session_start();

// Caminhos corrigidos para a pasta 'controle'
require_once '../daos/UsuarioDAO.php';
require_once '../modelos/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $usuarioDAO = new UsuarioDAO();
    
    // A função login agora usa password_verify internamente
    $usuarioLogado = $usuarioDAO->login($email, $senha);

    if ($usuarioLogado) {
        // Login Sucesso
        $_SESSION['id_usuario'] = $usuarioLogado->getId();
        $_SESSION['nome_usuario'] = $usuarioLogado->getNome();
        $_SESSION['is_admin'] = $usuarioLogado->getIsAdmin();
        
        // Redirecionamento inteligente
        if ($usuarioLogado->getIsAdmin() == 1) {
            header("Location: ../visoes/admin/painel.php");
        } else {
            header("Location: ../visoes/perfil.php");
        }
        exit;
    } else {
        // Login Falhou
        header("Location: ../visoes/login.php?erro=1");
        exit;
    }
} else {
    header("Location: ../visoes/login.php");
    exit;
}
?>