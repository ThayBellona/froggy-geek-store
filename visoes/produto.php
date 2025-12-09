<?php
session_start();
require_once '../daos/ProdutoDAO.php';
require_once '../daos/AvaliacaoDAO.php';

if(!isset($_GET['id'])) { header("Location: ../index.php"); exit; }
$id = $_GET['id'];

$dao = new ProdutoDAO();
$produto = $dao->buscarPorId($id);
$tamanhos = $dao->buscarTamanhos($id);
$relacionados = $dao->buscarRelacionados($produto->getCategoria(), $id);

$avDao = new AvaliacaoDAO();
$avaliacoes = $avDao->buscarPorProduto($id);

if(!$produto){ echo "Produto não encontrado!"; exit; }
$descricaoTexto = method_exists($produto, 'getDescricao') ? $produto->getDescricao() : $produto->descricao;
?>

<!DOCTYPE html>
<html lang="pt-br"> <head>
  <meta charset="UTF-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">
  <title><?php echo $produto->getNome(); ?> - Froggy Geek</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/navbar.css">
<link rel="icon" href="../img-modelos/logo-img/sapo-geek-icon.png" type="image/png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <style>
      .estrelas-input { direction: rtl; display: inline-flex; }
      .estrelas-input input { display: none; }
      .estrelas-input label { font-size: 1.8rem; color: #ddd; cursor: pointer; transition: color 0.2s; margin: 0 2px; }
      .estrelas-input input:checked ~ label, .estrelas-input label:hover, .estrelas-input label:hover ~ label { color: #ffc107; }
      
      .mini-card { display: flex; align-items: center; background: white; border-radius: 12px; padding: 10px; margin-bottom: 15px; border: 1px solid #f0f0f0; transition: 0.3s; text-decoration: none; color: inherit; }
      .mini-card:hover { transform: translateX(5px); border-color: var(--frog-accent); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
      .mini-card img { width: 60px; height: 60px; object-fit: contain; border-radius: 8px; background: #f8f9fa; }
      .sidebar-fixa { position: sticky; top: 110px; z-index: 1; }

      /* Botão Estilo Sapo */
.btn-frog {
    background-color: #2e6417; /* Verde Escuro (Cor da Marca) */
    color: #ffffff;            /* Texto Branco */
    border: 2px solid #2e6417;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 25px;       /* Bordas bem redondas */
    transition: all 0.3s ease; /* Animação suave */
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-block;
    text-align: center;
    cursor: pointer;
}

/* Efeito ao passar o mouse */
.btn-frog:hover {
    background-color: #058a10ff; /* Vira Verde Neon */
    color: #13ff91ff;            /* Texto vira escuro */
    border-color: #64cc37ff;
    transform: translateY(-3px); /* Sobe um pouquinho */
    box-shadow: 0 5px 15px rgba(46, 100, 23, 0.4); /* Sombra verde */
}

/* Efeito ao clicar */
.btn-frog:active {
    transform: translateY(1px);
    box-shadow: none;
}
  </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

  <?php $caminho = '..'; include '../includes/menu.php'; ?>

  <div class="container mt-4 mb-5">
      
      <div class="row g-4">
          
          <div class="col-lg-9">
              
              <div class="card border-0 shadow-sm rounded-4 mb-4">
                  <div class="card-body p-4 p-md-5">
                      <div class="row g-5">
                          <div class="col-md-6 text-center">
                              <div class="p-4 bg-light rounded-4 position-relative">
                                  <img src="../<?php echo $produto->getImagem(); ?>" class="img-fluid" style="max-height: 450px; object-fit: contain; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1));">
                                  <?php if($produto->getDesconto() > 0): ?>
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-danger fs-5 rounded-pill shadow">-<?php echo $produto->getDesconto(); ?>%</span>
                                  <?php endif; ?>
                              </div>
                          </div>
                          
                          <div class="col-md-6">
                              <span class="badge bg-success bg-opacity-10 text-success mb-2 px-3"><?php echo $produto->getCategoria(); ?></span>
                              <h1 class="fw-bold display-6 mb-3"><?php echo $produto->getNome(); ?></h1>
                              
                              <div class="mb-4">
                                  <?php if($produto->getDesconto() > 0): $precoNovo = $produto->getPreco() - ($produto->getPreco() * ($produto->getDesconto() / 100)); ?>
                                      <span class="text-muted text-decoration-line-through fs-5 me-2">R$ <?php echo number_format($produto->getPreco(), 2, ',', '.'); ?></span>
                                      <span class="text-success fw-bold display-5">R$ <?php echo number_format($precoNovo, 2, ',', '.'); ?></span>
                                  <?php else: ?>
                                      <span class="text-success fw-bold display-5">R$ <?php echo number_format($produto->getPreco(), 2, ',', '.'); ?></span>
                                  <?php endif; ?>
                              </div>
                              
                              <div class="mb-4">
                                  <h6 class="fw-bold text-dark">Descrição:</h6>
                                  <p class="text-muted" style="line-height: 1.6;">
                                      <?php echo $descricaoTexto ? nl2br($descricaoTexto) : "Peça exclusiva Froggy Geek. Conforto e estilo para o seu dia a dia."; ?>
                                  </p>
                                  
                                  <div class="bg-light p-3 rounded-3 border mt-3">
                                      <h6 class="fw-bold text-success mb-2 small text-uppercase"><i class="bi bi-stars"></i> Detalhes do Material</h6>
                                      <ul class="list-unstyled small text-secondary mb-0">
                                          <li class="mb-1"><i class="bi bi-check2-circle text-success me-2"></i>100% Algodão 30.1 Penteado</li>
                                          <li class="mb-1"><i class="bi bi-check2-circle text-success me-2"></i>Estampa Silk Screen HD (Não desbota)</li>
                                          <li><i class="bi bi-check2-circle text-success me-2"></i>Costura reforçada ombro a ombro</li>
                                      </ul>
                                  </div>

                                  <div class="mt-3 d-flex gap-4 border-top pt-3">
                                      <div class="d-flex align-items-center">
                                          <i class="bi bi-arrow-repeat fs-3 text-success me-2"></i>
                                          <div class="lh-1">
                                              <span class="d-block fw-bold text-dark small">Troca Grátis</span>
                                              <small class="text-muted" style="font-size: 0.7rem;">Até 7 dias</small>
                                          </div>
                                      </div>
                                      <div class="d-flex align-items-center">
                                          <i class="bi bi-shield-check fs-3 text-success me-2"></i>
                                          <div class="lh-1">
                                              <span class="d-block fw-bold text-dark small">Garantia</span>
                                              <small class="text-muted" style="font-size: 0.7rem;">30 dias p/ defeitos</small>
                                          </div>
                                      </div>
                                  </div>
                                  <a href="faq-duvidas.php" target="_blank" class="link-policy mt-2 d-inline-block">Ver política completa de reembolso</a>
                              </div>

                              <form action="../controle/adicionar-carrinho.php" method="POST">
                                  <input type="hidden" name="id_produto" value="<?php echo $produto->getId(); ?>">
                                  <div class="mb-4">
                                      <label class="fw-bold mb-2 text-secondary small text-uppercase">Tamanhos:</label><br>
                                      <div class="btn-group" role="group">
                                          <?php $temEstoque = false; foreach(['P', 'M', 'G', 'GG'] as $tam): 
                                              $qtd = 0; foreach($tamanhos as $t) { if($t['tamanho'] == $tam) $qtd = $t['quantidade']; }
                                              if($qtd > 0) $temEstoque = true;
                                              $disabled = ($qtd == 0) ? 'disabled' : '';
                                              $outline = ($qtd == 0) ? 'btn-outline-secondary opacity-25' : 'btn-outline-success fw-bold';
                                          ?>
                                              <input type="radio" class="btn-check" name="tamanho" id="<?php echo $tam; ?>" value="<?php echo $tam; ?>" <?php echo $disabled; ?> required>
                                              <label class="btn <?php echo $outline; ?> px-4 py-2" for="<?php echo $tam; ?>"><?php echo $tam; ?></label>
                                          <?php endforeach; ?>
                                      </div>
                                  </div>
                                  <div class="d-grid gap-2">
                                      <?php if($temEstoque): ?>
                                          <button type="submit" class="btn btn-frog btn-lg py-3 shadow-sm"><i class="bi bi-bag-plus me-2"></i> Adicionar ao Carrinho</button>
                                      <?php else: ?>
                                          <button type="button" class="btn btn-secondary btn-lg" disabled>Produto Esgotado</button>
                                      <?php endif; ?>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="card border-0 shadow-sm rounded-4">
                  <div class="card-body p-4 p-md-5">
                     <div class="d-flex align-items-center justify-content-between mb-4">
                         <h3 class="fw-bold m-0 text-dark">Avaliações</h3>
                         <span class="badge bg-light text-dark border"><?php echo count($avaliacoes); ?> opiniões</span>
                     </div>

                     <?php if(isset($_SESSION['id_usuario'])): ?>
                         <div class="bg-light p-4 rounded-3 mb-5">
                             <h6 class="fw-bold mb-3 text-success">Deixe sua avaliação</h6>
                             <form action="../controle/processar-avaliacao.php" method="POST">
                                 <input type="hidden" name="id_produto" value="<?php echo $id; ?>">
                                 <div class="mb-2 d-flex align-items-center">
                                     <div class="estrelas-input">
                                         <input type="radio" id="e5" name="nota" value="5" required><label for="e5">★</label>
                                         <input type="radio" id="e4" name="nota" value="4"><label for="e4">★</label>
                                         <input type="radio" id="e3" name="nota" value="3"><label for="e3">★</label>
                                         <input type="radio" id="e2" name="nota" value="2"><label for="e2">★</label>
                                         <input type="radio" id="e1" name="nota" value="1"><label for="e1">★</label>
                                     </div>
                                 </div>
                                 <textarea name="comentario" class="form-control border-0 shadow-sm mb-3" rows="3" placeholder="O que você achou do produto?" required></textarea>
                                 <div class="text-end"><button class="btn btn-sm btn-success fw-bold px-4 rounded-pill">Publicar</button></div>
                             </form>
                         </div>
                     <?php else: ?>
                         <div class="alert alert-light border text-center mb-5 py-4"><a href="login.php" class="fw-bold text-success text-decoration-none">Entre</a> ou <a href="cadastro.php" class="fw-bold text-success text-decoration-none">Cadastre-se</a> para avaliar.</div>
                     <?php endif; ?>

                     <?php if(count($avaliacoes) > 0): foreach($avaliacoes as $av): 
                         $fotoUser = isset($av['foto_perfil']) && !empty($av['foto_perfil']) ? "../".$av['foto_perfil'] : null;
                     ?>
                         <div class="d-flex gap-3 mb-4 border-bottom pb-4">
                             <?php if($fotoUser && file_exists($fotoUser)): ?>
                                <img src="<?php echo $fotoUser; ?>" class="rounded-circle object-fit-cover" width="50" height="50">
                             <?php else: ?>
                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-success fw-bold flex-shrink-0" style="width: 50px; height: 50px;"><?php echo strtoupper(substr($av['nome'], 0, 1)); ?></div>
                             <?php endif; ?>
                             <div>
                                 <div class="d-flex align-items-center gap-2">
                                     <h6 class="fw-bold m-0 text-dark"><?php echo $av['nome']; ?></h6>
                                     <span class="text-warning small"><?php for($i=0; $i<$av['nota']; $i++) echo '★'; ?></span>
                                 </div>
                                 <small class="text-muted d-block mb-2"><?php echo date('d/m/Y', strtotime($av['data_avaliacao'])); ?></small>
                                 <p class="m-0 text-secondary"><?php echo $av['comentario']; ?></p>
                             </div>
                         </div>
                     <?php endforeach; else: ?><p class="text-muted text-center">Seja o primeiro a avaliar!</p><?php endif; ?>
                  </div>
              </div>
          </div>

          <div class="col-lg-3">
              <div class="sidebar-fixa">
                  <div class="bg-white p-4 rounded-4 shadow-sm border">
                      <h5 class="fw-bold mb-4 ps-2 border-start border-4 border-success">Veja Também</h5>
                      <?php if(count($relacionados) > 0): foreach($relacionados as $rel): ?>
                        <a href="produto.php?id=<?php echo $rel->getId(); ?>" class="mini-card border-0 shadow-sm mb-3">
                            <img src="../<?php echo $rel->getImagem(); ?>">
                            <div class="ms-3 w-100 overflow-hidden">
                                <h6 class="fw-bold m-0 text-dark text-truncate" style="font-size: 0.9rem;"><?php echo $rel->getNome(); ?></h6>
                                <span class="text-success fw-bold small">R$ <?php echo number_format($rel->getPreco(), 2, ',', '.'); ?></span>
                            </div>
                        </a>
                      <?php endforeach; endif; ?>
                      <div class="text-center mt-4"><a href="categoria.php?tipo=<?php echo $produto->getCategoria(); ?>" class="btn btn-outline-success btn-sm rounded-pill w-100">Ver Categoria</a></div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <?php include '../includes/rodape.php'; ?>
</body>
</html>