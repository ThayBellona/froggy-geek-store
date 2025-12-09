<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { header("Location: ../../index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Central Admin - Froggy Geek</title>
    
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo time(); ?>">
    
    <link rel="icon" href="../../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="admin-page d-flex flex-column min-vh-100">

    <?php $caminho = '../..'; include '../../includes/menu.php'; ?>

    <div class="container d-flex flex-column justify-content-center align-items-center flex-grow-1 py-5">
        
        <div class="hub-box text-center animate-up">
            
            <div class="mb-5">
                <div class="position-relative d-inline-block mb-3">
                    <img src="../../img-modelos/logo-img/sapo-geek-icon.png" width="100" class="rounded-circle bg-light p-2 shadow-sm border">
                    <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-2 border-white rounded-circle"></span>
                </div>
                <h2 class="fw-bold text-dark m-0">Central de Comando</h2>
                <p class="text-muted opacity-75">Bem-vindo, <strong><?php echo $_SESSION['nome_usuario']; ?></strong></p>
            </div>

            <div class="row g-4 justify-content-center">
                
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="dashboard.php" class="hub-btn bg-dash" title="Ver Gráficos">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <a href="form-adicionar.php" class="hub-btn bg-prod" title="Gerenciar Estoque">
                        <i class="bi bi-box-seam"></i>
                        <span>Produtos</span>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <a href="gerenciar-vendas.php" class="hub-btn bg-sales" title="Ver Pedidos">
                        <i class="bi bi-cart-check"></i>
                        <span>Pedidos</span>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <a href="gerenciar-cupons.php" class="hub-btn bg-cupons" title="Criar Descontos">
                        <i class="bi bi-ticket-perforated"></i>
                        <span>Cupons</span>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="gerenciar-usuarios.php" class="hub-btn" style="background: linear-gradient(135deg, #607d8b 0%, #455a64 100%);" title="Gerenciar Clientes">
                        <i class="bi bi-people-fill"></i>
                        <span>Usuários</span>
                    </a>
                </div>
                
                 <div class="col-6 col-md-4 col-lg-2">
                    <a href="gerenciar-suporte.php" class="hub-btn" style="background: linear-gradient(135deg, #ff5252 0%, #d32f2f 100%);" title="Ver Chamados">
                        <i class="bi bi-headset"></i>
                        <span>Suporte</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

    <?php include '../../includes/rodape.php'; ?>

</body>
</html>