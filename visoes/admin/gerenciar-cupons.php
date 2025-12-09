<?php
session_start();
require_once '../../daos/CupomDAO.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { header("Location: ../../index.php"); exit; }
$dao = new CupomDAO();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $dao->criar($_POST['codigo'], $_POST['desconto']); }
if (isset($_GET['excluir'])) { $dao->excluir($_GET['excluir']); header("Location: gerenciar-cupons.php"); exit; }
$lista = $dao->buscarTodos();
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Cupons - Admin</title>
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
                <h2 class=" fw-bold text-dark">Cupons de Desconto</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-admin h-100">
                    <div class="card-header header-gradient-blue py-3">
                        <h5 class="m-1 ms-3 text-white fw-bold"><i class="bi bi-tag-fill me-2"></i>Criar Cupom</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Código (Ex: SALE10)</label>
                                <input type="text" name="codigo" class="form-control bg-light border-0 fw-bold text-uppercase text-primary" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Desconto (%)</label>
                                <input type="number" name="desconto" class="form-control bg-light border-0" placeholder="10" min="1" max="100" required>
                            </div>
                            <button class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Criar Cupom</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card-admin h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="m-0 fw-bold text-secondary">Cupons Ativos</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-colored align-middle m-0">
                                <thead><tr><th class="ps-4">Código</th><th>Desconto</th><th>Status</th><th class="text-end pe-4">Ação</th></tr></thead>
                                <tbody>
                                    <?php if(count($lista) > 0): foreach($lista as $cupom): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light text-primary border border-primary fs-6 px-3 py-2"><?php echo $cupom['codigo']; ?></span>
                                        </td>
                                        <td class="fw-bold text-success fs-5"><?php echo $cupom['desconto_percentual']; ?>%</td>
                                        <td><span class="badge bg-success rounded-pill">Ativo</span></td>
                                        <td class="text-end pe-4">
                                            <a href="?excluir=<?php echo $cupom['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle border-0" onclick="return confirm('Apagar?')"><i class="bi bi-trash-fill fs-5"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="4" class="text-center py-5 text-muted">Nenhum cupom ativo.</td></tr>
                                    <?php endif; ?>
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