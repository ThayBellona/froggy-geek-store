<?php
session_start();
require_once '../../daos/UsuarioDAO.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { header("Location: ../../index.php"); exit; }

$usuarioDAO = new UsuarioDAO();
$listaUsuarios = $usuarioDAO->buscarTodos();
?>

<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
 <link rel="icon" href="../../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">
    <title>Gerenciar Usuários - Admin</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="admin-page d-flex flex-column min-vh-100">

    <?php $caminho = '../..'; include '../../includes/menu.php'; ?>

    <div class="container mt-4 mb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="painel.php" class="btn btn-outline-dark rounded-pill px-3 mb-2 fw-bold bg-white shadow-sm"><i class="bi bi-arrow-left"></i> Central</a>
                <h2 class="fw-bold text-dark">Gestão de Usuários</h2>
            </div>
            <div class="bg-white px-4 py-2 rounded-pill shadow-sm border border-primary">
                <span class="fw-bold text-primary fs-5"><?php echo count($listaUsuarios); ?></span> cadastros
            </div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg']=='senha_resetada'): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-key-fill me-2"></i> Senha resetada para <strong>"1234"</strong> com sucesso!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif($_GET['msg']=='promovido'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-shield-check me-2"></i> Usuário promovido a <strong>ADMIN</strong>!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif($_GET['msg']=='rebaixado'): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-person me-2"></i> Usuário agora é <strong>Cliente</strong>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif($_GET['msg']=='erro_proprio'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> Você não pode alterar sua própria conta!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="card-admin">
            <div class="card-header header-gradient-blue  py-3">
                <h5 class=" m-3 fw-bold text-white fw-bold"><i class="bi bi-people-fill me-2 "></i> Lista de Clientes e Admins</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-colored align-middle m-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Usuário</th>
                                <th>Email</th>
                                <th>Permissão</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($listaUsuarios as $u): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#<?php echo $u->getId(); ?></td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php 
                                            $foto = $u->getFotoPerfil();
                                            // Verifica se a foto existe antes de exibir
                                            if($foto && file_exists("../../".$foto)): 
                                        ?>
                                            <img src="../../<?php echo $foto; ?>" width="40" height="40" class="rounded-circle me-3 border bg-white object-fit-cover">
                                        <?php else: ?>
                                            <div class="bg-light rounded-circle p-2 me-3 border d-flex align-items-center justify-content-center text-secondary fw-bold" style="width:40px; height:40px;">
                                                <?php echo strtoupper(substr($u->getNome(), 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?php echo $u->getNome(); ?></span>
                                            <small class="text-muted"><?php echo $u->getGenero(); ?></small>
                                        </div>
                                    </div>
                                </td>
                                
                                <td><?php echo $u->getEmail(); ?></td>
                                
                                <td>
                                        <?php if ($u->getIsAdmin()): ?>
                                            <span class="badge bg-danger rounded-pill px-3">ADMIN</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark border rounded-pill px-3">Cliente</span>
                                        <?php endif; ?>
                                    </td>


                                <td class="text-end pe-4">
                                    <?php if($u->getId() != $_SESSION['id_usuario']): ?>
                                        
                                        <a href="../../controle/gerenciar-usuario-acoes.php?acao=resetar&id=<?php echo $u->getId(); ?>" 
                                           class="btn btn-sm btn-warning text-dark me-1" 
                                           title="Resetar senha para 1234"
                                           onclick="return confirm('Resetar a senha deste usuário para 1234?')">
                                            <i class="bi bi-key-fill"></i>
                                        </a>

                                        <?php if($u->getIsAdmin()): ?>
                                            <a href="../../controle/gerenciar-usuario-acoes.php?acao=rebaixar&id=<?php echo $u->getId(); ?>" 
                                               class="btn btn-sm btn-secondary me-1" 
                                               title="Tornar Cliente"
                                               onclick="return confirm('Remover permissão de ADMIN deste usuário?')">
                                                <i class="bi bi-person-down"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="../../controle/gerenciar-usuario-acoes.php?acao=promover&id=<?php echo $u->getId(); ?>" 
                                               class="btn btn-sm btn-info text-white me-1" 
                                               title="Tornar Admin"
                                               onclick="return confirm('Tornar este usuário um ADMIN?')">
                                                <i class="bi bi-shield-plus"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="../../controle/gerenciar-usuario-acoes.php?acao=excluir&id=<?php echo $u->getId(); ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-circle border-0" 
                                           title="Excluir Usuário"
                                           onclick="return confirm('ATENÇÃO: Isso apagará o histórico de compras e dados deste usuário. Continuar?')">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </a>

                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border">Você</span>
                                    <?php endif; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>