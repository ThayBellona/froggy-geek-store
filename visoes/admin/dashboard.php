<?php
session_start();
require_once '../../daos/DashboardDAO.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { header("Location: ../../index.php"); exit; }

$dao = new DashboardDAO();
$resumo = $dao->getResumo();        
$vendasMes = $dao->getVendasPorMes(); 
$topVendidos = $dao->getTopProdutosVendidos();
$topAvaliados = $dao->getTopProdutosAvaliados();
$idadeMedia = $dao->getIdadeMediaPorProduto();
$cupomStats = $dao->getStatsCupons();

// NOVOS DADOS
$generos = $dao->getVendasPorGenero();
$rankingCupons = $dao->getRankingCupons();
$fidelidade = $dao->getFidelidadeClientes();

// Preparar dados Chart.js
// Vendas
$mesesC = []; $valC = []; $qtdC = [];
$nomesMeses = [1=>'Jan', 2=>'Fev', 3=>'Mar', 4=>'Abr', 5=>'Mai', 6=>'Jun', 7=>'Jul', 8=>'Ago', 9=>'Set', 10=>'Out', 11=>'Nov', 12=>'Dez'];
foreach($vendasMes as $v) {
    $mesesC[] = isset($v['mes_nome']) ? $v['mes_nome'] : (isset($nomesMeses[$v['mes_num']]) ? $nomesMeses[$v['mes_num']] : $v['mes_num']);
    $valC[] = $v['total_valor'];
    $qtdC[] = $v['total_qtd'];
}

// Gênero
$genLabels = []; $genData = [];
foreach($generos as $g) { $genLabels[] = $g['genero']; $genData[] = $g['total']; }

// Fidelidade
$fidLabels = []; $fidData = [];
foreach($fidelidade as $f) { $fidLabels[] = $f['compras_feitas'] . ' Pedido(s)'; $fidData[] = $f['qtd_clientes']; }

// Cupons
$cupLabels = []; $cupData = [];
foreach($rankingCupons as $c) { $cupLabels[] = $c['codigo']; $cupData[] = $c['uso']; }
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
 <link rel="icon" href="../../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">
    <title>Dashboard - Froggy Geek</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="admin-page d-flex flex-column min-vh-100">

    <?php $caminho = '../..'; include '../../includes/menu.php'; ?>

    <div class="container mt-4 mb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <a href="painel.php" class="btn btn-outline-dark rounded-pill px-3 mb-2 fw-bold bg-white shadow-sm"><i class="bi bi-arrow-left"></i> Central</a>
                <h2 class="fw-bold text-dark m-0">Dashboard Analítico</h2>
            </div>
            <button class="btn btn-dark d-print-none shadow rounded-pill px-4" onclick="window.print()"><i class="bi bi-printer me-2"></i> PDF</button>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3"><div class="kpi-box bg-prod shadow"><p class="mb-0 opacity-75">Faturamento</p><h3 class="mb-0">R$ <?php echo number_format($resumo['vendas'], 2, ',', '.'); ?></h3><i class="bi bi-cash-stack kpi-icon-bg"></i></div></div>
            <div class="col-md-3"><div class="kpi-box bg-sales shadow"><p class="mb-0 opacity-75">Produtos</p><h3 class="mb-0"><?php echo $resumo['produtos']; ?></h3><i class="bi bi-box-seam kpi-icon-bg"></i></div></div>
            <div class="col-md-3"><div class="kpi-box bg-cupons shadow"><p class="mb-0 opacity-75">Clientes</p><h3 class="mb-0"><?php echo $resumo['clientes']; ?></h3><i class="bi bi-people-fill kpi-icon-bg"></i></div></div>
            <div class="col-md-3"><div class="kpi-box bg-dash shadow" style="background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%);"><p class="mb-0 opacity-75">Cupons Ativos</p><h3 class="mb-0"><?php echo $cupomStats['ativos']; ?></h3><i class="bi bi-ticket-perforated-fill kpi-icon-bg"></i></div></div>
        </div>

        <div class="card-admin mb-5">
            <div class="card-header header-gradient-green py-3"><h5 class="m-1 ms-3 text-white fw-bold"><i class="bi bi-graph-up-arrow me-2"></i> Fluxo Financeiro</h5></div>
            <div class="card-body p-4">
                <canvas id="vendasChart" style="max-height: 350px;"></canvas>
            </div>
        </div>

        <h4 class="fw-bold mb-4 text-dark ps-2 border-start border-4 border-success">Destaques de Produto</h4>
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="card-admin h-100">
                    <div class="card-header header-gradient-orange py-3"><h6 class="m-1 ms-3 text-white fw-bold"><i class="bi bi-bag-heart-fill me-2"></i> Mais Vendidos</h6></div>
                    <div class="card-body p-4">
                        <?php if(count($topVendidos) > 0): $maxQtd = $topVendidos[0]['total']; foreach($topVendidos as $prod): $largura = ($prod['total'] / $maxQtd) * 100; ?>
                            <div class="d-flex align-items-center mb-3">
                                <img src="../../<?php echo $prod['imagem']; ?>" width="45" height="45" class="rounded-3 border me-3 object-fit-contain bg-light">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between small mb-1"><span class="fw-bold text-dark text-truncate" style="max-width: 140px;"><?php echo $prod['nome']; ?></span><span class="badge bg-success bg-opacity-10 text-success border border-success"><?php echo $prod['total']; ?> un.</span></div>
                                    <div class="progress" style="height: 8px; border-radius: 10px;"><div class="progress-bar bg-warning" style="width: <?php echo $largura; ?>%; border-radius: 10px;"></div></div>
                                </div>
                            </div>
                        <?php endforeach; else: ?><p class="text-muted text-center">Sem vendas.</p><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-admin h-100">
                    <div class="card-header header-gradient-blue py-3"><h6 class="m-1 ms-3 text-white fw-bold"><i class="bi bi-people-fill me-2"></i> Idade Média</h6></div>
                    <div class="card-body p-4">
                        <?php if(count($idadeMedia) > 0): foreach($idadeMedia as $item): $idade = round($item['idade_media']); $largura = ($idade / 60) * 100; ?>
                            <div class="d-flex align-items-center mb-3">
                                <img src="../../<?php echo $item['imagem']; ?>" width="45" height="45" class="rounded-3 border me-3 object-fit-contain bg-light">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between small mb-1"><span class="fw-bold text-dark text-truncate" style="max-width: 140px;"><?php echo $item['nome']; ?></span><span class="text-primary fw-bold small"><?php echo $idade; ?> anos</span></div>
                                    <div class="progress" style="height: 8px; border-radius: 10px;"><div class="progress-bar bg-info" style="width: <?php echo $largura; ?>%; border-radius: 10px;"></div></div>
                                </div>
                            </div>
                        <?php endforeach; else: ?><p class="text-muted text-center">Sem dados.</p><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-admin h-100">
                    <div class="card-header header-gradient-purple py-3"><h6 class="m-1 ms-3 text-white fw-bold"><i class="bi bi-star-fill me-2"></i> Top Avaliados</h6></div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php if(count($topAvaliados) > 0): foreach($topAvaliados as $av): ?>
                                <li class="list-group-item d-flex align-items-center py-3 px-4 border-bottom-0">
                                    <div class="position-relative me-3">
                                        <img src="../../<?php echo $av['imagem']; ?>" width="45" height="45" class="rounded-3 border object-fit-contain bg-light">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success border border-light"><?php echo number_format($av['media'], 1); ?></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-bold text-dark d-block text-truncate small" style="max-width: 150px;"><?php echo $av['nome']; ?></span>
                                        <div class="text-warning" style="font-size: 0.7rem; letter-spacing: 1px;"><?php for($i=0; $i<round($av['media']); $i++) echo '★'; ?></div>
                                    </div>
                                </li>
                            <?php endforeach; else: ?><li class="list-group-item text-center py-5 text-muted">Sem avaliações.</li><?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="fw-bold mb-4 text-dark ps-2 border-start border-4 border-warning">Estatísticas de Público</h4>
        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card-admin h-100">
                    <div class="card-header header-gradient-blue py-3">
                        <h6 class="m-1 ms-3 text-white "><i class="bi bi-gender-ambiguous me-2"></i> Perfil de Gênero</h6>
                    </div>
                    <div class="card-body d-flex justify-content-center p-4">
                        <div style="width: 220px;"><canvas id="generoChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-admin h-100">
                    <div class="card-header header-gradient-purple py-3">
                        <h6 class="m-1 ms-3 text-white "><i class="bi bi-arrow-repeat me-2"></i> Fidelidade (Recompra)</h6>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="fidelidadeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-admin h-100">
                    <div class="card-header header-gradient-orange py-3">
                        <h6 class="m-1 ms-3 text-white "><i class="bi bi-ticket-perforated-fill me-2"></i> Ranking de Cupons</h6>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="cupomChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

 <?php include '../../includes/rodape.php'; ?>

    <script>
        // Função para garantir que o JSON não quebre com acentos
        // Se o PHP for antigo ou o dado vier corrompido, isso evita o erro "Unexpected token"
        <?php 
        function safe_json($data) {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
            if ($json === false) {
                // Tenta limpar strings inválidas se a primeira tentativa falhar
                $data = array_map(function($item) {
                    return is_string($item) ? utf8_encode($item) : $item;
                }, $data);
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
            }
            return $json ?: '[]'; // Retorna array vazio se tudo falhar
        }
        ?>

        // 1. VENDAS
        new Chart(document.getElementById('vendasChart'), {
            type: 'bar',
            data: {
                labels: <?php echo safe_json($mesesC); ?>,
                datasets: [
                    { 
                        label: 'Faturamento (R$)', 
                        data: <?php echo safe_json($valC); ?>, 
                        backgroundColor: 'rgba(67, 160, 71, 0.6)', 
                        borderColor: '#2e7d32', 
                        borderWidth: 1, 
                        order: 2 
                    },
                    { 
                        label: 'Qtd. Pedidos', 
                        data: <?php echo safe_json($qtdC); ?>, 
                        type: 'line', 
                        borderColor: '#ff9800', 
                        backgroundColor: '#ff9800', 
                        borderWidth: 3, 
                        yAxisID: 'y1', 
                        order: 1 
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                scales: { 
                    y: {beginAtZero: true}, 
                    y1: {beginAtZero: true, position: 'right', grid: {drawOnChartArea: false}} 
                } 
            }
        });

        // 2. GÊNERO (Principal suspeito de erro por causa de "Não-binário")
        new Chart(document.getElementById('generoChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo safe_json($genLabels); ?>,
                datasets: [{ 
                    data: <?php echo safe_json($genData); ?>, 
                    backgroundColor: ['#42a5f5', '#ef5350', '#ffca28', '#ab47bc', '#26a69a'], 
                    borderWidth: 0 
                }]
            },
            options: { plugins: { legend: {position: 'bottom'} } }
        });

        // 3. FIDELIDADE
        new Chart(document.getElementById('fidelidadeChart'), {
            type: 'bar',
            data: {
                labels: <?php echo safe_json($fidLabels); ?>,
                datasets: [{ 
                    label: 'Clientes', 
                    data: <?php echo safe_json($fidData); ?>, 
                    backgroundColor: '#5c6bc0', 
                    borderRadius: 4 
                }]
            },
            options: { plugins: { legend: {display: false} }, scales: { y: {beginAtZero: true} } }
        });

        // 4. CUPONS
        new Chart(document.getElementById('cupomChart'), {
            type: 'bar',
            data: {
                labels: <?php echo safe_json($cupLabels); ?>,
                datasets: [{ 
                    label: 'Usos', 
                    data: <?php echo safe_json($cupData); ?>, 
                    backgroundColor: '#ef5350', 
                    borderRadius: 4 
                }]
            },
            options: { indexAxis: 'y', plugins: { legend: {display: false} }, scales: { x: {beginAtZero: true} } }
        });
    </script>

</body>
</html>