<?php
$qtd = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;
?>

<nav class="navbar navbar-expand-lg navbar-top sticky-top">
    <div class="container">
        
        <a class="navbar-brand d-flex align-items-center gap-2 " href="<?php echo $caminho; ?>/index.php">
            <img src="<?php echo $caminho; ?>/img-modelos/logo-img/sapo-geek-icon.png" width="45" height="45">
            <span>Froggy<span style="color: var(--frog-main);">Geek</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuMobile">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuMobile">
            
            <form action="<?php echo $caminho; ?>/visoes/busca.php" method="GET" class="d-flex mx-auto my-3 my-lg-0 search-box">
                <div class="input-group">
                    <input type="text" name="q" class="form-control form-search" placeholder="O que você procura hoje?" required>
                    <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="d-flex align-items-center justify-content-center gap-3 mt-3 mt-lg-0">
                
                <a href="<?php echo $caminho; ?>/sobre.php" class="nav-icon-item">
                    <i class="bi bi-info-circle"></i>
                    <span>Sobre</span>
                </a>

                <a href="<?php echo $caminho; ?>/visoes/faq-duvidas.php" class="nav-icon-item">
                    <i class="bi bi-question-circle"></i>
                    <span>Dúvidas</span>
                </a>

                <?php if (!isset($_SESSION['id_usuario'])): ?>
                    <a href="<?php echo $caminho; ?>/visoes/login.php" class="nav-icon-item">
                        <i class="bi bi-person"></i>
                        <span>Entrar</span>
                    </a>
                <?php else: ?>
                    <div class="dropdown">
                        <a href="<?php echo $caminho; ?>/visoes/perfil.php" class="nav-icon-item dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="position-relative">
                                <i class="bi bi-person-check-fill text-success"></i>
                            </div>
                            <span>Minha Conta</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li class="px-3 py-2 text-muted small">Olá, <strong><?php echo explode(' ', $_SESSION['nome_usuario'])[0]; ?></strong></li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                <li><a class="dropdown-item text-success fw-bold" href="<?php echo $caminho; ?>/visoes/admin/painel.php"><i class="bi bi-speedometer2 me-2"></i> Painel Admin</a></li>
                                <li><a class="dropdown-item" href="<?php echo $caminho; ?>/visoes/admin/form-adicionar.php"><i class="bi bi-box-seam me-2"></i> Produtos</a></li>
                                <li><a class="dropdown-item" href="<?php echo $caminho; ?>/visoes/admin/gerenciar-vendas.php"><i class="bi bi-cart-check me-2"></i> Vendas</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item" href="<?php echo $caminho; ?>/visoes/perfil.php"><i class="bi bi-person-circle me-2"></i> Meu Perfil</a></li>
                            
                            <?php if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1): ?>
                                <li><a class="dropdown-item" href="<?php echo $caminho; ?>/visoes/perfil.php#pedidos"><i class="bi bi-box-seam me-2"></i> Meus Pedidos</a></li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo $caminho; ?>/controle/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Sair</a></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <a href="<?php echo $caminho; ?>/visoes/carrinho.php" class="nav-icon-item position-relative">
                    <i class="bi bi-cart3"></i>
                    <span>Carrinho</span>
                    <?php if($qtd > 0): ?>
                        <span class="position-absolute badge rounded-pill bg-danger badge-cart"><?php echo $qtd; ?></span>
                    <?php endif; ?>
                </a>

            </div>
        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-bottom d-none d-lg-block "> 
    <div class="container justify-content-center">
        <ul class="navbar-nav gap-4">
            <li class="nav-item"><a class="nav-link nav-link-bottom" href="<?php echo $caminho; ?>/visoes/categoria.php?tipo=Todas"><i class="bi bi-grid me-1"></i> Todos os Produtos</a></li>
            <li class="nav-item"><a class="nav-link nav-link-bottom" href="<?php echo $caminho; ?>/visoes/categoria.php?tipo=Anime">🎌 Anime</a></li>
            <li class="nav-item"><a class="nav-link nav-link-bottom" href="<?php echo $caminho; ?>/visoes/categoria.php?tipo=90s Geek">🕹️ 90's Geek</a></li>
            <li class="nav-item"><a class="nav-link nav-link-bottom" href="<?php echo $caminho; ?>/visoes/categoria.php?tipo=Games">🎮 Games</a></li>
            <li class="nav-item"><a class="nav-link nav-link-bottom" href="<?php echo $caminho; ?>/visoes/categoria.php?tipo=Fary Core">🍄 Fary Core</a></li>
        </ul>
    </div>
</nav>