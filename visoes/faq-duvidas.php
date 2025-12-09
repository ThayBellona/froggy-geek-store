<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Froggy Geek - Dúvidas & FAQ</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .faq-hero {
            background: linear-gradient(135deg, #2e6417 0%, #43a047 100%);
            padding: 80px 0;
            color: white;
            border-radius: 0 0 50px 50px;
            text-align: center;
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(46, 100, 23, 0.2);
        }
        .faq-box {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
        }
        .accordion-item { border: none; margin-bottom: 15px; }
        .accordion-button {
            background-color: #f8f9fa;
            border-radius: 15px !important;
            color: #1f2937;
            font-weight: 700;
            padding: 20px;
            box-shadow: none;
        }
        .accordion-button:not(.collapsed) {
            background-color: #e8f5e9; color: #2e6417;
            box-shadow: 0 5px 15px rgba(46, 100, 23, 0.1);
        }
        .accordion-body {
            background: white; padding: 20px 25px; font-size: 0.95rem; color: #555; line-height: 1.6;
        }
        .contact-box {
            background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 20px; padding: 40px; text-align: center;
        }
        .legal-text {
            font-size: 0.85rem;
            color: #888;
            font-style: italic;
            margin-top: 10px;
            display: block;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = ".."; include '../includes/menu.php'; ?>

    <div class="faq-hero">
        <div class="container">
            <h1 class="fw-bold display-5">Central de Ajuda</h1>
            <p class="mt-2 fs-5 opacity-75">Transparência total com você ✨</p>
        </div>
    </div>

    <div class="container mb-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="faq-box mb-5">
                    <h3 class="fw-bold mb-4 text-dark"><i class="bi bi-shield-check text-success me-2"></i>Políticas e Direitos</h3>

                    <div class="accordion" id="faqLista">

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqReembolso">
                                    💸 Trocas, Devoluções e Reembolso
                                </button>
                            </h2>
                            <div id="faqReembolso" class="accordion-collapse collapse show" data-bs-parent="#faqLista">
                                <div class="accordion-body">
                                    <p>Seguimos rigorosamente o <strong>Código de Defesa do Consumidor (CDC)</strong> para garantir seus direitos:</p>
                                    <ul class="list-unstyled">
                                        <li class="mb-3">
                                            📅 <strong>Direito de Arrependimento (7 Dias):</strong><br>
                                            Conforme o Art. 49 do CDC, você tem até <strong>7 dias corridos</strong> após o recebimento (ou retirada) do produto para desistir da compra, sem precisar justificar. O produto deve estar sem indícios de uso e com a etiqueta.
                                        </li>
                                        <li class="mb-3">
                                            ⚠️ <strong>Troca por Defeito (30 Dias):</strong><br>
                                            Para vícios aparentes ou de fácil constatação em produtos não duráveis (roupas), o prazo para reclamação é de <strong>30 dias</strong> (Art. 26, I do CDC).
                                        </li>
                                        <li class="mb-3">
                                            🔄 <strong>Como Solicitar:</strong><br>
                                            Acesse seu <a href="perfil.php" class="fw-bold text-success">Perfil > Aba Suporte</a> e abra um chamado selecionando "Reembolso/Troca". Selecione o pedido correspondente para agilizar o processo.
                                        </li>
                                        <li>
                                            💰 <strong>Reembolso:</strong><br>
                                            O valor será devolvido integralmente na mesma forma de pagamento utilizada (estorno no cartão ou Pix) em até 5 dias úteis após a devolução e conferência do produto.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqMaterial">
                                    🧵 Especificações e Qualidade do Produto
                                </button>
                            </h2>
                            <div id="faqMaterial" class="accordion-collapse collapse" data-bs-parent="#faqLista">
                                <div class="accordion-body">
                                    Prezamos pela transparência na composição dos nossos produtos:
                                    <ul class="mt-2 mb-0">
                                        <li><strong>Tecido Premium:</strong> 100% Algodão Fio 30.1 Penteado (Malha Menegotti). Oferece toque macio, alta durabilidade e não forma "bolinhas" (peeling).</li>
                                        <li><strong>Estamparia:</strong> Silk Screen HD (Serigrafia Profissional). A tinta funde com as fibras do tecido, garantindo que a estampa não rache, não desbote e dure por anos.</li>
                                        <li><strong>Acabamento:</strong> Costura reforçada de ombro a ombro e gola em ribana com elastano para maior conforto e resistência.</li>
                                    </ul>
                                    <span class="legal-text">*Todas as informações de composição estão na etiqueta do produto, conforme regulamentação do INMETRO.</span>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqEntrega">
                                    📦 Prazos e Retirada
                                </button>
                            </h2>
                            <div id="faqEntrega" class="accordion-collapse collapse" data-bs-parent="#faqLista">
                                <div class="accordion-body">
                                    Trabalhamos com a modalidade <strong>Retirada na Loja</strong> para garantir agilidade e isenção de frete.
                                    <br><br>
                                    <strong>Prazo de Disponibilidade:</strong> Após a confirmação do pagamento, seu pedido estará separado e pronto para retirada em até <strong>2 horas</strong> (dentro do horário comercial). Você pode acompanhar o status ("Em Separação" -> "Aprovado") pelo seu painel.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqPagamento">
                                    💳 Formas de Pagamento Aceitas
                                </button>
                            </h2>
                            <div id="faqPagamento" class="accordion-collapse collapse" data-bs-parent="#faqLista">
                                <div class="accordion-body">
                                    Para sua comodidade e segurança, aceitamos:
                                    <ul>
                                        <li><strong>PIX:</strong> Aprovação imediata e automática.</li>
                                        <li><strong>Cartão de Crédito:</strong> Visa, Mastercard, Elo, Hipercard e Amex. Parcelamento disponível.</li>
                                    </ul>
                                    Seus dados de cartão podem ser salvos de forma segura no seu perfil para compras futuras com apenas um clique.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqContato">
                                    📞 Canais de Atendimento
                                </button>
                            </h2>
                            <div id="faqContato" class="accordion-collapse collapse" data-bs-parent="#faqLista">
                                <div class="accordion-body">
                                    Caso tenha outras dúvidas ou problemas, nosso canal oficial de suporte é o e-mail: <br>
                                    <a href="mailto:contato.froggygeek@outlook.com" class="fw-bold text-success">contato.froggygeek@outlook.com</a>.
                                    <br>
                                    O tempo médio de resposta é de até 24 horas úteis.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="contact-box">
                    <h4 class="fw-bold text-dark mb-2">Ainda precisa de ajuda?</h4>
                    <p class="text-muted mb-4">Nossa equipe está pronta para resolver seu problema.</p>
                    <a href="mailto:contato.froggygeek@outlook.com" class="btn btn-frog px-4 py-2 shadow-sm">
                        <i class="bi bi-envelope me-2"></i> Fale Conosco
                    </a>
                </div>

            </div>
        </div>
    </div>

    <?php include '../includes/rodape.php'; ?>

</body>
</html>