<?php

class Cliente {

    private $id;
    private $nome;
    private $cpf;
    private $telefone;
    private $dataNascimento;
    private $endereco;
    
    /* Construtor da Classe Cliente */
    public function __construct($id, $nome, $cpf, $telefone, $dataNascimento, $endereco) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->telefone = $telefone;
        $this->dataNascimento = $dataNascimento;
        $this->endereco = $endereco;
    }

    /* GETTERS */
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    /* SETTERS */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    /* Métodos da Classe */

    public function getIdade() {
        return date("Y") - date("Y", strtotime($this->dataNascimento));
    }

    public function podeAlugar($classificacao) {
        $idade = $this->getIdade();

        if ($classificacao == "18" && $idade < 18) {
            return false;
        } elseif ($classificacao == "16" && $idade < 16) {
            return false;
        } else {
            return true;
        }
    }

    public function exibirDados() {
        echo "ID: " . $this->id . "<br>";
        echo "Nome: " . $this->nome . "<br>";
        echo "CPF: " . $this->cpf . "<br>";
        echo "Telefone: " . $this->telefone . "<br>";
        echo "Data Nascimento: " . $this->dataNascimento . "<br>";
        echo "Endereço: " . $this->endereco . "<br>";
        echo "Idade: " . $this->getIdade() . "<br>";
    }
}

?>
