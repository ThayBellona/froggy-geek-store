<?php
require_once __DIR__ . '/../conexao/Conexao.php';

class DashboardDAO {
    
    // 1. Vendas por Mês (Gráfico Principal: Valor + Quantidade)
    public function getVendasPorMes() {
        try {
            $sql = "SELECT MONTH(data_compra) as mes_num, 
                        SUM(valor_total) as total_valor,
                        COUNT(id) as total_qtd
                    FROM pedidos 
                    WHERE status != 'Cancelado' 
                    GROUP BY YEAR(data_compra), MONTH(data_compra) 
                    ORDER BY YEAR(data_compra) ASC, MONTH(data_compra) ASC 
                    LIMIT 6";
            $conn = Conexao::getConexao();
            $stmt = $conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    // 2. Estatísticas de Cupons (KPI do topo + Mais usado)
    public function getStatsCupons() {
        try {
            $conn = Conexao::getConexao();
            $ativos = $conn->query("SELECT COUNT(*) FROM cupons WHERE ativo = 1")->fetchColumn();
            
            // Cupom mais usado
            $sqlCampeao = "SELECT c.codigo, COUNT(p.id) as uso 
                           FROM pedidos p 
                           JOIN cupons c ON p.id_cupom = c.id 
                           GROUP BY c.id 
                           ORDER BY uso DESC LIMIT 1";
            $stmt = $conn->query($sqlCampeao);
            $campeao = $stmt->fetch(PDO::FETCH_ASSOC);

            return ['ativos' => $ativos, 'campeao' => $campeao];
        } catch (Exception $e) { return ['ativos' => 0, 'campeao' => null]; }
    }

    // 3. Vendas por Gênero (Gráfico de Rosca)
    public function getVendasPorGenero() {
        try {
            $sql = "SELECT u.genero, COUNT(p.id) as total
                    FROM pedidos p
                    JOIN usuarios u ON p.id_usuario = u.id
                    WHERE p.status != 'Cancelado'
                    GROUP BY u.genero";
            $conn = Conexao::getConexao();
            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    // 4. Ranking de Cupons (Gráfico de Barras)
    public function getRankingCupons() {
        try {
            $sql = "SELECT c.codigo, COUNT(p.id) as uso
                    FROM pedidos p
                    JOIN cupons c ON p.id_cupom = c.id
                    GROUP BY c.id 
                    ORDER BY uso DESC LIMIT 5";
            $conn = Conexao::getConexao();
            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    // 5. Fidelidade (Gráfico: Quantas vezes os clientes compram)
    public function getFidelidadeClientes() {
        try {
            $sql = "SELECT compras_feitas, COUNT(*) as qtd_clientes
                    FROM (
                        SELECT COUNT(id) as compras_feitas 
                        FROM pedidos 
                        WHERE status != 'Cancelado'
                        GROUP BY id_usuario
                    ) as sub
                    GROUP BY compras_feitas 
                    ORDER BY compras_feitas ASC";
            $conn = Conexao::getConexao();
            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    // 6. Top Produtos Mais Vendidos (Lista)
    public function getTopProdutosVendidos() {
        try {
            $sql = "SELECT p.nome, p.imagem, SUM(ip.quantidade) as total
                    FROM itens_pedido ip
                    JOIN pedidos ped ON ip.id_pedido = ped.id
                    JOIN produtos p ON ip.id_produto = p.id
                    WHERE ped.status != 'Cancelado'
                    GROUP BY p.id 
                    ORDER BY total DESC LIMIT 5";
            $conn = Conexao::getConexao();
            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    // 7. Top Produtos Avaliados (Lista)
    public function getTopProdutosAvaliados() {
        try {
            $sql = "SELECT p.nome, p.imagem, AVG(a.nota) as media, COUNT(a.id) as qtd
                    FROM avaliacoes a 
                    JOIN produtos p ON a.id_produto = p.id
                    GROUP BY p.id 
                    ORDER BY media DESC, qtd DESC LIMIT 5";
            $conn = Conexao::getConexao();
            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    // 8. Idade Média por Produto (Lista)
    public function getIdadeMediaPorProduto() {
        try {
            $sql = "SELECT p.nome, p.imagem, 
                    ROUND(AVG(TIMESTAMPDIFF(YEAR, u.data_nascimento, CURDATE()))) as idade_media
                    FROM itens_pedido ip
                    JOIN pedidos ped ON ip.id_pedido = ped.id
                    JOIN usuarios u ON ped.id_usuario = u.id
                    JOIN produtos p ON ip.id_produto = p.id
                    WHERE ped.status != 'Cancelado' AND u.data_nascimento IS NOT NULL
                    GROUP BY p.id 
                    HAVING idade_media > 0
                    ORDER BY idade_media DESC LIMIT 5";
            $conn = Conexao::getConexao();
            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    // 9. Resumo Geral (KPIs)
    public function getResumo() {
        try {
            $conn = Conexao::getConexao();
            $vendas = $conn->query("SELECT SUM(valor_total) FROM pedidos WHERE status != 'Cancelado'")->fetchColumn();
            $prods = $conn->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
            $users = $conn->query("SELECT COUNT(*) FROM usuarios WHERE is_admin = 0")->fetchColumn();
            
            return [
                'vendas' => $vendas ?: 0,
                'produtos' => $prods,
                'clientes' => $users
            ];
        } catch (Exception $e) {
            return ['vendas' => 0, 'produtos' => 0, 'clientes' => 0];
        }
    }

    // 10. Top Clientes (Lista)
    public function getTopClientes() {
        try {
            $sql = "SELECT u.nome, COUNT(p.id) as qtd_pedidos, SUM(p.valor_total) as total_gasto
                    FROM pedidos p 
                    JOIN usuarios u ON p.id_usuario = u.id
                    WHERE p.status != 'Cancelado' 
                    GROUP BY u.id
                    ORDER BY total_gasto DESC LIMIT 5";
            $conn = Conexao::getConexao();
            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }
}
?>