<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <title>Cadastro - Froggy Geek</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
    .btn-frog {
    background-color: #2e6417; 
    color: #ffffff;            
    border: 2px solid #2e6417;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 25px;       
    transition: all 0.3s ease; 
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-block;
    text-align: center;
    cursor: pointer;
}

/* Efeito ao passar o mouse */
.btn-frog:hover {
    background-color: #058a10ff;
    color: #13ff91ff;           
    border-color: #64cc37ff;
    transform: translateY(-3px); 
    box-shadow: 0 5px 15px rgba(46, 100, 23, 0.4); 
}

.btn-frog:active {
    transform: translateY(1px);
    box-shadow: none;
}
</style>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = '..'; include '../includes/menu.php'; ?>

    <div class="container d-flex align-items-center justify-content-center flex-grow-1 my-5">
        <div class="card border-0 shadow rounded-4 p-4 p-md-5" style="max-width: 500px; width: 100%;">
            <div class="text-center mb-4">
                <img src="../img-modelos/logo-img/sapo-geek-icon.png" width="60" class="mb-3">
                <h3 class="fw-bold text-success">Junte-se ao Clube</h3>
                <p class="text-muted small">Crie sua conta para comprar e avaliar</p>
            </div>
            
            <form action="../controle/processar-cadastro.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nome Completo</label>
                    <input type="text" name="nome" class="form-control bg-light border-0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Email</label>
                    <input type="email" name="email" class="form-control bg-light border-0" required>
                </div>
                
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold small text-muted">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control bg-light border-0" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold small text-muted">Gênero</label>
                        <select name="genero" class="form-select bg-light border-0" required>
                            <option value="" selected disabled>Selecione...</option>
                            <option value="Feminino">Feminino</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Não-binárie">Não-binárie</option>
                            <option value="Outro">Outro</option>
                            <option value="Prefiro não dizer">Prefiro não dizer</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Senha</label>
                    <input type="password" name="senha" class="form-control bg-light border-0" required>
                </div>
                
                <button type="submit" class="btn btn-frog w-100 py-2 fw-bold shadow-sm">CRIAR CONTA</button>
            </form>
            
            <div class="text-center mt-4">
                <span class="text-muted">Já tem conta?</span>
                <a href="login.php" class="fw-bold text-success text-decoration-none">Fazer Login</a>
            </div>
        </div>
    </div>

    <?php include '../includes/rodape.php'; ?>
</body>
</html>