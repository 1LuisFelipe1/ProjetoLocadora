<?php

class Locacao
{
    private int $id;
    private Cliente $cliente;
    private Filme $filme;
    private DateTime $dataLocacao;
    private DateTime $dataDevolucaoPrevista;
    private ?DateTime $dataDevolucaoReal;
    private int $diasLocados;
    private float $valorTotal;
    private float $multa;
    private string $status;

    /* Construtor da Classe Locacao */
    public function __construct(Cliente $cliente, Filme $filme, int $diasLocados) {
        $this->cliente = $cliente;
        $this->filme = $filme;
        $this->diasLocados = $diasLocados;
        $this->dataLocacao = new DateTime();
        $this->dataDevolucaoPrevista = (new DateTime()) -> modify("+{$diasLocados} dias");
        $this->dataDevolucaoReal = null;
        $this->valorTotal = $this->calcularValor();
        $this->multa = 0.0;
        $this->status = 'Ativa!';
    }

    /* Métodos Funções da Classe Locacao */
    public function calcularValor() : float {
        return $this->diasLocados * $this->filme->getPrecoLocacao();
    }

    public function calcularMulta() : float {
        if (!$this->estaAtrasada()) {
            return 0.0;
        }
        $hoje = new DateTime();
        $referencia = $this->dataDevolucaoReal ?? $hoje;

        $diasAtraso = $this->dataDevolucaoPrevista
            ->diff($referencia)
            ->days;

        $this->multa = $diasAtraso * 2.0;

        return $this->multa;
    }

    public function finalizarLocacao(DateTime $dataDevolucaoReal) : void {
        $this->dataDevolucaoReal = $dataDevolucaoReal;
        $this->multa = $this->calcularMulta();
        $this->valorTotal += $this->multa;
        $this->status = 'Finalizada!';
    }

    public function estaAtrasada() : bool {
        $referencia = $this->dataDevolucaoReal ?? new DateTime();
        return $referencia > $this->dataDevolucaoPrevista;
    }

    public function exibirDados() : array {
        return [
            'Cliente: ' => $this->cliente->getNome(),
            'Filme: ' => $this->filme->getTitulo(),
            'Data de locação: ' => $this->dataLocacao->format('d/m/Y'),
            'Data Prevista: ' => $this->dataDevolucaoPrevista->format('d/m/Y'),
            'Data Devolvida: ' => $this->dataDevolucaoReal
                ? $this->dataDevolucaoReal->format('d/m/Y')
                : 'Não devolvido',
            'Dias Alugados: ' => $this->diasLocados,
            'Valor: ' => $this->valorTotal,
            'Multa: R$' => $this->multa,
            'Status: ' => $this->status
        ];
    }

}