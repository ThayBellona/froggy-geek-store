<?php
include_once __DIR__ . '/../conexao/Conexao.php';
include_once __DIR__ . '/../modelos/Produto.php';

class ProdutoDAO {
    
    // 1. Busca todos os produtos
    public function buscarTodos() {
        try {
            $sql = "SELECT * FROM produtos";
            $conn = Conexao::getConexao();
            $stmt = $conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Produto');
        } catch (PDOException $e) {
            return [];
        }
    }

    // 2. Busca um produto pelo ID
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM produtos WHERE id = :id";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetchObject('Produto');
        } catch (PDOException $e) {
            return null;
        }
    }

    // 3. Busca produtos por Categoria
    public function buscarPorCategoria($categoria) {
        try {
            $sql = "SELECT * FROM produtos WHERE categoria = :cat";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':cat', $categoria);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Produto');
        } catch (PDOException $e) {
            return [];
        }
    }

    // 4. Busca produtos pelo Nome (Barra de Pesquisa)
    public function buscarPorNome($termo) {
        try {
            $sql = "SELECT * FROM produtos WHERE nome LIKE :termo";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':termo', "%" . $termo . "%");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Produto');
        } catch (PDOException $e) {
            return [];
        }
    }

    // ---------------------------------------------------------
    // FUNÇÕES DE ESTOQUE E TAMANHOS
    // ---------------------------------------------------------

    // 5. Busca lista de tamanhos disponíveis (Para o Cliente)
    public function buscarTamanhos($idProduto) {
        try {
            $sql = "SELECT tamanho, quantidade FROM estoque_tamanhos WHERE id_produto = :id";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $idProduto);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 6. Busca estoque formatado (Para o Admin - Form de Edição)
    public function buscarEstoquesPorTamanho($idProduto) {
        try {
            $sql = "SELECT tamanho, quantidade FROM estoque_tamanhos WHERE id_produto = :id";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $idProduto);
            $stmt->execute();
            
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Cria array padrão zerado
            $estoques = ['P' => 0, 'M' => 0, 'G' => 0, 'GG' => 0];
            foreach($resultados as $reg) {
                $estoques[$reg['tamanho']] = $reg['quantidade'];
            }
            return $estoques;
        } catch (PDOException $e) {
            return ['P' => 0, 'M' => 0, 'G' => 0, 'GG' => 0];
        }
    }

    // 7. Atualiza estoque simples (Legacy/Cancelamentos)
    public function atualizarEstoque($id, $novaQuantidade) {
        try {
            $sql = "UPDATE produtos SET estoque = :qtd WHERE id = :id";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':qtd', $novaQuantidade);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // ---------------------------------------------------------
    // FUNÇÕES DE ESCRITA (COM DESCONTO E TRANSAÇÃO)
    // ---------------------------------------------------------

    // 8. Inserir Produto (Com Tamanhos e Desconto)
    public function inserir(Produto $produto, $arrayTamanhos) {
        try {
            $conn = Conexao::getConexao();
            $conn->beginTransaction();

            // Insere na tabela principal (incluindo desconto)
            $sql = "INSERT INTO produtos (nome, imagem, categoria, preco, estoque, desconto) 
                    VALUES (:nome, :img, :cat, :preco, :total, :desc)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nome', $produto->getNome());
            $stmt->bindValue(':img', $produto->getImagem());
            $stmt->bindValue(':cat', $produto->getCategoria());
            $stmt->bindValue(':preco', $produto->getPreco());
            $stmt->bindValue(':total', array_sum($arrayTamanhos)); 
            $stmt->bindValue(':desc', $produto->getDesconto()); // Novo campo
            $stmt->execute();
            
            $idProduto = $conn->lastInsertId();

            // Insere os tamanhos
            $sqlTam = "INSERT INTO estoque_tamanhos (id_produto, tamanho, quantidade) VALUES (?, ?, ?)";
            $stmtTam = $conn->prepare($sqlTam);

            foreach($arrayTamanhos as $tam => $qtd) {
                $stmtTam->execute([$idProduto, $tam, $qtd]);
            }

            $conn->commit();
            return true;

        } catch (PDOException $e) {
            if(isset($conn)) $conn->rollBack();
            return false;
        }
    }

    // 9. Atualizar Produto (Com Tamanhos e Desconto)
    public function atualizar(Produto $produto, $arrayTamanhos) {
        try {
            $conn = Conexao::getConexao();
            $conn->beginTransaction();
            
            // Atualiza dados principais
            if (empty($produto->getImagem())) {
                $sql = "UPDATE produtos SET nome=:n, categoria=:c, preco=:p, estoque=:e, desconto=:d WHERE id=:id";
            } else {
                $sql = "UPDATE produtos SET nome=:n, imagem=:i, categoria=:c, preco=:p, estoque=:e, desconto=:d WHERE id=:id";
            }
            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':n', $produto->getNome());
            $stmt->bindValue(':c', $produto->getCategoria());
            $stmt->bindValue(':p', $produto->getPreco());
            $stmt->bindValue(':e', array_sum($arrayTamanhos)); 
            $stmt->bindValue(':d', $produto->getDesconto()); // Novo campo
            $stmt->bindValue(':id', $produto->getId());
            
            if (!empty($produto->getImagem())) {
                $stmt->bindValue(':i', $produto->getImagem());
            }
            $stmt->execute();

            // Atualiza Tamanhos (Remove antigos e insere novos)
            $conn->prepare("DELETE FROM estoque_tamanhos WHERE id_produto = ?")->execute([$produto->getId()]);
            
            $sqlTam = "INSERT INTO estoque_tamanhos (id_produto, tamanho, quantidade) VALUES (?, ?, ?)";
            $stmtTam = $conn->prepare($sqlTam);
            foreach($arrayTamanhos as $tam => $qtd) {
                $stmtTam->execute([$produto->getId(), $tam, $qtd]);
            }

            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if(isset($conn)) $conn->rollBack();
            return false;
        }
    }

    // 10. Excluir Produto
    public function excluir($id) {
        try {
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare("DELETE FROM produtos WHERE id = :id");
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
        // Busca 3 produtos da mesma categoria para a barra lateral (menos o atual)
    public function buscarRelacionados($categoria, $idExcluir) {
        try {
            $sql = "SELECT * FROM produtos 
                    WHERE categoria = :cat AND id != :id 
                    ORDER BY RAND() LIMIT 3"; // RAND() pega aleatório
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':cat', $categoria);
            $stmt->bindValue(':id', $idExcluir);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Produto');
        } catch (PDOException $e) {
            return [];
        }
    }
}

    

?>
