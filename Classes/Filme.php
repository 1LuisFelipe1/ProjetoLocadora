<?php

class Filme
{
    private int $id;
    private string $titulo;
    private string $genero;
    private int $ano;
    private string $classificacao;
    private int $quantidade;
    private float $precoLocacao;

    public function __construct(int $id, string $titulo, string $genero, int $ano, string $classificacao, int $quantidade, float $precoLocacao)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->genero = $genero;
        $this->ano = $ano;
        $this->classificacao = $classificacao;
        $this->quantidade = $quantidade;
        $this->precoLocacao = $precoLocacao;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getGenero(): string
    {
        return $this->genero;
    }

    public function getAno(): int
    {
        return $this->ano;
    }

    public function getClassificacao(): string
    {
        return $this->classificacao;
    }

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    public function getPrecoLocacao(): float
    {
        return $this->precoLocacao;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function setGenero(string $genero): void
    {
        $this->genero = $genero;
    }

    public function setAno(int $ano): void
    {
        $this->ano = $ano;
    }

    public function setClassificacao(string $classificacao): void
    {
        $this->classificacao = $classificacao;
    }

    public function setQuantidade(int $quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    public function setPrecoLocacao(float $precoLocacao): void
    {
        $this->precoLocacao = $precoLocacao;
    }

    public function estaDisponivel(): bool
    {
        return $this->quantidade > 0;
    }

    public function alugar(): bool
    {
        if (!$this->estaDisponivel()) {
            return false;
        }

        $this->quantidade--;
        return true;
    }

    public function devolver(): void
    {
        $this->quantidade++;
    }

    public function exibirDados(): array
    {
        return [
            'ID' => $this->id,
            'Título' => $this->titulo,
            'Gênero' => $this->genero,
            'Ano' => $this->ano,
            'Classificação' => $this->classificacao,
            'Quantidade' => $this->quantidade,
            'Preço' => $this->precoLocacao,
        ];
    }
}
