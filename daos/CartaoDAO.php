<?php
require_once __DIR__ . '/../conexao/Conexao.php';

class CartaoDAO {
    public function salvar($idUsuario, $apelido, $numero, $nome, $validade) {
        try {
            $final = substr(str_replace(' ', '', $numero), -4);
            $sql = "INSERT INTO cartoes (id_usuario, apelido_cartao, numero_final, nome_titular, validade) VALUES (?, ?, ?, ?, ?)";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$idUsuario, $apelido, $final, $nome, $validade]);
        } catch (PDOException $e) { return false; }
    }
    
    public function buscarPorUsuario($idUsuario) {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare("SELECT * FROM cartoes WHERE id_usuario = ? ORDER BY id DESC");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($idCartao, $idUsuario) {
        $conn = Conexao::getConexao();
        // Validamos o ID do usuário para ninguém apagar o cartão de outra pessoa
        $stmt = $conn->prepare("DELETE FROM cartoes WHERE id = ? AND id_usuario = ?");
        return $stmt->execute([$idCartao, $idUsuario]);
    }
}
?>