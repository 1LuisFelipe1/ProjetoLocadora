<?php

class SistemaLocadora
{
    private array $filmes = [];
    private array $clientes = [];
    private array $locacoes = [];

    public function __construct(array $filmes = [], array $clientes = [], array $locacoes = [])
    {
        $this->filmes = $filmes;
        $this->clientes = $clientes;
        $this->locacoes = $locacoes;
    }

    public function cadastrarFilme(Filme $filme): void
    {
        $this->filmes[] = $filme;
    }

    public function cadastrarCliente(Cliente $cliente): void
    {
        $this->clientes[] = $cliente;
    }

    public function realizarLocacao(Cliente $cliente, Filme $filme, int $dias, ?int $id = null): Locacao
    {
        $locacao = new Locacao($cliente, $filme, $dias, $id);
        $this->locacoes[] = $locacao;

        return $locacao;
    }

    public function devolverLocacao(Locacao $locacao, DateTime $dataDevolucao): void
    {
        $jaFinalizada = $locacao->estaFinalizada();
        $locacao->finalizarLocacao($dataDevolucao);

        if (!$jaFinalizada) {
            $locacao->getFilme()->devolver();
        }
    }

    public function listarFilmes(): array
    {
        return $this->filmes;
    }

    public function listarClientes(): array
    {
        return $this->clientes;
    }

    public function listarLocacoes(): array
    {
        return $this->locacoes;
    }

    public function listarLocacoesAtivas(): array
    {
        return array_values(array_filter($this->locacoes, function (Locacao $locacao) {
            return !$locacao->estaFinalizada();
        }));
    }

    public function listarLocacoesFinalizadas(): array
    {
        return array_values(array_filter($this->locacoes, function (Locacao $locacao) {
            return $locacao->estaFinalizada();
        }));
    }

    public function buscarFilme(int $id): ?Filme
    {
        foreach ($this->filmes as $filme) {
            if ($filme->getId() === $id) {
                return $filme;
            }
        }

        return null;
    }

    public function buscarCliente(int $id): ?Cliente
    {
        foreach ($this->clientes as $cliente) {
            if ($cliente->getId() === $id) {
                return $cliente;
            }
        }

        return null;
    }

    public function buscarLocacao(int $id): ?Locacao
    {
        foreach ($this->locacoes as $locacao) {
            if ($locacao->getId() === $id) {
                return $locacao;
            }
        }

        return null;
    }
}
