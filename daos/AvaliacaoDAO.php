<?php
require_once __DIR__ . '/../conexao/Conexao.php';

class AvaliacaoDAO {
    
    public function inserir($idProduto, $idUsuario, $nota, $comentario) {
        try {
            $sql = "INSERT INTO avaliacoes (id_produto, id_usuario, nota, comentario) VALUES (?, ?, ?, ?)";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$idProduto, $idUsuario, $nota, $comentario]);
        } catch (PDOException $e) { return false; }
    }

    public function buscarPorProduto($idProduto) {
        try {
            $sql = "SELECT a.*, u.nome, u.foto_perfil 
                    FROM avaliacoes a 
                    JOIN usuarios u ON a.id_usuario = u.id 
                    WHERE a.id_produto = ? ORDER BY a.id DESC";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idProduto]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
}
?>