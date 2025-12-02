<?php
require_once __DIR__ . '/../conexao/Conexao.php';
require_once __DIR__ . '/../modelos/Usuario.php';

class UsuarioDAO {

    public function cadastrar(Usuario $usuario) {
        try {
            $sql = "INSERT INTO usuarios (nome, email, senha, genero, data_nascimento, is_admin) 
                    VALUES (:nome, :email, :senha, :gen, :nasc, 0)";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':email', $usuario->getEmail());
            
            // Hash é gerado no Controller antes de vir pra cá, ou pode gerar aqui se preferir
            // No seu caso, estamos gerando no Controller, então aqui só salva
            $stmt->bindValue(':senha', $usuario->getSenha());
            
            $stmt->bindValue(':gen', $usuario->getGenero());
            $stmt->bindValue(':nasc', $usuario->getDataNascimento());
            
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function atualizar($id, $nome, $genero, $dataNascimento, $foto, $senha = null) {
        $conn = Conexao::getConexao();
        
        if ($senha) {
            // Se veio senha nova, ela já deve vir com hash do controller ou aplicar aqui
            // Vamos assumir que o controller já mandou o hash ou aplicar aqui se for texto puro
            // Para segurança, vamos garantir que seja hash se não parecer um
            if (substr($senha, 0, 4) !== '$2y$') {
                 $senha = password_hash($senha, PASSWORD_DEFAULT);
            }

            $sql = "UPDATE usuarios SET nome=?, genero=?, data_nascimento=?, foto_perfil=?, senha=? WHERE id=?";
            return $conn->prepare($sql)->execute([$nome, $genero, $dataNascimento, $foto, $senha, $id]);
        } else {
            $sql = "UPDATE usuarios SET nome=?, genero=?, data_nascimento=?, foto_perfil=? WHERE id=?";
            return $conn->prepare($sql)->execute([$nome, $genero, $dataNascimento, $foto, $id]);
        }
    }

    public function buscarPorId($id) {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject('Usuario');
    }

    // --- LOGIN CORRIGIDO (IMPORTANTE) ---
    public function login($email, $senha) {
        try {
            // 1. Busca o usuário pelo email
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // 2. Se achou o usuário, verifica a senha usando password_verify
            if ($resultado && password_verify($senha, $resultado['senha'])) {
                $usuario = new Usuario();
                $usuario->setId($resultado['id']);
                $usuario->setNome($resultado['nome']);
                $usuario->setEmail($resultado['email']);
                $usuario->setIsAdmin($resultado['is_admin']);
                $usuario->setFotoPerfil($resultado['foto_perfil']);
                return $usuario;
            } else { 
                return null; 
            }
        } catch (PDOException $e) { return null; }
    }
    
    public function buscarTodos() {
        try {
            $conn = Conexao::getConexao();
            $sql = "SELECT * FROM usuarios ORDER BY id DESC";
            $stmt = $conn->query($sql);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $lista = [];
            foreach($resultados as $row) {
                $u = new Usuario();
                $u->setId($row['id']);
                $u->setNome($row['nome']);
                $u->setEmail($row['email']);
                $u->setIsAdmin($row['is_admin']); // Mapeia correto
                $u->setGenero($row['genero']);
                $u->setDataNascimento($row['data_nascimento']);
                $u->setFotoPerfil($row['foto_perfil']);
                $lista[] = $u;
            }
            return $lista;
        } catch (PDOException $e) { return []; }
    }
    
    public function excluir($id) {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function resetarSenha($id, $senhaPadrao) {
        try {
            $sql = "UPDATE usuarios SET senha = :senha WHERE id = :id";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $senhaHash = password_hash($senhaPadrao, PASSWORD_DEFAULT);
            $stmt->bindValue(':senha', $senhaHash); 
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function alterarNivel($id, $novoStatus) {
        try {
            $sql = "UPDATE usuarios SET is_admin = :status WHERE id = :id";
            $conn = Conexao::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':status', $novoStatus);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
}
?>