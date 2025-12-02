<?php
require_once __DIR__ . '/../conexao/Conexao.php';
require_once __DIR__ . '/../modelos/Usuario.php';

class PedidoDAO {

    // 1. Busca pedidos de UM usuário específico (Para o Perfil)
    public function buscarPorUsuario($idUsuario) {
        try {
            $sql = "SELECT * FROM pedidos WHERE id_usuario = :id ORDER BY id DESC";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $idUsuario);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 2. Busca TODOS os pedidos com o nome do cliente (Para o Admin)
    public function buscarTodosComCliente() {
        try {
            $sql = "SELECT p.*, u.nome as nome_cliente 
                    FROM pedidos p 
                    JOIN usuarios u ON p.id_usuario = u.id 
                    ORDER BY p.id DESC";
            $conn = Conexao::getConexao();
            $stmt = $conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 3. Busca os produtos de um pedido específico
    public function buscarItensDoPedido($idPedido) {
        try {
            $sql = "SELECT ip.*, p.nome, p.imagem 
                    FROM itens_pedido ip 
                    JOIN produtos p ON ip.id_produto = p.id 
                    WHERE ip.id_pedido = :id";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $idPedido);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 4. Admin atualiza o status (Ex: de Pendente para Entregue)
    public function atualizarStatus($idPedido, $novoStatus) {
        try {
            $sql = "UPDATE pedidos SET status = :status WHERE id = :id";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':status', $novoStatus);
            $stmt->bindValue(':id', $idPedido);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>