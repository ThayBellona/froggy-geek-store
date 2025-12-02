<?php
require_once __DIR__ . '/../conexao/Conexao.php';

class CupomDAO {
    
    public function buscarTodos() {
        $conn = Conexao::getConexao();
        return $conn->query("SELECT * FROM cupons")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($codigo, $porcentagem) {
        $sql = "INSERT INTO cupons (codigo, desconto_percentual) VALUES (?, ?)";
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare($sql);
        return $stmt->execute([strtoupper($codigo), $porcentagem]);
    }

    public function excluir($id) {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare("DELETE FROM cupons WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function buscarPorCodigo($codigo) {
        try {
            $sql = "SELECT * FROM cupons WHERE codigo = :cod AND ativo = 1";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':cod', strtoupper($codigo));
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e) { return null; }
    }


    // 1. Busca cupons do usuário para o perfil
    public function buscarPorUsuario($idUsuario) {
        $sql = "SELECT cu.*, c.codigo, c.desconto_percentual 
                FROM cupons_usuario cu
                JOIN cupons c ON cu.id_cupom = c.id
                WHERE cu.id_usuario = ? 
                ORDER BY cu.usado ASC, cu.data_ganho DESC"; // Não usados primeiro
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Sorteia e entrega um cupom aleatório (Chance de 30%)
    public function sortearCupomParaUsuario($idUsuario) {
        // Chance de 30% (gere um número de 1 a 100)
        if (rand(1, 100) <= 30) {
            $conn = Conexao::getConexao();
            // Pega um cupom aleatório da loja
            $cupom = $conn->query("SELECT id, codigo FROM cupons WHERE ativo = 1 ORDER BY RAND() LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            
            if ($cupom) {
                // Salva na carteira do usuário
                $sql = "INSERT INTO cupons_usuario (id_usuario, id_cupom) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$idUsuario, $cupom['id']]);
                return $cupom['codigo']; // Retorna o código ganho
            }
        }
        return null; // Não ganhou nada
    }

    // 3. Marca cupom como usado
    public function marcarComoUsado($idUsuario, $codigoCupom) {
        $conn = Conexao::getConexao();
        // Busca o ID do cupom
        $cupom = $this->buscarPorCodigo($codigoCupom);
        if ($cupom) {
            // Marca o mais antigo desse tipo como usado
            $sql = "UPDATE cupons_usuario SET usado = 1 
                    WHERE id_usuario = ? AND id_cupom = ? AND usado = 0 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idUsuario, $cupom['id']]);
        }
    }
}
?>