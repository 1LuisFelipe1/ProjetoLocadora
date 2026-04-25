<?php

class SistemaLocadora
{
    private array $filmes = [];
    private array $clientes = [];
    private array $locacoes = [];

    public function __construct(array $filmes, array $clientes, array $locacoes){

        $this->filmes = $filmes;
        $this->clientes = $clientes;
        $this->locacoes = $locacoes;
    }

    public function cadastrarFilme(Filme $filme) :void {
        $this->filmes[] = $filme;
    }

    public function cadastrarCliente(Cliente$cliente) : void {
        $this->clientes[] = $cliente;
    }

    public function realizarLocacao(Cliente $cliente, Filme $filme, int $dias) {
        $locacao = new Locacao($cliente, $filme, $dias);
        $this->locacoes[] = $locacao;
    }

    public function devolverLocacao(Locacao $locacao, DateTime $dataDevolucao) : void {
        $locacao->finalizarLocacao($dataDevolucao);
    }

    public function listarFilmes(){
        foreach($this->filmes as $filme) {
            echo "Id: " . $filme->getId() . PHP_EOL;
            echo "Titulo: " . $filme->getTitulo() . PHP_EOL;
            echo "Genero: " . $filme->getGenero() . PHP_EOL;
            echo "Ano: " . $filme->getAno() . PHP_EOL;
            echo "Classificacao: " . $filme->getClassificacao() . PHP_EOL;
            echo "Quantidade: " . $filme->getQuantidade() . PHP_EOL;
            echo "Preco para alugar: " . $filme->getPrecoLocacao() . PHP_EOL;
        }
    }

    public function listarClientes() {
        foreach($this->clientes as $cliente) {
            echo "ID: " . $cliente->getId() . PHP_EOL;
            echo "Nome: " . $cliente->getNome() . PHP_EOL;
            echo "CPF: " . $cliente->getCpf() . PHP_EOL;
            echo "Telefone: " . $cliente->getTelefone() . PHP_EOL;
            echo "Data de Nascimento: " . $cliente->getDataNascimento() . PHP_EOL;
            echo "Endereco: " . $cliente->getEndereco() . PHP_EOL;
        }
    }

    public function listarLocacoes() : array {
        return $this->locacoes;
    }

    public function buscarFilme(int $id) {
        foreach($this->filmes as $filme) {
                if($filme->getId() == $id) {
                echo "Id: " . $filme->getTitulo() . PHP_EOL;
                return;
                }
            }
        echo "Filme nao encontrado!";
        }
    public function buscarCliente(int $id) {
        foreach($this->clientes as $cliente) {
            if($cliente->getId() == $id) {
                echo "ID: " . $cliente->getId() . PHP_EOL;
                echo "Nome: " . $cliente->getNome() . PHP_EOL;
                return;
            }
        }
        echo "Cliente nao encontrado!";
    }

}