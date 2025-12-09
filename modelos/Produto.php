<?php
class Produto {
    private $id;
    private $nome;
    private $imagem;
    private $categoria;
    private $preco;
    private $estoque;
    private $desconto; // Novo campo

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getImagem() { return $this->imagem; }
    public function setImagem($imagem) { $this->imagem = $imagem; }

    public function getCategoria() { return $this->categoria; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }
    
    public function getPreco() { return $this->preco; }
    public function setPreco($preco) { $this->preco = $preco; }

    public function getEstoque() { return $this->estoque; }
    public function setEstoque($estoque) { $this->estoque = $estoque; }

    // --- NOVOS MÉTODOS QUE FALTAVAM ---
    public function getDesconto() { return $this->desconto; }
    public function setDesconto($desconto) { $this->desconto = $desconto; }
}
?>