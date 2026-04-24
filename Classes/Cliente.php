<?php

class Cliente {

    private int $id;
    private string $nome;
    private string $cpf;
    private string $telefone;
    private string $dataNascimento;
    private string $endereco;
    
    /* Construtor da Classe Cliente */
    public function __construct(int $id, string $nome, string $cpf, string $telefone, string $dataNascimento, string $endereco) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->telefone = $telefone;
        $this->dataNascimento = $dataNascimento;
        $this->endereco = $endereco;
    }

    /* GETTERS */
    public function getId() : int{
        return $this->id;
    }

    public function getNome() : string {
        return $this->nome;
    }

    public function getCpf() : string {
        return $this->cpf;
    }

    public function getTelefone() : string {
        return $this->telefone;
    }

    public function getDataNascimento() : string {
        return $this->dataNascimento;
    }

    public function getEndereco() : string {
        return $this->endereco;
    }

    /* SETTERS */
    public function setNome($nome) : void {
        $this->nome = $nome;
    }

    public function setCpf($cpf) : void{
        $this->cpf = $cpf;
    }

    public function setTelefone($telefone) : void{
        $this->telefone = $telefone;
    }

    public function setDataNascimento($dataNascimento) : void{
        $this->dataNascimento = $dataNascimento;
    }

    public function setEndereco($endereco) : void{
        $this->endereco = $endereco;
    }

    /* Métodos da Classe */

    public function getIdade() : string{
       $anoAtual = date('Y');
       $anoNascimento = substr($this->dataNascimento, strrpos($this->dataNascimento,'/')+1);

       return (string)($anoAtual - $anoNascimento);
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
        echo "Nome: " . $this->getNome() . PHP_EOL;
        echo "CPF: " . $this->getCpf() . PHP_EOL;
        echo "Telefone: " . $this->getTelefone() . PHP_EOL;
        echo "Data Nascimento: " . $this->getDataNascimento() . PHP_EOL;
        echo "Endereço: " . $this->getEndereco() . PHP_EOL;
        echo "Idade: " . $this->getIdade() . PHP_EOL;
    }
}

?>
