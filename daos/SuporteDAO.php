<?php
require_once __DIR__ . '/../conexao/Conexao.php';

class SuporteDAO {
    
    public function criar($idUser, $tipo, $msg, $idPedido = null) {
        try {
            $sql = "INSERT INTO suporte (id_usuario, tipo_solicitacao, mensagem, id_pedido) VALUES (?, ?, ?, ?)";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            // Se idPedido vier vazio ou zero, salva como NULL
            $idPedido = empty($idPedido) ? null : $idPedido;
            return $stmt->execute([$idUser, $tipo, $msg, $idPedido]);
        } catch (Exception $e) { return false; }
    }

    public function buscarPorUsuario($idUser) {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare("SELECT * FROM suporte WHERE id_usuario = ? ORDER BY id DESC");
        $stmt->execute([$idUser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarTodosComUsuario() {
        //  busca o ID do pedido para mostrar ao admin
        $sql = "SELECT s.*, u.nome, u.email, s.id_pedido 
                FROM suporte s 
                JOIN usuarios u ON s.id_usuario = u.id 
                ORDER BY FIELD(s.status, 'Aberto', 'Em Análise', 'Concluído'), s.data_abertura DESC";
        return Conexao::getConexao()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarStatus($id, $status) {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare("UPDATE suporte SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
?>