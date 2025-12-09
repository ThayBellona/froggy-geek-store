<?php
session_start();
require_once '../daos/ProdutoDAO.php';
require_once '../daos/CartaoDAO.php';

if (!isset($_SESSION['id_usuario']) || empty($_SESSION['carrinho'])) { header("Location: ./login.php"); exit; }

$dao = new ProdutoDAO();
$cartaoDAO = new CartaoDAO();
$meusCartoes = $cartaoDAO->buscarPorUsuario($_SESSION['id_usuario']);

$total = 0;
foreach ($_SESSION['carrinho'] as $chave => $qtd) {
    $partes = explode('_', $chave);
    $prod = $dao->buscarPorId($partes[0]);
    if($prod) $total += $prod->getPreco() * $qtd;
}

$desconto = 0;
$cupomAtivo = isset($_SESSION['cupom_ativo']) ? $_SESSION['cupom_ativo'] : null;
if($cupomAtivo) {
    $desconto = $total * ($cupomAtivo['desconto'] / 100);
}
$totalFinal = $total - $desconto;
?>

<!DOCTYPE html>
<html lang="pt-br"> 
    <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Checkout - Froggy Geek</title>
    <link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .card-pickup { border: 2px dashed #2e6417; background-color: #f0fdf4; }
        .form-check-input:checked { background-color: #2e6417; border-color: #2e6417; }
        .option-card { transition: 0.2s; cursor: pointer; }
        .option-card:hover { background-color: #f8f9fa; }
        .form-check-input:checked + label .option-card { background-color: #e8f5e9; border-color: #2e6417 !important; }

        /* --- CSS DO BOTÃO DE PAGAMENTO ANIMADO --- */
        /* Renomeei as classes para não conflitar com o Bootstrap */
        
        .pay-btn-group {
            background-color: #ffffff;
            display: flex;
            width: 100%; /* Ajustável */
            max-width: 460px;
            height: 100px;
            position: relative;
            border-radius: 6px;
            transition: 0.3s ease-in-out;
            cursor: pointer;
            border: 1px solid #ddd;
            margin: 0 auto; /* Centralizar */
        }

        .pay-btn-group:hover {
            transform: scale(1.03);
        }

        .pay-btn-group:hover .pay-left-side {
            width: 100%;
        }

        .pay-left-side {
            background-color: #5de2a3;
            width: 130px;
            height: 100%;
            border-radius: 4px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: 0.3s;
            flex-shrink: 0;
            overflow: hidden;
        }

        .pay-right-side {
            width: calc(100% - 130px);
            display: flex;
            align-items: center;
            overflow: hidden;
            cursor: pointer;
            justify-content: space-between;
            white-space: nowrap;
            transition: 0.3s;
            padding: 0 20px;
        }

        .pay-right-side:hover {
            background-color: #f9f7f9;
        }

        .pay-arrow {
            width: 20px;
            height: 20px;
            margin-right: 20px;
        }

        .pay-new {
            font-size: 18px;
            font-weight: bold;
            font-family: "Poppins", sans-serif;
            color: #333;
        }

        .pay-card-anim {
            width: 70px;
            height: 46px;
            background-color: #c7ffbc;
            border-radius: 6px;
            position: absolute;
            display: flex;
            z-index: 10;
            flex-direction: column;
            align-items: center;
            box-shadow: 9px 9px 9px -2px rgba(77, 200, 143, 0.72);
        }

        .pay-card-line {
            width: 65px;
            height: 13px;
            background-color: #80ea69;
            border-radius: 2px;
            margin-top: 7px;
        }

        .pay-buttons {
            width: 8px;
            height: 8px;
            background-color: #379e1f;
            box-shadow: 0 -10px 0 0 #26850e, 0 10px 0 0 #56be3e;
            border-radius: 50%;
            margin-top: 5px;
            transform: rotate(90deg);
            margin: 10px 0 0 -30px;
        }

        .pay-btn-group:hover .pay-card-anim {
            animation: slide-top 1.2s cubic-bezier(0.645, 0.045, 0.355, 1) both;
        }

        .pay-btn-group:hover .pay-post {
            animation: slide-post 1s cubic-bezier(0.165, 0.84, 0.44, 1) both;
        }

        @keyframes slide-top {
            0% { transform: translateY(0); }
            50% { transform: translateY(-70px) rotate(90deg); }
            60% { transform: translateY(-70px) rotate(90deg); }
            100% { transform: translateY(-8px) rotate(90deg); }
        }

        .pay-post {
            width: 63px;
            height: 75px;
            background-color: #dddde0;
            position: absolute;
            z-index: 11;
            bottom: 10px;
            top: 120px;
            border-radius: 6px;
            overflow: hidden;
        }

        .pay-post-line {
            width: 47px;
            height: 9px;
            background-color: #545354;
            position: absolute;
            border-radius: 0px 0px 3px 3px;
            right: 8px;
            top: 8px;
        }

        .pay-post-line:before {
            content: "";
            position: absolute;
            width: 47px;
            height: 9px;
            background-color: #757375;
            top: -8px;
        }

        .pay-screen {
            width: 47px;
            height: 23px;
            background-color: #ffffff;
            position: absolute;
            top: 22px;
            right: 8px;
            border-radius: 3px;
        }

        .pay-numbers {
            width: 12px;
            height: 12px;
            background-color: #838183;
            box-shadow: 0 -18px 0 0 #838183, 0 18px 0 0 #838183;
            border-radius: 2px;
            position: absolute;
            transform: rotate(90deg);
            left: 25px;
            top: 52px;
        }

        .pay-numbers-line2 {
            width: 12px;
            height: 12px;
            background-color: #aaa9ab;
            box-shadow: 0 -18px 0 0 #aaa9ab, 0 18px 0 0 #aaa9ab;
            border-radius: 2px;
            position: absolute;
            transform: rotate(90deg);
            left: 25px;
            top: 68px;
        }

        @keyframes slide-post {
            50% { transform: translateY(0); }
            100% { transform: translateY(-70px); }
        }

        .pay-dollar {
            position: absolute;
            font-size: 16px;
            width: 100%;
            left: 0;
            top: 0;
            color: #4b953b;
            text-align: center;
        }

        .pay-btn-group:hover .pay-dollar {
            animation: fade-in-fwd 0.3s 1s backwards;
        }

        @keyframes fade-in-fwd {
            0% { opacity: 0; transform: translateY(-5px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Reset para o botão invisivel */
        .btn-invisible-submit {
            background: none;
            border: none;
            padding: 0;
            width: 100%;
            display: block;
        }
    </style>
    
    <script>
        function togglePagamento() {
            const radioNovo = document.getElementById('c_novo');
            const radioPix = document.getElementById('pix');
            const areaNovo = document.getElementById('area-novo-cartao');
            const areaPix = document.getElementById('area-pix');
            const inputs = areaNovo.querySelectorAll('input');

            areaNovo.style.display = 'none';
            areaPix.style.display = 'none';
            inputs.forEach(i => { if(i.type !== 'checkbox') i.required = false; });

            if (radioNovo && radioNovo.checked) {
                areaNovo.style.display = 'block';
                inputs.forEach(i => { if(i.type !== 'checkbox') i.required = true; });
            } else if (radioPix && radioPix.checked) {
                areaPix.style.display = 'block';
            }
        }
    </script>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = '..'; include '../includes/menu.php'; ?>

    <div class="container mt-5 mb-5 flex-grow-1">
        <div class="d-flex align-items-center mb-4">
            <a href="carrinho.php" class="btn btn-outline-secondary rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
            <h2 class="text-dark fw-bold m-0">Finalizar Pedido</h2>
        </div>
        
        <div class="row g-5">
            
            <div class="col-md-7 order-md-1">
                
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="m-0 text-success fw-bold"><i class="bi bi-geo-alt-fill me-2"></i> Forma de Entrega</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="card card-pickup p-3 border-0 rounded-3">
                            <div class="d-flex">
                                <div class="me-3"><i class="bi bi-shop fs-1 text-success"></i></div>
                                <div>
                                    <h5 class="fw-bold text-dark m-0">Retirada na Loja (Grátis)</h5>
                                    <p class="mb-2 text-muted small">Seu pedido estará pronto em até 2 horas.</p>
                                    <hr class="my-2 text-success opacity-25">
                                    <strong>📍 Froggy Geek Store</strong><br>
                                    <span class="text-muted">Laboratorio 2 - Av. Brasil, 920 - Xavier Maia, Rio Branco - AC</span><br>
                                    <small class="text-success fw-bold">Aberto: Seg a Sex das 09h às 18h</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="m-0 text-success fw-bold"><i class="bi bi-credit-card me-2"></i> Pagamento</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <form action="../controle/processar-compra.php" method="POST" id="formCheckout">
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small mb-3">ESCOLHA O MÉTODO:</label>

                                <?php if(count($meusCartoes) > 0): ?>
                                    <?php foreach($meusCartoes as $c): ?>
                                    <div class="position-relative mb-2">
                                        <input class="form-check-input position-absolute top-50 start-0 ms-3 translate-middle-y" 
                                               type="radio" name="cartao_selecionado" id="c_<?php echo $c['id']; ?>" value="<?php echo $c['id']; ?>" 
                                               onclick="togglePagamento()" style="z-index: 5;">
                                        <label class="w-100 p-3 ps-5 rounded-3 border option-card d-flex align-items-center justify-content-between bg-white" for="c_<?php echo $c['id']; ?>">
                                            <span>
                                                <strong><?php echo $c['apelido_cartao']; ?></strong>
                                                <small class="text-muted"> • Final <?php echo $c['numero_final']; ?></small>
                                            </span>
                                            <i class="bi bi-credit-card-2-front text-secondary fs-4"></i>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <div class="position-relative mb-2">
                                    <input class="form-check-input position-absolute top-50 start-0 ms-3 translate-middle-y" 
                                           type="radio" name="cartao_selecionado" id="c_novo" value="novo" checked 
                                           onclick="togglePagamento()" style="z-index: 5;">
                                    <label class="w-100 p-3 ps-5 rounded-3 border option-card bg-light text-dark fw-bold" for="c_novo">
                                        <i class="bi bi-plus-circle me-2"></i> Usar um novo cartão
                                    </label>
                                </div>
                                <input type="hidden" name="pagamento" value="Cartão de Crédito">

                                <div class="position-relative mb-2">
                                    <input class="form-check-input position-absolute top-50 start-0 ms-3 translate-middle-y" 
                                           type="radio" name="cartao_selecionado" id="pix" value="pix" 
                                           onclick="togglePagamento()" style="z-index: 5;">
                                    <label class="w-100 p-3 ps-5 rounded-3 border option-card bg-white fw-bold" for="pix">
                                        <i class="bi bi-qr-code me-2"></i> Pagar com Pix
                                    </label>
                                </div>
                            </div>

                            <div id="area-novo-cartao">
                                <div class="p-4 bg-light rounded-4 border mb-3 position-relative">
                                    <span class="position-absolute top-0 start-50 translate-middle badge bg-secondary border border-light">Dados do Cartão</span>
                                    <div class="row gy-3 mt-1">
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-muted">Nome no Cartão</label>
                                            <input type="text" class="form-control border-0" name="nome_cartao" placeholder="COMO ESTA NO CARTAO" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-muted">Número</label>
                                            <input type="text" class="form-control border-0" id="numeroCartao" name="numero" maxlength="19" placeholder="0000 0000 0000 0000" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Validade</label>
                                            <input type="text" class="form-control border-0" id="validadeCartao" name="validade" maxlength="5" placeholder="MM/AA" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">CVV</label>
                                            <input type="text" class="form-control border-0" name="cvv" maxlength="4" placeholder="123" required>
                                        </div>
                                        
                                        <div class="col-12 pt-2 border-top mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="salvar_cartao" id="saveCard" value="1">
                                                <label class="form-check-label small text-dark" for="saveCard">
                                                    Salvar este cartão para compras futuras com o apelido:
                                                </label>
                                                <input type="text" name="apelido" class="form-control form-control-sm mt-1 d-inline-block w-auto ms-2 border-0" placeholder="Ex: Meu Nubank">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="area-pix" style="display: none;" class="alert alert-success text-center mt-3 border-success">
                                <i class="bi bi-qr-code fs-1 d-block mb-2"></i>
                                <p class="m-0 fw-bold">O QR Code será gerado após confirmar.</p>
                            </div>

                            <hr class="my-4">

                            <button class="btn-invisible-submit" type="submit">
                                <div class="pay-btn-group">
                                    <div class="pay-left-side">
                                        <div class="pay-card-anim">
                                            <div class="pay-card-line"></div>
                                            <div class="pay-buttons"></div>
                                        </div>
                                        <div class="pay-post">
                                            <div class="pay-post-line"></div>
                                            <div class="pay-screen">
                                                <div class="pay-dollar">$</div>
                                            </div>
                                            <div class="pay-numbers"></div>
                                            <div class="pay-numbers-line2"></div>
                                        </div>
                                    </div>
                                    <div class="pay-right-side">
                                        <div class="pay-new">Confirmar Compra</div>
                                        <svg viewBox="0 0 451.846 451.847" height="512" width="512" xmlns="http://www.w3.org/2000/svg" class="pay-arrow"><path fill="#cfcfcf" data-old_color="#000000" class="active-path" data-original="#000000" d="M345.441 248.292L151.154 442.573c-12.359 12.365-32.397 12.365-44.75 0-12.354-12.354-12.354-32.391 0-44.744L278.318 225.92 106.409 54.017c-12.354-12.359-12.354-32.394 0-44.748 12.354-12.359 32.391-12.359 44.75 0l194.287 194.284c6.177 6.18 9.262 14.271 9.262 22.366 0 8.099-3.091 16.196-9.267 22.373z"></path></svg>
                                    </div>
                                </div>
                            </button>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5 order-md-2">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-3 bg-white ">
                    <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-ticket-perforated me-2"></i>Cupom de Desconto</h6>
                    <?php if($cupomAtivo): ?>
                        <div class="alert alert-success d-flex justify-content-between align-items-center m-0 p-2 small">
                            <span><strong><?php echo $cupomAtivo['codigo']; ?></strong> aplicado!</span>
                            <a href="../controle/aplicar-cupom.php?remover=1&origem=checkout" class="text-success"><i class="bi bi-x-circle-fill"></i></a>
                        </div>
                    <?php else: ?>
                        <form action="../controle/aplicar-cupom.php" method="POST" class="input-group">
                            <input type="hidden" name="origem" value="checkout">
                            <input type="text" name="codigo_cupom" class="form-control bg-light border-end-0" placeholder="Código">
                            <button class="btn btn-outline-success border-start-0 fw-bold">Aplicar</button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-4">Resumo da Compra</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach ($_SESSION['carrinho'] as $chave => $qtd): 
                            $partes = explode('_', $chave);
                            $prod = $dao->buscarPorId($partes[0]);
                            if(!$prod) continue;
                        ?>
                        <li class="list-group-item d-flex justify-content-between lh-sm px-0">
                            <div>
                                <h6 class="my-0 small fw-bold text-dark"><?php echo $prod->getNome(); ?></h6>
                                <small class="text-muted">x<?php echo $qtd; ?></small>
                            </div>
                            <span class="text-muted small">R$ <?php echo number_format($prod->getPreco()*$qtd, 2, ',', '.'); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong></div>
                    <div class="d-flex justify-content-between mb-2 text-success"><span>Frete</span><strong>Grátis</strong></div>
                    <?php if($desconto > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-danger"><span>Desconto</span><strong>- R$ <?php echo number_format($desconto, 2, ',', '.'); ?></strong></div>
                    <?php endif; ?>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold">Total</span>
                        <strong class="text-success fs-4">R$ <?php echo number_format($totalFinal, 2, ',', '.'); ?></strong>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include '../includes/rodape.php'; ?>

    <script>
        // Máscaras
        document.getElementById('numeroCartao').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
            e.target.value = value;
        });
        document.getElementById('validadeCartao').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) value = value.substring(0, 2) + '/' + value.substring(2, 4);
            e.target.value = value;
        });
        
        // Toggle inicial
        togglePagamento();
    </script>
</body>
</html>