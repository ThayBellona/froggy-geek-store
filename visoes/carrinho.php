<?php
session_start();
require_once '../daos/ProdutoDAO.php';

$produtoDAO = new ProdutoDAO();
$totalCarrinho = 0;
if (!isset($_SESSION['carrinho'])) { $_SESSION['carrinho'] = []; }

// Lógica preliminar do total
foreach ($_SESSION['carrinho'] as $chave => $qtd) {
    $partes = explode('_', $chave);
    $prod = $produtoDAO->buscarPorId($partes[0]);
    if($prod) $totalCarrinho += $prod->getPreco() * $qtd;
}

// Cupom
$desconto = 0;
$cupomAtivo = null;
if (isset($_SESSION['cupom_ativo'])) {
    $cupomAtivo = $_SESSION['cupom_ativo'];
    $desconto = $totalCarrinho * ($cupomAtivo['desconto'] / 100);
}
$totalFinal = $totalCarrinho - $desconto;
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Carrinho - Froggy Geek</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = '..'; include '../includes/menu.php'; ?>

    <div class="container mt-5 mb-5 flex-grow-1">
        <h2 class="fw-bold mb-4 text-dark"><i class="bi bi-cart3 text-success me-2"></i>Seu Carrinho</h2>

        <?php if (count($_SESSION['carrinho']) == 0): ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm border-0">
                <div class="mb-3"><i class="bi bi-basket2 display-1 text-muted opacity-25"></i></div>
                <h4 class="text-muted">Seu carrinho está vazio</h4>
                <a href="../index.php" class="btn btn-frog mt-3 px-4">Ver Produtos</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle m-0">
                                <thead class="table-light small text-uppercase text-muted">
                                    <tr>
                                        <th class="ps-4">Produto</th>
                                        <th>Preço</th>
                                        <th class="text-center">Qtd</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['carrinho'] as $chave => $qtd):
                                        $partes = explode('_', $chave);
                                        $prod = $produtoDAO->buscarPorId($partes[0]);
                                        $tam = isset($partes[1]) ? $partes[1] : 'U';
                                        if(!$prod) continue;
                                        $sub = $prod->getPreco() * $qtd;
                                    ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="../<?php echo $prod->getImagem(); ?>" width="60" height="60" class="rounded border object-fit-contain">
                                                <div>
                                                    <h6 class="m-0 fw-bold text-dark"><?php echo $prod->getNome(); ?></h6>
                                                    <span class="badge bg-light text-dark border">Tam: <?php echo $tam; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>R$ <?php echo number_format($prod->getPreco(), 2, ',', '.'); ?></td>
                                        <td class="text-center"><span class="fw-bold text-dark bg-light px-3 py-1 rounded border"><?php echo $qtd; ?></span></td>
                                        <td class="fw-bold text-success">R$ <?php echo number_format($sub, 2, ',', '.'); ?></td>
                                        <td class="pe-4 text-end">
                                            <a href="../controle/remover-carrinho.php?id=<?php echo $chave; ?>" class="btn btn-sm text-danger hover-scale" title="Remover">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="../index.php" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                            <i class="bi bi-arrow-left me-2"></i> Continuar Comprando
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
                        <label class="fw-bold mb-2 small text-muted">CUPOM DE DESCONTO</label>
                        <?php if($cupomAtivo): ?>
                            <div class="alert alert-success d-flex justify-content-between align-items-center m-0 p-2 small">
                                <span>Cupom <strong><?php echo $cupomAtivo['codigo']; ?></strong> aplicado!</span>
                                <a href="../controle/aplicar-cupom.php?remover=1&origem=carrinho" class="text-success"><i class="bi bi-x-circle-fill"></i></a>
                            </div>
                        <?php else: ?>
                            <form action="../controle/aplicar-cupom.php" method="POST" class="input-group">
                                <input type="hidden" name="origem" value="carrinho">
                                <input type="text" name="codigo_cupom" class="form-control bg-light border-0" placeholder="Digite o código">
                                <button class="btn btn-outline-success fw-bold">Aplicar</button>
                            </form>
                            <?php if(isset($_GET['msg']) && $_GET['msg']=='cupom_erro'): ?>
                                <small class="text-danger mt-1 d-block">Cupom inválido.</small>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-4">Resumo do Pedido</h5>
                        
                        <div class="d-flex justify-content-between mb-2 text-muted">
                            <span>Subtotal</span>
                            <span>R$ <?php echo number_format($totalCarrinho, 2, ',', '.'); ?></span>
                        </div>
                        
                        <?php if($desconto > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span>Desconto</span>
                            <span>- R$ <?php echo number_format($desconto, 2, ',', '.'); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between mb-3 text-success">
                            <span>Frete</span>
                            <span class="fw-bold">Grátis</span>
                        </div>
                        
                        <hr class="border-dashed">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fs-5 fw-bold">Total</span>
                            <span class="fs-4 fw-bold text-dark">R$ <?php echo number_format($totalFinal, 2, ',', '.'); ?></span>
                        </div>
                        
                        <a href="./checkout.php" class="btn btn-outline-success  w-100 py-3 shadow-sm fs-6">
                            FECHAR PEDIDO <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>

    <?php include '../includes/rodape.php'; ?>
</body>
</html>