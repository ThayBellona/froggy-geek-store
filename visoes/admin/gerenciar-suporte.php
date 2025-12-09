<?php
session_start();
require_once '../../daos/SuporteDAO.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { header("Location: ../../index.php"); exit; }

$dao = new SuporteDAO();

// Processar Mudança de Status
if (isset($_POST['novo_status']) && isset($_POST['id_ticket'])) {
    $dao->atualizarStatus($_POST['id_ticket'], $_POST['novo_status']);
    header("Location: gerenciar-suporte.php"); exit;
}

$tickets = $dao->buscarTodosComUsuario();
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Suporte - Admin</title>
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
                <h2 class="fw-bold text-dark">Central de Suporte</h2>
            </div>
        </div>

        <div class="row g-4">
            <?php if(count($tickets) > 0): foreach($tickets as $t): 
                $corStatus = 'warning';
                if($t['status'] == 'Em Análise') $corStatus = 'info';
                if($t['status'] == 'Concluído') $corStatus = 'success';
            ?>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="badge bg-<?php echo $corStatus; ?> text-dark bg-opacity-25 border border-<?php echo $corStatus; ?>"><?php echo $t['status']; ?></span>
                        <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($t['data_abertura'])); ?></small>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-1"><?php echo $t['tipo_solicitacao']; ?></h5>
                        <p class="text-muted small mb-3">De: <strong><?php echo $t['nome']; ?></strong> (<?php echo $t['email']; ?>)</p>
                        
                        <div class="bg-light p-3 rounded-3 border mb-3 text-secondary fst-italic">
                            "<?php echo $t['mensagem']; ?>"
                        </div>

                        <form method="POST" class="d-flex gap-2">
                            <input type="hidden" name="id_ticket" value="<?php echo $t['id']; ?>">
                            <select name="novo_status" class="form-select form-select-sm">
                                <option value="Aberto">Reabrir</option>
                                <option value="Em Análise">Analisar</option>
                                <option value="Concluído">Finalizar</option>
                            </select>
                            <button class="btn btn-sm btn-dark fw-bold">Atualizar</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; else: ?>
                <div class="col-12 text-center text-muted py-5"><i class="bi bi-chat-square-heart fs-1 opacity-25"></i><p>Nenhum chamado aberto.</p></div>
            <?php endif; ?>
        </div>
    </div>
    <?php include '../../includes/rodape.php'; ?>
</body>
</html>