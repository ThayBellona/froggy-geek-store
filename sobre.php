<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - Froggy Geek</title>

    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="icon" href="./img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --frog-main: #3bbf61;
            --frog-accent: #1ea54a;
            --frog-light: #d8ffe6;
            --frog-gradient: linear-gradient(135deg, #3bbf61, #1ea54a);
        }

        .bg-froggy {
            background: var(--frog-gradient);
        }
        .team-photo {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border: 4px solid var(--frog-main);
        }
        .team-card {
            transition: .3s;
            background: white;
        }
        .team-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .color-block {
            border-radius: 16px;
            padding: 2.2rem;
            background: var(--frog-light);
            border-left: 7px solid var(--frog-main);
        }
        .highlight-title {
            background: var(--frog-light);
            display: inline-block;
            padding: 6px 16px;
            border-radius: 10px;
            font-weight: bold;
            border: 2px solid var(--frog-main);
            color: var(--frog-dark);
        }
    </style>

</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = '.'; include 'includes/menu.php'; ?>

    <section class="py-5 bg-froggy text-white text-center" style="border-radius: 0 0 40px 40px;">
        <div class="container">
            <h1 class="fw-bold mb-3 display-5">Sobre a Froggy Geek</span>
                <img src="./img-modelos/logo-img/sapo-geek-icon.png" alt="Logo Froggy Geek" class="img-fluid  " style="max-width: 120px;"></h1>
            <p class="lead w-75 mx-auto opacity-90">
                Onde estilo, criatividade e paixão geek se encontram para criar algo único.
            </p>
        </div>
    </section>

    <section class="container py-5 my-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4"><span class="highlight-title">Nossa História</span></h2>
                <p class="text-secondary fs-5" style="line-height: 1.8;">
                    A Froggy Geek nasceu com o objetivo de unir moda e cultura pop de um jeito criativo, expressivo e cheio de personalidade.
                    O que começou como um pequeno sonho virou um movimento que celebra o orgulho geek com estilo.
                </p>

                <p class="text-secondary">
                    Cada coleção é pensada para representar universos que amamos — animes, games, nostalgia e muito mais.  
                    Nosso compromisso sempre foi entregar qualidade premium, estampas originais e conforto real.
                </p>

                <p class="fw-bold text-success">
                    Hoje, somos mais que uma loja: somos uma comunidade que veste aquilo que ama.
                </p>
            </div>

            <div class="col-lg-6 text-center">
                <img src="./img-modelos/logo-img/Logo-Bleh.png" alt="Logo Froggy Geek" class="img-fluid p-1 bg-white rounded-circle shadow" style="max-width:100%;">
            </div>
        </div>
    </section>

    <section class="py-5 bg-white border-top border-bottom shadow-sm">
        <div class="container">
            <h2 class="fw-bold text-center mb-5"><span class="highlight-title">Missão, Visão e Valores</span></h2>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="color-block shadow-sm h-100">
                        <h4 class="fw-bold mb-3 text-success text-center"><i class="bi bi-rocket-takeoff me-2"></i>Missão</h4>
                        <p class="text-muted">
                            Criar roupas geeks que conectem pessoas às suas paixões, com qualidade, conforto  
                            e identidade única.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="color-block shadow-sm h-100" style="background: #fff9db; border-left-color:#f1c40f;">
                        <h4 class="fw-bold mb-3 text-warning text-center"><i class="bi bi-eye me-2"></i>Visão</h4>
                        <p class="text-muted">
                            Tornar-se a maior referência nacional em moda geek, inspirando criatividade  
                            e fortalecendo nossa comunidade.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="color-block shadow-sm h-100" style="background: #ffe6f2; border-left-color:#e84393;">
                        <h4 class="fw-bold mb-3 text-danger text-center"><i class="bi bi-heart me-2"></i>Valores</h4>
                        <p class="text-muted">
                            Inclusão, originalidade, qualidade, respeito aos fãs, paixão pela arte  
                            e amor pelo universo geek.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
  
    <section class="container py-5 mb-5">
        <h2 class="fw-bold text-center mb-5"><span class="highlight-title">Nossa Equipe</span></h2>

        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="team-card text-center p-4 rounded-4 shadow-sm border h-100">
                    <img src="./img-modelos/logo-img/FotoThay.jpeg" class="rounded-circle mb-3 team-photo" alt="Foto Thay">
                    
                    <h5 class="fw-bold">Thay Bellona</h5>
                    <p class="badge bg-success bg-opacity-10 text-success">Desenvolvedora & Tech Lead</p>
                    <p class="text-muted small opacity-75">
                        A "maga dos códigos" da Froggy Geek. Responsável por todo o desenvolvimento do site, 
                        pela gestão operacional e por garantir que a tecnologia conecte você aos melhores produtos.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="team-card text-center p-4 rounded-4 shadow-sm border h-100">
                     <img src="./img-modelos/logo-img/AnaFoto.jpeg" class="rounded-circle mb-3 team-photo" style="border-color: #ffc107; object-position: top;" alt="Foto Anna">

                    <h5 class="fw-bold">Anna Júlia</h5>
                    <p class="badge bg-warning bg-opacity-10 text-warning">Fundadora & Designer</p>
                    <p class="text-muted small opacity-75">
                        A mente criativa que deu vida à Froggy Geek. Responsável pela direção de arte, 
                        criação das estampas exclusivas e por definir a identidade visual única da nossa marca.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <?php include 'includes/rodape.php'; ?>

</body>
</html>