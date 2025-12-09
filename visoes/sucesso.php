<?php 
session_start(); 
$cupomGanho = isset($_GET['cupom_ganho']) ? $_GET['cupom_ganho'] : null;
$idPedido = isset($_GET['pedido']) ? $_GET['pedido'] : '000';
?>
<!DOCTYPE html>
<html lang="pt-br"> 
    <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Pedido Confirmado!</title>
    
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
    .btn-frog {
    background-color: #2e6417; /* Verde Escuro (Cor da Marca) */
    color: #ffffff;            /* Texto Branco */
    border: 2px solid #2e6417;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 25px;       /* Bordas bem redondas */
    transition: all 0.3s ease; /* Animação suave */
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-block;
    text-align: center;
    cursor: pointer;
}

/* Efeito ao passar o mouse */
.btn-frog:hover {
    background-color: #058a10ff; /* Vira Verde Neon */
    color: #13ff91ff;            /* Texto vira escuro */
    border-color: #64cc37ff;
    transform: translateY(-3px); /* Sobe um pouquinho */
    box-shadow: 0 5px 15px rgba(46, 100, 23, 0.4); /* Sombra verde */
}

/* Efeito ao clicar */
.btn-frog:active {
    transform: translateY(1px);
    box-shadow: none;
}
</style>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = '..'; include '../includes/menu.php'; ?>

    <div class="container d-flex flex-column align-items-center justify-content-center flex-grow-1 text-center">
         <br> 
        <div class="mb-4 text-success" style="font-size: 5rem;">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <h1 class="fw-bold text-dark">Pagamento Aprovado!</h1>
        <p class="text-muted fs-5">Seu pedido <strong>#<?php echo $idPedido; ?></strong> foi confirmado.</p>
  
        <div class="mt-4 d-flex gap-3">
            <a href="perfil.php" class="btn btn-outline-dark rounded-pill px-4">Ver Meus Pedidos</a>
            <a href="../index.php" class="btn btn-frog px-4 shadow-sm">Continuar Comprando</a>
        </div>

    </div>
      <br>
    <?php if($cupomGanho): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="cupomToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
            <div class="d-flex">
                <div class="toast-body fs-6">
                    <i class="bi bi-gift-fill me-2"></i> Parabéns! Você ganhou <strong>10% OFF</strong><br>
                    Use o cupom: <span class="badge bg-white text-success fs-6 mt-1"><?php echo $cupomGanho; ?></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    
    <script>
        // Ativa a notificação automaticamente ao carregar
        window.onload = function() {
            const toast = document.getElementById('cupomToast');
            const toastBootstrap = new bootstrap.Toast(toast);
            toastBootstrap.show();
        };
    </script>
    <?php endif; ?>

    <?php include '../includes/rodape.php'; ?>

</body>
</html>