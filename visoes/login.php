<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <title>Login - Froggy Geek</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = '..'; include '../includes/menu.php'; ?>
    <br>
    <div class="container d-flex align-items-center justify-content-center flex-grow-1">
        <div class="card border-0 shadow rounded-4 p-4 p-md-5" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <img src="../img-modelos/logo-img/sapo-geek-icon.png" width="60" class="mb-2">
                <h3 class="fw-bold text-success">Bem-vindo!</h3>
                <p class="text-muted small">Faça login para continuar</p>
            </div>
            
            <?php if(isset($_GET['erro'])): ?>
                <div class="alert alert-danger py-2 text-center">Email ou senha incorretos!</div>
            <?php endif; ?>

            <form action="../controle/processar-login.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control rounded-3" id="email" placeholder="name@example.com" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" name="senha" class="form-control rounded-3" id="senha" placeholder="Password" required>
                    <label for="senha">Senha</label>
                </div>
                <button type="submit" class="btn btn-frog w-100 py-2 fw-bold shadow-sm bg-success text-light">ENTRAR</button>
            </form>
            
            <div class="text-center mt-4">
                <span class="text-muted">Não tem conta?</span>
                <a href="cadastro.php" class="fw-bold text-success text-decoration-none">Cadastre-se</a>
            </div>
        </div>
    </div>
    <br>

    <?php include '../includes/rodape.php'; ?>
</body>
</html>