<?php

class Cliente
{
    private int $id;
    private string $nome;
    private string $cpf;
    private string $telefone;
    private string $dataNascimento;
    private string $endereco;

    public function __construct(int $id, string $nome, string $cpf, string $telefone, string $dataNascimento, string $endereco)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->telefone = $telefone;
        $this->dataNascimento = $dataNascimento;
        $this->endereco = $endereco;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function getDataNascimento(): string
    {
        return $this->dataNascimento;
    }

    public function getEndereco(): string
    {
        return $this->endereco;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function setTelefone(string $telefone): void
    {
        $this->telefone = $telefone;
    }

    public function setDataNascimento(string $dataNascimento): void
    {
        $this->dataNascimento = $dataNascimento;
    }

    public function setEndereco(string $endereco): void
    {
        $this->endereco = $endereco;
    }

    public function getIdade(): int
    {
        $nascimento = DateTime::createFromFormat('d/m/Y', $this->dataNascimento);

        if (!$nascimento) {
            $nascimento = DateTime::createFromFormat('Y-m-d', $this->dataNascimento);
        }

        if (!$nascimento) {
            return 0;
        }

        return $nascimento->diff(new DateTime())->y;
    }

    public function podeAlugar(string $classificacao): bool
    {
        $idade = $this->getIdade();
        $classificacaoMinima = (int)$classificacao;

        if ($classificacaoMinima <= 0) {
            return true;
        }

        return $idade >= $classificacaoMinima;
    }

    public function exibirDados(): array
    {
        return [
            'Nome' => $this->getNome(),
            'CPF' => $this->getCpf(),
            'Telefone' => $this->getTelefone(),
            'Data de nascimento' => $this->getDataNascimento(),
            'Endereço' => $this->getEndereco(),
            'Idade' => $this->getIdade(),
        ];
    }
}
