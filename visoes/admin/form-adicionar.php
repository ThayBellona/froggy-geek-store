<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { header("Location: ../../index.php"); exit; }
require_once '../../daos/ProdutoDAO.php';
$produtoDAO = new ProdutoDAO();
$listaProdutos = $produtoDAO->buscarTodos();
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Produtos - Admin</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="admin-page d-flex flex-column min-vh-100">

    <?php $caminho = '../..'; include '../../includes/menu.php'; ?>

    <div class="container mt-4 mb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="painel.php" class="btn btn-outline-dark rounded-pill px-3 mb-2 fw-bold bg-white shadow-sm"><i class="bi bi-arrow-left"></i> Central</a>
                <h2 class="fw-bold text-dark">Produtos & Estoque</h2>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card-admin h-100">
                    <div class="card-header header-gradient-green py-3">
                        <h5 class="m-1 ms-3 text-white fw-bold"><i class="bi bi-plus-lg me-2"></i>Cadastrar</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="../../controle/processar-produto.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Nome</label>
                                <input type="text" name="nome" class="form-control bg-light border-0" required>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Preço (R$)</label>
                                    <input type="number" name="preco" step="0.01" class="form-control bg-light border-0" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold small text-danger">Desconto (%)</label>
                                    <input type="number" name="desconto" class="form-control bg-light border-danger text-danger fw-bold" value="0">
                                </div>
                            </div>

                            <div class="mb-3 p-3 border rounded bg-light">
                                <label class="form-label fw-bold small text-success mb-2">Estoque por Tamanho</label>
                                <div class="row g-1">
                                    <div class="col-3"><input type="number" name="qtd_P" class="form-control form-control-sm text-center" placeholder="P"></div>
                                    <div class="col-3"><input type="number" name="qtd_M" class="form-control form-control-sm text-center" placeholder="M"></div>
                                    <div class="col-3"><input type="number" name="qtd_G" class="form-control form-control-sm text-center" placeholder="G"></div>
                                    <div class="col-3"><input type="number" name="qtd_GG" class="form-control form-control-sm text-center" placeholder="GG"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Categoria</label>
                                <select name="categoria" class="form-select bg-light border-0">
                                    <option value="Anime">Anime</option>
                                    <option value="90s Geek">90s Geek</option>
                                    <option value="Games">Games</option>
                                    <option value="Fary Core">Fary Core</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Foto</label>
                                <input type="file" name="imagem" class="form-control bg-light border-0" required>
                            </div>
                            <button class="btn btn-success w-100 py-2 fw-bold shadow">Salvar Produto</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-admin h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="m-1 ms-3 fw-bold text-secondary">Catálogo Atual</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-colored align-middle m-0">
                                <thead><tr><th class="ps-4">Produto</th><th>Preço</th><th>Desc.</th><th>Total</th><th class="text-end pe-4">Ações</th></tr></thead>
                                <tbody>
                                    <?php foreach($listaProdutos as $p): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="../../<?php echo $p->getImagem(); ?>" width="40" height="40" class="rounded border me-3 object-fit-contain bg-white">
                                                <span class="fw-bold small text-dark"><?php echo $p->getNome(); ?></span>
                                            </div>
                                        </td>
                                        <td class="text-success fw-bold">R$ <?php echo number_format($p->getPreco(), 2, ',', '.'); ?></td>
                                        <td>
                                            <?php if($p->getDesconto()>0): ?><span class="badge bg-danger bg-opacity-10 text-danger border border-danger">-<?php echo $p->getDesconto(); ?>%</span><?php else: ?>-<?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-primary rounded-pill"><?php echo $p->getEstoque(); ?> un.</span></td>
                                        <td class="text-end pe-4">
                                            <a href="form-editar.php?id=<?php echo $p->getId(); ?>" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                                            <a href="../../controle/excluir-produto.php?id=<?php echo $p->getId(); ?>" class="btn btn-sm btn-danger ms-1" onclick="return confirm('Apagar?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../includes/rodape.php'; ?>
</body>
</html>