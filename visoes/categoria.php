<?php
session_start();
require_once '../daos/ProdutoDAO.php';

$cat = isset($_GET['tipo']) ? $_GET['tipo'] : '90s Geek';
$dao = new ProdutoDAO();

if ($cat == 'Todas') {
    $lista = $dao->buscarTodos();
    $tituloPagina = "Todos os Produtos";
} else {
    $lista = $dao->buscarPorCategoria($cat);
    $tituloPagina = $cat;
}
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

  <title><?php echo $tituloPagina; ?> - Froggy Geek</title>
  
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/navbar.css">
<link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
        /* Correções visuais dos preços */
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
<body class="bg-light d-flex flex-column min-vh-100">

  <?php $caminho = '..'; include '../includes/menu.php'; ?>

  <div class="container mt-4 mb-5 flex-grow-1">
      <div class="row">
          
          <div class="col-md-3 mb-4">
              <div class="sidebar-categoria sticky-top" style="top: 100px; z-index: 1;">
                  <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Categorias</h5>
                  <div class="d-flex flex-column gap-1">
                      <a href="categoria.php?tipo=Todas" class="<?php echo ($cat=='Todas')?'ativo':''; ?>">Ver Tudo</a>
                      <a href="categoria.php?tipo=Anime" class="<?php echo ($cat=='Anime')?'ativo':''; ?>">Anime</a>
                      <a href="categoria.php?tipo=90s Geek" class="<?php echo ($cat=='90s Geek')?'ativo':''; ?>">90's GEEK</a>
                      <a href="categoria.php?tipo=Games" class="<?php echo ($cat=='Games')?'ativo':''; ?>">Video Games</a>
                      <a href="categoria.php?tipo=Fary Core" class="<?php echo ($cat=='Fary Core')?'ativo':''; ?>">Fary Core</a>
                  </div>
              </div>
          </div>

          <div class="col-md-9">
              <div class="d-flex justify-content-between align-items-center mb-4">
                  <h2 class="fw-bold m-0"><?php echo $tituloPagina; ?></h2>
                  <span class="text-muted"><?php echo count($lista); ?> produtos encontrados</span>
              </div>

              <div class="row g-4">
                <?php if(count($lista) > 0): ?>
                    <?php foreach($lista as $produto): ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="produto-card h-100">
                          <a href="produto.php?id=<?php echo $produto->getId(); ?>" class="text-decoration-none text-dark d-flex flex-column h-100">
                            
                            <div class="card-img-wrapper position-relative">
                                <?php if($produto->getDesconto() > 0): ?>
                                    <div class="badge-discount">-<?php echo $produto->getDesconto(); ?>%</div>
                                <?php endif; ?>
                                <img src="../<?php echo $produto->getImagem(); ?>" class="card-img-top" alt="<?php echo $produto->getNome(); ?>">
                            </div>
                            
                            <div class="card-body">
                                <div class="card-cat-tag"><?php echo $produto->getCategoria(); ?></div>
                                <h5 class="card-title" title="<?php echo $produto->getNome(); ?>">
                                    <?php echo $produto->getNome(); ?>
                                </h5>
                                
                                <div class="card-footer-custom">
                                    <div class="d-flex flex-column align-items-start justify-content-center" style="height: 45px;">
                                        <?php if($produto->getDesconto() > 0): 
                                            $precoNovo = $produto->getPreco() - ($produto->getPreco() * ($produto->getDesconto() / 100));
                                        ?>
                                            <span class="price-old">R$ <?php echo number_format($produto->getPreco(), 2, ',', '.'); ?></span>
                                            <span class="price-new">R$ <?php echo number_format($precoNovo, 2, ',', '.'); ?></span>
                                        <?php else: ?>
                                            <span class="card-price">R$ <?php echo number_format($produto->getPreco(), 2, ',', '.'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-card-action"><i class="bi bi-arrow-right"></i></div>
                                </div>
                            </div>

                          </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center p-5">
                            <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
                            <h3>Ops!</h3>
                            <p>Ainda não temos produtos cadastrados nesta categoria.</p>
                            <a href="categoria.php?tipo=Todas" class="btn btn-success mt-2">Ver todos os produtos</a>
                        </div>
                    </div>
                <?php endif; ?>
              </div>
          </div>

      </div>
  </div>

  <?php include '../includes/rodape.php'; ?>
</body>
</html>