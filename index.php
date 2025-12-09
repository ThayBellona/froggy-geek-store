<?php
session_start();
require_once 'daos/ProdutoDAO.php';

$produtoDAO = new ProdutoDAO();
$listaProdutos = $produtoDAO->buscarTodos(); 
?>
<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Froggy Geek - Home</title>

    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/navbar.css"> 
    <link rel="icon" href="img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .produto-card .card-body {
            display: flex;
            flex-direction: column;
        }

        .price-box {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .price-old {
            text-decoration: line-through;
            color: #888;
            font-size: 0.85rem;
            margin-bottom: -2px;
        }

        .price-new {
            color: #dc3545;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .price-area {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Badge de desconto */
        .badge-discount {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #dc3545;
            color: white;
            font-weight: 800;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
            z-index: 10;
            transform: rotate(5deg);
        }
    </style>
</head>

<body>

<?php $caminho = '.'; include 'includes/menu.php'; ?>

<header class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                <span class="badge bg-light text-success mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
                    🐸 NOVA COLEÇÃO
                </span>

                <h1 class="hero-title">
                    Moda Geek com <br><span style="color: var(--frog-accent);">Estilo e Conforto</span>
                </h1>

                <p class="lead mb-4 opacity-90">Vista suas paixões. As melhores estampas de Animes, Games e Cultura Pop você encontra aqui.</p>

                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    <a href="visoes/categoria.php?tipo=Todas" class="btn btn-light text-success fw-bold btn-lg shadow px-4 rounded-pill">
                        Ver Loja
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-img-container">
                    <div class="hero-circle-bg"></div>
                    <img src="uploads/camiseta-anime-naruto-minato-nam.png" class="hero-img img-fluid" alt="Destaque">
                </div>
            </div>

        </div>
    </div>
</header>

<section class="container mb-5">
    <div class="row g-4 text-center py-4 bg-white rounded-4 shadow-sm border">
        <div class="col-md-4 border-end">
            <i class="bi bi-truck fs-2 text-success"></i>
            <h6 class="fw-bold mt-2">Retirada</h6>
            <small class="text-muted">Na nossa loja</small>
        </div>

        <div class="col-md-4 border-end">
            <i class="bi bi-credit-card fs-2 text-success"></i>
            <h6 class="fw-bold mt-2">Aceitamos Cartão</h6>
            <small class="text-muted">Salve seu cartão e facilite sua compra!</small>
        </div>

        <div class="col-md-4">
            <i class="bi bi-arrow-repeat fs-2 text-success"></i>
            <h6 class="fw-bold mt-2">Troca Fácil</h6>
            <small class="text-muted">Garantia de satisfação</small>
        </div>
    </div>
</section>

<section class="container mb-5">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark">Explore os <span style="color: var(--frog-main);">Universos</span></h3>
    </div>
    
    <div class="cat-grid">
        <a href="visoes/categoria.php?tipo=Anime" class="cat-item">
            <div class="cat-circle"><img src="./img-modelos/logo-img/dandandan-02.png"></div>
            <h5>Anime</h5>
        </a>

        <a href="visoes/categoria.php?tipo=90s Geek" class="cat-item">
            <div class="cat-circle"><img src="./img-modelos/logo-img/blusa-cartoon-black-02.png"></div>
            <h5>90's Geek</h5>
        </a>

        <a href="visoes/categoria.php?tipo=Games" class="cat-item">
            <div class="cat-circle"><img src="./img-modelos/logo-img/doom-02.png"></div>
            <h5>Games</h5>
        </a>

        <a href="visoes/categoria.php?tipo=Fary Core" class="cat-item">
            <div class="cat-circle"><img src="./img-modelos/logo-img/sapo-cogumelo-02.png"></div>
            <h5>Fary Core</h5>
        </a>
    </div>
</section>

<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
        <h2 class="fw-bold m-0 text-dark">Mais Vendidos</h2>
        <a href="visoes/categoria.php?tipo=Todas" class="text-decoration-none fw-bold text-success">Ver tudo <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-4">

        <?php 
        $destaques = array_slice($listaProdutos, 0, 8); 
        
        if(count($destaques) > 0):
            foreach($destaques as $produto): 
        ?>

        <div class="col-6 col-md-3">
            <div class="produto-card h-100">

                <a href="visoes/produto.php?id=<?php echo $produto->getId(); ?>" 
                   class="text-decoration-none text-dark d-flex flex-column h-100">

                    <div class="card-img-wrapper position-relative">

                        <?php if($produto->getDesconto() > 0): ?>
                            <div class="badge-discount">
                                -<?php echo $produto->getDesconto(); ?>%
                            </div>
                        <?php endif; ?>

                        <img src="<?php echo $produto->getImagem(); ?>" 
                             class="card-img-top" 
                             alt="<?php echo $produto->getNome(); ?>">
                    </div>

                    <div class="card-body">
                        
                        <div class="card-cat-tag"><?php echo $produto->getCategoria(); ?></div>

                        <h5 class="card-title"><?php echo $produto->getNome(); ?></h5>

                        <div class="price-area mt-auto">

                            <?php 
                            $preco = $produto->getPreco();
                            $desconto = $produto->getDesconto();
                            ?>

                            <?php if($desconto > 0): ?>
                                <?php $precoDesconto = $preco - ($preco * ($desconto / 100)); ?>

                                <div class="price-box">
                                    <span class="price-old">
                                        R$ <?php echo number_format($preco, 2, ',', '.'); ?>
                                    </span>
                                    <span class="price-new">
                                        R$ <?php echo number_format($precoDesconto, 2, ',', '.'); ?>
                                    </span>
                                </div>

                            <?php else: ?>
                                <span class="card-price">
                                    R$ <?php echo number_format($preco, 2, ',', '.'); ?>
                                </span>
                            <?php endif; ?>

                            <div class="btn-card-action">
                                <i class="bi bi-arrow-right"></i>
                            </div>

                        </div>
                    </div>

                </a>
            </div>
        </div>

        <?php 
            endforeach; 
        else:
        ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Nenhum produto em destaque.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/rodape.php'; ?>

</body>
</html>
