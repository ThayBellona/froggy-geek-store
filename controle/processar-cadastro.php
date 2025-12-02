<?php
require_once '../daos/UsuarioDAO.php';
require_once '../modelos/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario();
    $usuario->setNome($_POST['nome']);
    $usuario->setEmail($_POST['email']);
    
    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $usuario->setSenha($senhaHash);

    $usuario->setGenero($_POST['genero']);
    $usuario->setDataNascimento($_POST['data_nascimento']);

    $dao = new UsuarioDAO();
    if ($dao->cadastrar($usuario)) {
        header("Location: ../visoes/login.php?msg=criado");
    } else {
        header("Location: ../visoes/cadastro.php?erro=email");
    }
}
?>