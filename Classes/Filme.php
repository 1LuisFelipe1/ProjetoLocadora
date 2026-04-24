<?php

class Filme {

    private int $id;
    private string $titulo;
    private string $genero;
    private int $ano;
    private string $classificacao;
    private int $quantidade;
    private float $precoLocacao;

    /* Construtor da Classe Filme */
     public function __construct(int $id, string $titulo, string $genero, int $ano, string $classificacao, int $quantidade, float $precoLocacao) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->genero = $genero;
        $this->ano = $ano;
        $this->classificacao = $classificacao;
        $this->quantidade = $quantidade;
        $this->precoLocacao = $precoLocacao;
    }

    /* GETTERS */
     public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getGenero() {
        return $this->genero;
    }

    public function getAno() {
        return $this->ano;
    }

    public function getClassificacao() {
        return $this->classificacao;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function getPrecoLocacao() {
        return $this->precoLocacao;
    }

    /* SETTERS */
   public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setGenero($genero) {
        $this->genero = $genero;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }

    public function setClassificacao($classificacao) {
        $this->classificacao = $classificacao;
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    public function setPrecoLocacao($precoLocacao) {
        $this->precoLocacao = $precoLocacao;
    }


    /* Métodos da Classe */
    public function alugar() {
        if ($this->quantidade > 0) {
            $this->quantidade--;
            return true;
        } else {
            return false;
        }
    }

    public function devolver() {
        $this->quantidade++;
    }

    public function exibirDados() {
        echo "ID: " . $this->id . "<br>";
        echo "Título: " . $this->titulo . "<br>";
        echo "Gênero: " . $this->genero . "<br>";
        echo "Ano: " . $this->ano . "<br>";
        echo "Classificação: " . $this->classificacao . "<br>";
        echo "Quantidade: " . $this->quantidade . "<br>";
        echo "Preço: " . $this->precoLocacao . "<br>";
    }
}

?>
