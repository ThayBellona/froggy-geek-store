<?php
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $isAdmin;
    private $genero;
    private $data_nascimento; // Campo correto
    private $foto_perfil;     // Campo correto

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getSenha() { return $this->senha; }
    public function setSenha($senha) { $this->senha = $senha; }

    public function getIsAdmin() { return $this->isAdmin; }
    public function setIsAdmin($isAdmin) { $this->isAdmin = $isAdmin; }

    public function getGenero() { return $this->genero; }
    public function setGenero($genero) { $this->genero = $genero; }

    public function getDataNascimento() { return $this->data_nascimento; }
    public function setDataNascimento($data) { $this->data_nascimento = $data; }

    public function getFotoPerfil() { return $this->foto_perfil; }
    public function setFotoPerfil($foto) { $this->foto_perfil = $foto; }
}
?>