<?php
session_start();
require_once '../daos/ProdutoDAO.php';

$termo = isset($_GET['q']) ? $_GET['q'] : '';
$dao = new ProdutoDAO();
$lista = $dao->buscarPorNome($termo);

// Contagem carrinho para o menu
$qtdItensCarrinho = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Busca: <?php echo $termo; ?> - Froggy Geek</title>
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="../css/navbar.css"> 
    <link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <?php $caminho = '..'; include '../includes/menu.php'; ?>

    <div class="container mt-5 mb-5">
        <h3 class="mb-4">Resultados para: <span class="text-success fw-bold">"<?php echo $termo; ?>"</span></h3>
        
        <?php if(count($lista) > 0): ?>
            <div class="row g-4">
                <?php foreach($lista as $produto): ?>
                <div class="col-6 col-md-3">
                    <div class="produto-card h-100">
                        <a href="produto.php?id=<?php echo $produto->getId(); ?>" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <div class="card-img-wrapper">
                                <img src="../<?php echo $produto->getImagem(); ?>" class="card-img-top" alt="<?php echo $produto->getNome(); ?>">
                            </div>
                            <div class="card-body">
                                <div class="card-cat-tag"><?php echo $produto->getCategoria(); ?></div>
                                <h5 class="card-title"><?php echo $produto->getNome(); ?></h5>
                                <div class="card-footer-custom">
                                    <span class="card-price">R$ <?php echo number_format($produto->getPreco(), 2, ',', '.'); ?></span>
                                    <div class="btn-card-action"><i class="bi bi-arrow-right"></i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center p-5 rounded-4 shadow-sm">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                <h4>Nenhum produto encontrado.</h4>
                <p>Tente buscar por outro termo ou navegue pelas categorias.</p>
                <a href="categoria.php?tipo=Todas" class="btn btn-success mt-2">Ver Todos os Produtos</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../includes/rodape.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>