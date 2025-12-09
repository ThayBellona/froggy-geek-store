<?php
session_start();
require_once '../../daos/ProdutoDAO.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) { header("Location: ../../index.php"); exit; }
if (!isset($_GET['id'])) { header("Location: form-adicionar.php"); exit; }

$id = $_GET['id'];
$dao = new ProdutoDAO();
$produto = $dao->buscarPorId($id);
$estoques = $dao->buscarEstoquesPorTamanho($id); 

if (!$produto) { echo "Produto não encontrado."; exit; }
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Editar Produto - Froggy Geek</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="icon" href="../../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php $caminho = '../..'; include '../../includes/menu.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="card shadow-sm col-md-8 mx-auto border-0 rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h4 class="m-0 text-dark fw-bold"><i class="bi bi-pencil-square text-warning"></i> Editar Produto</h4>
            </div>
            <div class="card-body p-4">
                
                <form action="../../controle/processar-edicao-produto.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $produto->getId(); ?>">
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <img src="../../<?php echo $produto->getImagem(); ?>" class="rounded shadow-sm border p-2 bg-white" width="150" height="150" style="object-fit: contain;">
                            <p class="small text-muted mt-2">Imagem Atual</p>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome do Produto</label>
                                <input type="text" name="nome" class="form-control" value="<?php echo $produto->getNome(); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold text-success">Preço (R$)</label>
                                    <input type="number" name="preco" step="0.01" class="form-control" value="<?php echo $produto->getPreco(); ?>" required>
                                </div>
                                
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold text-danger">Desconto (%)</label>
                                    <input type="number" name="desconto" class="form-control border-danger text-danger fw-bold" 
                                           value="<?php echo $produto->getDesconto(); ?>" max="99">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 bg-light p-3 rounded-3 border">
                        <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-layers"></i> Gerenciar Estoque por Tamanho:</label>
                        <div class="row g-2">
                            <?php foreach(['P','M','G','GG'] as $tam): ?>
                            <div class="col-3">
                                <label class="small text-center w-100 fw-bold"><?php echo $tam; ?></label>
                                <input type="number" name="qtd_<?php echo $tam; ?>" class="form-control text-center" min="0"
                                       value="<?php echo isset($estoques[$tam]) ? $estoques[$tam] : 0; ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Categoria</label>
                        <select name="categoria" class="form-select">
                            <?php 
                                $cats = ["90s Geek", "Anime", "Games", "Fary Core"];
                                foreach($cats as $c) {
                                    $selected = ($produto->getCategoria() == $c) ? 'selected' : '';
                                    echo "<option value='$c' $selected>$c</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Alterar Imagem</label>
                        <input type="file" name="imagem" class="form-control" accept="image/*">
                        <small class="text-muted">Deixe vazio para manter a atual.</small>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="form-adicionar.php" class="btn btn-outline-secondary px-4">Cancelar</a>
                        <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">
                            <i class="bi bi-check-lg"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../includes/rodape.php'; ?>

</body>
</html>