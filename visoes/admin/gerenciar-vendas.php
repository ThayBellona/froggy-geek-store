<?php
session_start();
require_once '../../daos/PedidoDAO.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { header("Location: ../../index.php"); exit; }

$pedidoDAO = new PedidoDAO();
$listaPedidos = $pedidoDAO->buscarTodosComCliente();
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Vendas - Admin</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="admin-page d-flex flex-column min-vh-100">

    <?php $caminho = '../..'; include '../../includes/menu.php'; ?>

    <div class="container mt-5 mb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="painel.php" class="btn btn-outline-dark rounded-pill px-3 mb-2 fw-bold bg-white shadow-sm">
                    <i class="bi bi-arrow-left"></i> Central
                </a>
                <h2 class="fw-bold text-dark">Gestão de Vendas</h2>
            </div>
            <div class="bg-white px-4 py-2 rounded-pill shadow-sm border border-2 border-danger">
                <span class="fw-bold text-danger fs-5"><?php echo count($listaPedidos); ?></span> pedidos
            </div>
        </div>

        <div class="card-admin">
            <div class="card-header text-white header-gradient-orange py-3">
                <h5 class="m-3 fw-bold"><i class="bi bi-receipt-cutoff"></i> Histórico de Transações</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-colored align-middle m-0">
                        <thead>
                            <tr>
                                <th class="ps-4">#ID</th>
                                <th>Data</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Ação</th>
                                <th class="text-end pe-4">Detalhes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($listaPedidos as $ped): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#<?php echo $ped['id']; ?></td>
                                <td><?php echo date('d/m H:i', strtotime($ped['data_compra'])); ?></td>
                                <td class="fw-bold text-dark"><?php echo $ped['nome_cliente']; ?></td>
                                <td class="text-success fw-bold">R$ <?php echo number_format($ped['valor_total'], 2, ',', '.'); ?></td>
                                
                                <td>
                                    <?php 
                                        $st = $ped['status'];
                                        $badge = 'bg-secondary';
                                        if($st=='Pendente') $badge='bg-warning text-dark';
                                        if($st=='Entregue') $badge='bg-success';
                                        if($st=='Cancelado') $badge='bg-danger';
                                    ?>
                                    <span class="badge <?php echo $badge; ?> rounded-pill px-3"><?php echo $st; ?></span>
                                </td>

                                <td>
                                    <form action="../../controle/atualizar-status-pedido.php" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="id_pedido" value="<?php echo $ped['id']; ?>">
                                        <select name="novo_status" class="form-select form-select-sm border-0 bg-light fw-bold" style="width: 130px;">
                                            <option value="Pendente" <?php echo ($st=='Pendente')?'selected':''; ?>>🟡 Pendente</option>
                                            <option value="Entregue" <?php echo ($st=='Entregue')?'selected':''; ?>>🟢 Entregue</option>
                                            <option value="Cancelado" <?php echo ($st=='Cancelado')?'selected':''; ?>>🔴 Cancelado</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-dark border-0" title="Salvar"><i class="bi bi-check-circle-fill fs-5"></i></button>
                                     </form>
                                </td>
                                
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light border text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#detalhes<?php echo $ped['id']; ?>">
                                        Ver Itens <i class="bi bi-chevron-down"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <tr class="collapse bg-light shadow-inset" id="detalhes<?php echo $ped['id']; ?>">
                                <td colspan="7" class="p-4">
                                    <div class="d-flex gap-3 flex-wrap">
                                        <?php 
                                            $itens = $pedidoDAO->buscarItensDoPedido($ped['id']);
                                            foreach($itens as $item):
                                        ?>
                                        <div class="bg-white p-2 rounded border d-flex align-items-center shadow-sm">
                                            <img src="../../<?php echo $item['imagem']; ?>" width="40" class="rounded me-2">
                                            <div class="lh-1">
                                                <small class="fw-bold d-block"><?php echo $item['nome']; ?></small>
                                                <small class="text-muted"><?php echo $item['quantidade']; ?>x R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></small>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../includes/rodape.php'; ?>

</body>
</html>