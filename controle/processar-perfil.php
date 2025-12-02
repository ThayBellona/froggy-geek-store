<?php
session_start();
require_once '../daos/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario'])) {
    $id = $_SESSION['id_usuario'];
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $dataNascimento = $_POST['data_nascimento'];
    
    // Lógica da Senha com Hash
    $senha = null;
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    }

    $dao = new UsuarioDAO();
    
    $usuarioAtual = $dao->buscarPorId($id);
    $caminhoFoto = $usuarioAtual->getFotoPerfil();

    if (!empty($_FILES['foto']['name'])) {
        $dir = "../uploads/perfis/";
        if(!is_dir($dir)){ mkdir($dir, 0777, true); }
        $nomeArq = uniqid() . "_" . $_FILES['foto']['name'];
        if(move_uploaded_file($_FILES['foto']['tmp_name'], $dir . $nomeArq)) {
            $caminhoFoto = "uploads/perfis/" . $nomeArq; 
        }
    }

    if ($dao->atualizar($id, $nome, $genero, $dataNascimento, $caminhoFoto, $senha)) {
        $_SESSION['nome_usuario'] = $nome;
        header("Location: ../visoes/perfil.php?msg=sucesso");
    } else {
        header("Location: ../visoes/perfil.php?msg=erro");
    }
}
?>