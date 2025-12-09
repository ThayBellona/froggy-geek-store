<?php
session_start();
require_once '../daos/PedidoDAO.php';
require_once '../daos/UsuarioDAO.php';
require_once '../daos/CartaoDAO.php';
require_once '../daos/SuporteDAO.php';
require_once '../daos/CupomDAO.php'; // Para listar cupons ganhos

if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$pedidoDAO = new PedidoDAO();
$usuarioDAO = new UsuarioDAO();
$cartaoDAO = new CartaoDAO();
$suporteDAO = new SuporteDAO();
$cupomDAO = new CupomDAO();

$meusPedidos = $pedidoDAO->buscarPorUsuario($_SESSION['id_usuario']);
$usuario = $usuarioDAO->buscarPorId($_SESSION['id_usuario']);
$meusCartoes = $cartaoDAO->buscarPorUsuario($_SESSION['id_usuario']);
$meusTickets = $suporteDAO->buscarPorUsuario($_SESSION['id_usuario']);
$meusCupons = $cupomDAO->buscarPorUsuario($_SESSION['id_usuario']);

if(!$usuario) { header("Location: ../controle/logout.php"); exit; }

// Lógica da Foto
$foto = $usuario->getFotoPerfil();
$caminhoFotoDisplay = ($foto && file_exists("../".$foto)) ? "../$foto" : null;
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <meta charset="UTF-8">
    <title>Meu Perfil - Froggy Geek</title>
    
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* MENU LATERAL */
        .nav-pills .nav-link { color: #2e6417; font-weight: 600; border-radius: 10px; margin-bottom: 5px; transition: 0.3s; border: 1px solid #e8f5e9; }
        .nav-pills .nav-link:hover { background-color: #e8f5e9; color: #1b5e20; }
        .nav-pills .nav-link.active { background-color: #2e6417 !important; color: white !important; box-shadow: 0 4px 10px rgba(46, 100, 23, 0.3); }

        /* ESTILO DOS CUPONS */
        .ticket { position: relative; background: white; border-radius: 10px; overflow: hidden; border: 2px dashed #2e6417; transition: 0.3s; }
        .ticket.used { border-color: #ccc; background: #f9f9f9; opacity: 0.7; }
        .ticket.used .ticket-code { text-decoration: line-through; color: #999; }
        .ticket-left { padding: 20px; width: 70%; border-right: 2px dashed #2e6417; }
        .ticket.used .ticket-left { border-right-color: #ccc; }
        .ticket-right { width: 30%; display: flex; align-items: center; justify-content: center; flex-direction: column; background: #f0fdf4; }
        .ticket.used .ticket-right { background: #eee; }
        .ticket-code { font-family: 'Courier New', monospace; font-weight: 800; font-size: 1.2rem; color: #2e6417; letter-spacing: 1px; }

            /* --- MENU LATERAL --- */
        .nav-pills .nav-link { color: #555; font-weight: 600; border-radius: 10px; margin-bottom: 5px; transition: 0.3s; }
        .nav-pills .nav-link:hover { background-color: #e8f5e9; color: #2e6417; }
        .nav-pills .nav-link.active { background-color: #2e6417 !important; color: white !important; }

        /* --- CARTÃO DE CRÉDITO (LAYOUT CORRIGIDO) --- */
        .flip-card {
            background-color: transparent;
            width: 320px;  /* Largura padrão */
            height: 200px; /* Altura padrão */
            perspective: 1000px;
            color: white;
            font-family: 'Courier New', Courier, monospace;
            margin: 0 auto; /* Centraliza no container */
        }

        .flip-card-inner {
            position: relative; width: 100%; height: 100%; text-align: center;
            transition: transform 0.8s; transform-style: preserve-3d;
        }
        
        .virar-cartao { transform: rotateY(180deg); }

        .flip-card-front, .flip-card-back {
            position: absolute; display: flex; flex-direction: column;
            width: 100%; height: 100%;
            -webkit-backface-visibility: hidden; backface-visibility: hidden;
            border-radius: 15px;
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 0;
        }

        .flip-card-back { transform: rotateY(180deg); }

        /* --- ELEMENTOS DA FRENTE (POSIÇÃO ABSOLUTA) --- */
        
        /* Bandeira (Mastercard) */
        .logo { position: absolute; top: 20px; right: 20px; width: 50px; }
        
        /* Chip */
        .chip { position: absolute; top: 25px; left: 25px; width: 40px; border-radius: 5px; box-shadow: 1px 1px 2px rgba(0,0,0,0.3); }
        
        /* Ícone Contactless */
        .contactless { position: absolute; top: 35px; right: 80px; width: 20px; filter: invert(1); opacity: 0.7; }

        /* Número do Cartão (Bem no meio) */
        .number {
            position: absolute;
            top: 47%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            font-size: 1.3rem;
            font-weight: bold;
            letter-spacing: 3px;
            text-shadow: 0 2px 2px rgba(0,0,0,0.8);
            text-align: center;
            white-space: nowrap;
        }

        /* Nome do Titular (moved up to leave space under it) */
        .name {
            position: absolute;
            bottom: 45px;     /* subiu para abrir espaço abaixo */
            left: 5%;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            max-width: 260px;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* --- VALID THRU e DATA (agora aparecem abaixo do nome, alinhados à esquerda) --- */
        .label-valid {
            position: absolute;
            bottom: 40px;    /* leve ajuste para centralizar com o label */
            left: 5%;
            font-size: 0.55rem;
            text-transform: uppercase;
            opacity: 0.85;
            letter-spacing: 0.6px;
        }

        .date_8264 {
            position: absolute;
            bottom: 2px;    /* leve ajuste para centralizar com o label */
            left: 5%;
            font-size: 0.95rem;
            font-weight: bold;
        }

        /* small responsive adjustments */
        @media (max-width: 360px) {
            .flip-card { width: 290px; height: 180px; }
            .date_8264 { left: 100px; font-size: 0.85rem; }
            .number { font-size: 1.1rem; letter-spacing: 2px; }
        }

        /* --- ELEMENTOS DO VERSO --- */
        .strip {
            position: absolute; top: 25px; left: 0; width: 100%; height: 40px; background: black;
            border-top-left-radius: 15px; border-top-right-radius: 15px;
        }
        .mstrip {
            position: absolute; top: 85px; left: 15px; width: 70%; height: 35px; background: #eee; border-radius: 4px;
        }
        .sstrip {
            position: absolute; top: 85px; right: 15px; width: 20%; height: 35px; background: white; border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
        }
        .code { color: black; font-weight: bold; font-size: 1.1rem; margin: 0; }
        .cvv-label { position: absolute; top: 68px; right: 15px; font-size: 0.6rem; color: white; }

        
        .btn-delete-card { position: absolute; top: -10px; right: -10px; z-index: 10; width: 35px; height: 35px; border-radius: 50%; background-color: #dc3545; color: white; border: 3px solid white; display: flex; align-items: center; justify-content: center; box-shadow: 0 5px 15px rgba(0,0,0,0.2); text-decoration: none; transition: 0.3s; }
        .btn-delete-card:hover { background-color: #b02a37; transform: scale(1.1); color: white; }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = '..'; include '../includes/menu.php'; ?>

    <div class="container mt-4 mb-5 flex-grow-1">
        <div class="row">
            
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm border-0 text-center p-4 rounded-4 bg-white h-100">
                    <div class="mb-3 position-relative d-inline-block">
                        <?php if($caminhoFotoDisplay): ?>
                            <img src="<?php echo $caminhoFotoDisplay; ?>" class="rounded-circle border border-3 border-success p-1" style="width: 90px; height: 90px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px;"><i class="bi bi-person-fill text-success display-3"></i></div>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-0"><?php echo $usuario->getNome(); ?></h5>
                    <p class="text-muted small text-truncate"><?php echo $usuario->getEmail(); ?></p>
                    <hr>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                        <button class="nav-link active text-start mb-2 py-3 px-3" data-bs-toggle="pill" data-bs-target="#pedidos"><i class="bi bi-box-seam me-2"></i> Meus Pedidos</button>
                        <button class="nav-link text-start mb-2 py-3 px-3" data-bs-toggle="pill" data-bs-target="#dados"><i class="bi bi-person-gear me-2"></i> Meus Dados</button>
                        <button class="nav-link text-start mb-2 py-3 px-3" data-bs-toggle="pill" data-bs-target="#cartoes"><i class="bi bi-credit-card me-2"></i> Cartões</button>
                        <button class="nav-link text-start mb-2 py-3 px-3" data-bs-toggle="pill" data-bs-target="#cupons"><i class="bi bi-ticket-perforated me-2"></i> Meus Cupons</button>
                        <button class="nav-link text-start py-3 px-3" data-bs-toggle="pill" data-bs-target="#suporte"><i class="bi bi-headset me-2"></i> Suporte</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="pedidos">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-4 text-success">Histórico de Pedidos</h4>
                                <?php if(count($meusPedidos) == 0): ?>
                                    <div class="text-center py-5"><i class="bi bi-cart-x fs-1 text-muted"></i><p class="mt-3">Você ainda não fez nenhuma compra.</p><a href="../index.php" class="btn btn-frog">Ir as Compras</a></div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light"><tr><th>Pedido</th><th>Data</th><th>Status</th><th>Total</th></tr></thead>
                                            <tbody>
                                                <?php foreach($meusPedidos as $ped): ?>
                                                <tr>
                                                    <td class="fw-bold text-primary">#<?php echo $ped['id']; ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($ped['data_compra'])); ?></td>
                                                    <td><span class="badge bg-secondary rounded-pill"><?php echo $ped['status']; ?></span></td>
                                                    <td class="text-success fw-bold">R$ <?php echo number_format($ped['valor_total'], 2, ',', '.'); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="dados">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-4 text-success">Editar Perfil</h4>
                                <?php if(isset($_GET['msg']) && $_GET['msg'] == 'sucesso'): ?><div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-4"><i class="bi bi-check-circle me-2"></i> Dados atualizados!</div><?php endif; ?>
                                <form action="../controle/processar-perfil.php" method="POST" enctype="multipart/form-data">
                                    <div class="row g-4">
                                        <div class="col-12"><label class="form-label fw-bold text-secondary">Foto de Perfil</label><input type="file" name="foto" class="form-control bg-light border-0" accept="image/*"><small class="text-muted">Deixe vazio para manter a atual.</small></div>
                                        <div class="col-md-12"><label class="form-label fw-bold text-secondary">Nome Completo</label><input type="text" name="nome" class="form-control bg-light border-0" value="<?php echo $usuario->getNome(); ?>" required></div>
                                        <div class="col-md-6"><label class="form-label fw-bold text-secondary">Data Nascimento</label><input type="date" name="data_nascimento" class="form-control bg-light border-0" value="<?php echo method_exists($usuario, 'getDataNascimento') ? $usuario->getDataNascimento() : ''; ?>"></div>
                                        <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary">Gênero</label>
                                        <select name="genero" class="form-select bg-light border-0">
                                            <option value="Não informado">Selecione...</option>
                                            <?php 
                                                // LISTA PADRONIZADA
                                                $generos = [
                                                    'Feminino', 
                                                    'Masculino', 
                                                    'Não-binárie', 
                                                    'Outro', 
                                                    'Prefiro não dizer'
                                                ];
                                                
                                                // Verifica o valor atual do usuário
                                                $genAtual = method_exists($usuario, 'getGenero') ? $usuario->getGenero() : '';
                                                
                                                foreach($generos as $g) {
                                                    // Marca como 'selected' se for igual ao do banco
                                                    $sel = ($genAtual == $g) ? 'selected' : '';
                                                    echo "<option value='$g' $sel>$g</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                        <div class="col-md-12"><label class="form-label fw-bold text-secondary">Nova Senha <small class="text-muted fw-normal">(Opcional)</small></label><input type="password" name="senha" class="form-control bg-light border-0" placeholder="Deixe vazio para manter"></div>
                                        <div class="col-12 pt-2"><button class="btn btn-frog px-5">Salvar Alterações</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="cartoes">
                        <div class="row g-4">
                            <?php if(count($meusCartoes) > 0): ?>
                                <div class="col-12"><h5 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-success">Meus Cartões Salvos</h5></div>
                                <?php foreach($meusCartoes as $c): ?>
                                <div class="col-lg-6 d-flex justify-content-center mb-4 position-relative">
                                    <a href="../controle/excluir-cartao.php?id=<?php echo $c['id']; ?>" class="btn-delete-card" onclick="return confirm('Remover cartão?')"><i class="bi bi-trash"></i></a>
                                    <div class="flip-card">
                                        <div class="flip-card-inner">
                                            <div class="flip-card-front">
                                                <svg class="logo" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 48 48"><path fill="#ff9800" d="M32 10A14 14 0 1 0 32 38A14 14 0 1 0 32 10Z"></path><path fill="#d50000" d="M16 10A14 14 0 1 0 16 38A14 14 0 1 0 16 10Z"></path><path fill="#ff3d00" d="M18,24c0,4.755,2.376,8.95,6,11.48c3.624-2.53,6-6.725,6-11.48s-2.376-8.95-6-11.48 C20.376,15.05,18,19.245,18,24z"></path></svg>
                                                <img class="chip" src="https://raw.githubusercontent.com/dasShounak/freeUseImages/main/chip.png">
                                                <p class="number">**** **** **** <?php echo $c['numero_final']; ?></p>
                                                <div class="label-valid">VÁLIDO ATÉ</div>
                                                <p class="date_8264"><?php echo $c['validade']; ?></p>
                                                <p class="name"><?php echo $c['nome_titular']; ?></p>
                                                <span class="position-absolute top-0 start-50 translate-middle-x mt-3 badge bg-white text-dark opacity-75 border"><?php echo $c['apelido_cartao']; ?></span>
                                            </div>
                                            <div class="flip-card-back"><div class="strip"></div><div class="mstrip"></div><div class="sstrip"></div></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <div class="col-12"><hr class="my-4"></div>
                            <?php endif; ?>

                            <div class="col-12"><h5 class="fw-bold text-success mb-3 ps-2 border-start border-4 border-success">Adicionar Novo Cartão</h5></div>
                            <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white border rounded-4 p-4 shadow-sm">
                                <div class="flip-card" id="cardVisual">
                                    <div class="flip-card-inner" id="cardInner">
                                        <div class="flip-card-front">
                                            <svg class="logo" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 48 48"><path fill="#ff9800" d="M32 10A14 14 0 1 0 32 38A14 14 0 1 0 32 10Z"></path><path fill="#d50000" d="M16 10A14 14 0 1 0 16 38A14 14 0 1 0 16 10Z"></path><path fill="#ff3d00" d="M18,24c0,4.755,2.376,8.95,6,11.48c3.624-2.53,6-6.725,6-11.48s-2.376-8.95-6-11.48 C20.376,15.05,18,19.245,18,24z"></path></svg>
                                            <img class="chip" src="https://raw.githubusercontent.com/dasShounak/freeUseImages/main/chip.png">
                                            <p class="number" id="displayNumero">0000 0000 0000 0000</p>
                                            <div class="label-valid">VÁLIDO ATÉ</div>
                                            <p class="date_8264" id="displayData">MM / AA</p>
                                            <p class="name" id="displayNome">NOME DO TITULAR</p>
                                        </div>
                                        <div class="flip-card-back"><div class="strip"></div><div class="mstrip"></div><div class="sstrip"><p class="code" id="displayCVV">***</p></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                                    <form action="../controle/salvar-cartao.php" method="POST">
                                        <div class="mb-3"><label class="form-label small fw-bold text-muted">Número</label><input type="text" class="form-control bg-light border-0" id="inputNumero" name="numero" maxlength="19" required></div>
                                        <div class="mb-3"><label class="form-label small fw-bold text-muted">Nome</label><input type="text" class="form-control bg-light border-0" id="inputNome" name="nome_cartao" maxlength="20" required></div>
                                        <div class="row">
                                            <div class="col-6 mb-3"><label class="form-label small fw-bold text-muted">Validade</label><input type="text" class="form-control bg-light border-0" id="inputData" name="validade" maxlength="5" placeholder="MM/AA" required></div>
                                            <div class="col-6 mb-3"><label class="form-label small fw-bold text-muted">CVV</label><input type="text" class="form-control bg-light border-0" id="inputCVV" name="cvv" maxlength="3" required></div>
                                        </div>
                                        <div class="mb-3"><label class="form-label small fw-bold text-muted">Apelido</label><input type="text" name="apelido" class="form-control bg-light border-0" placeholder="Ex: Meu Nubank" required></div>
                                        <button type="submit" class="btn btn-frog w-100 shadow-sm">Salvar Cartão</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="cupons">
                        <div class="row g-4">
                            <div class="col-12">
                                <h4 class="fw-bold text-success mb-3 ps-2 border-start border-4 border-success">Minha Carteira de Descontos</h4>
                                <?php if(count($meusCupons) > 0): ?>
                                    <div class="row">
                                        <?php foreach($meusCupons as $c): 
                                            $classe = $c['usado'] ? 'used' : '';
                                            $status = $c['usado'] ? 'USADO' : 'ATIVO';
                                            $corBadge = $c['usado'] ? 'bg-secondary' : 'bg-success';
                                        ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="ticket <?php echo $classe; ?> d-flex shadow-sm">
                                                <div class="ticket-left">
                                                    <span class="badge <?php echo $corBadge; ?> mb-2"><?php echo $status; ?></span>
                                                    <h3 class="fw-bold m-0"><?php echo $c['desconto_percentual']; ?>% OFF</h3>
                                                    <small class="text-muted">Ganho em: <?php echo date('d/m/Y', strtotime($c['data_ganho'])); ?></small>
                                                </div>
                                                <div class="ticket-right">
                                                    <span class="ticket-code"><?php echo $c['codigo']; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-ticket-perforated fs-1 opacity-25"></i>
                                        <p class="mt-2">Você ainda não ganhou nenhum cupom.</p>
                                        <small>Continue comprando para ter a chance de ganhar!</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="suporte">
                        <div class="row g-4">
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                                    <h5 class="fw-bold text-success mb-3">Abrir Chamado</h5>
                                    <?php if(isset($_GET['msg']) && $_GET['msg']=='suporte_ok'): ?><div class="alert alert-success small">Solicitação enviada!</div><?php endif; ?>
                                    
                                    <form action="../controle/processar-suporte.php" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Tipo de Solicitação</label>
                                            <select name="tipo" id="tipoSolicitacao" class="form-select bg-light border-0" required onchange="verificarTipo()">
                                                <option value="" selected disabled>Selecione...</option>
                                                <option value="Dúvida">Dúvida sobre Produto</option>
                                                <option value="Reembolso">Reembolso/Troca (Requer Pedido)</option>
                                                <option value="Problema Técnico">Problema Técnico</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                        </div>

                                        <div class="mb-3" id="divPedido" style="display: none;">
                                            <label class="form-label small fw-bold text-success">Selecione o Pedido *</label>
                                            <select name="id_pedido" id="selectPedido" class="form-select border-success text-success fw-bold">
                                                <option value="">Escolha...</option>
                                                <?php foreach($meusPedidos as $ped): ?>
                                                    <option value="<?php echo $ped['id']; ?>">
                                                        Pedido #<?php echo $ped['id']; ?> (R$ <?php echo number_format($ped['valor_total'], 2, ',', '.'); ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Mensagem</label>
                                            <textarea name="mensagem" class="form-control bg-light border-0" rows="5" required placeholder="Descreva seu problema..."></textarea>
                                        </div>
                                        <button class="btn btn-frog w-100">Enviar Solicitação</button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <h5 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-warning">Meus Chamados</h5>
                                <?php if(count($meusTickets) > 0): foreach($meusTickets as $t): 
                                    $cor = ($t['status']=='Concluído') ? 'success' : 'warning';    
                                ?>
                                    <div class="card border-0 shadow-sm mb-3 rounded-3">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <span class="fw-bold text-dark"><?php echo $t['tipo_solicitacao']; ?></span>
                                                    <?php if(!empty($t['id_pedido'])): ?>
                                                        <span class="badge bg-light text-dark border ms-2">Ref. #<?php echo $t['id_pedido']; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="badge bg-<?php echo $cor; ?> rounded-pill"><?php echo $t['status']; ?></span>
                                            </div>
                                            <p class="text-muted small mb-0 text-truncate"><?php echo $t['mensagem']; ?></p>
                                            <small class="text-secondary opacity-50" style="font-size: 0.7rem;"><?php echo date('d/m/Y H:i', strtotime($t['data_abertura'])); ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?><div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 opacity-25"></i><p class="small mt-2">Nenhum chamado aberto.</p></div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/rodape.php'; ?>

    <script>
        // Scripts do cartão
        const cardInner = document.getElementById('cardInner');
        const inNum = document.getElementById('inputNumero');
        const inNome = document.getElementById('inputNome');
        const inData = document.getElementById('inputData');
        const inCVV = document.getElementById('inputCVV');
        const dispNum = document.getElementById('displayNumero');
        const dispNome = document.getElementById('displayNome');
        const dispData = document.getElementById('displayData');
        const dispCVV = document.getElementById('displayCVV');

        inNum.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '').substring(0,16);
            val = val.replace(/(.{4})/g, '$1 ').trim();
            e.target.value = val;
            dispNum.innerText = val || '0000 0000 0000 0000';
        });
        inNome.addEventListener('input', (e) => { dispNome.innerText = e.target.value.toUpperCase() || 'NOME DO TITULAR'; });
        inData.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            if(val.length >= 2) val = val.substring(0,2) + '/' + val.substring(2,4);
            e.target.value = val;
            dispData.innerText = val || 'MM / AA';
        });
        inCVV.addEventListener('focus', () => { cardInner.classList.add('virar-cartao'); });
        inCVV.addEventListener('blur', () => { cardInner.classList.remove('virar-cartao'); });
        inCVV.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0,3);
            dispCVV.innerText = e.target.value || '***';
        });

        // Lógica do Suporte (Reembolso)
        function verificarTipo() {
            const tipo = document.getElementById('tipoSolicitacao').value;
            const divPedido = document.getElementById('divPedido');
            const selectPedido = document.getElementById('selectPedido');

            if (tipo === 'Reembolso') {
                divPedido.style.display = 'block';
                selectPedido.required = true;
            } else {
                divPedido.style.display = 'none';
                selectPedido.required = false;
                selectPedido.value = "";
            }
        }
    </script>

</body>
</html>