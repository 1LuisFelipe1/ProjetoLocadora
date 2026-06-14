<?php

define('BASE_URL', '/ProjetoLocadora');
$env = parse_ini_file(__DIR__ . '/../env');
define('TMDB_API_KEY', $env['API_KEY'] ?? '');

require_once __DIR__ . '/../Classes/Filme.php';
require_once __DIR__ . '/../Classes/Cliente.php';
require_once __DIR__ . '/../Classes/Locacao.php';
require_once __DIR__ . '/../Classes/SistemaLocadora.php';
require_once __DIR__ . '/../assets/api/TmdbService.php';

session_start();

if (isset($_GET['resetar'])) {
    $_SESSION = [];
    session_destroy();
    session_start();
}

if (
    !isset($_SESSION['sistema'])
    || !($_SESSION['sistema'] instanceof SistemaLocadora)
    || ($_SESSION['schemaVersion'] ?? 0) !== 3
) {
    $sistemaInicial = new SistemaLocadora();

    $filmes = [
        new Filme(1, 'O Poderoso Chefão', 'Crime/Drama', 1972, '16', 3, 9.90, ''),
        new Filme(2, 'Toy Story', 'Animação/Família', 1995, 'Livre', 5, 7.50, ''),
    ];

    $clientes = [
        new Cliente(1, 'Ana Souza', '123.456.789-00', '(11) 99999-0001', '15/03/2001', 'Rua das Flores, 120'),
        new Cliente(2, 'Lucas Lima', '987.654.321-00', '(11) 98888-0002', '08/09/2011', 'Av. Brasil, 45'),
        new Cliente(3, 'Marina Costa', '456.789.123-00', '(11) 97777-0003', '21/01/1995', 'Rua Central, 90'),
    ];

    foreach ($filmes as $filme) {
        $sistemaInicial->cadastrarFilme($filme);
    }

    foreach ($clientes as $cliente) {
        $sistemaInicial->cadastrarCliente($cliente);
    }

    $sistemaInicial->realizarLocacao($clientes[0], $filmes[0], 3, 1);

    $_SESSION['sistema'] = $sistemaInicial;
    $_SESSION['clienteId'] = 3;
    $_SESSION['filmeId'] = 2;
    $_SESSION['locacaoId'] = 1;
    $_SESSION['schemaVersion'] = 3;
}

$sistema = $_SESSION['sistema'];

function h(mixed $valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function moeda(float $valor): string
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function dataBr(DateTime $data): string
{
    return $data->format('d/m/Y');
}

function validarCpf(string $cpf): bool
{
    $cpf = preg_replace('/\D/', '', $cpf);
    if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += (int)$cpf[$i] * ($t + 1 - $i);
        }
        $resto = (10 * $soma) % 11;
        if ((int)$cpf[$t] !== ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }
    }
    return true;
}

function dataParaBr(string $data): string
{
    $objeto = DateTime::createFromFormat('Y-m-d', $data);
    return $objeto ? $objeto->format('d/m/Y') : $data;
}

function proximoId(string $chave): int
{
    if (!isset($_SESSION[$chave])) {
        $_SESSION[$chave] = 0;
    }

    $_SESSION[$chave]++;
    return $_SESSION[$chave];
}

function redirecionar(string $destino): never
{
    header("Location: {$destino}");
    exit;
}

function badgeStatusLocacao(Locacao $locacao): string
{
    if ($locacao->estaFinalizada()) {
        return '<span class="badge text-bg-secondary">Finalizada</span>';
    }

    if ($locacao->estaAtrasada()) {
        return '<span class="badge text-bg-danger">Atrasada</span>';
    }

    return '<span class="badge text-bg-success">Ativa</span>';
}

function badgeClassificacao(string $classificacao): string
{
    if ($classificacao === 'Livre') {
        return '<span class="badge text-bg-success">Livre</span>';
    }

    return '<span class="badge text-bg-warning text-dark">' . h($classificacao) . '+</span>';
}

function badgeEstoque(Filme $filme): string
{
    if (!$filme->estaDisponivel()) {
        return '<span class="badge text-bg-danger">Sem estoque</span>';
    }

    if ($filme->getQuantidade() <= 1) {
        return '<span class="badge text-bg-warning text-dark">Baixo estoque</span>';
    }

    return '<span class="badge text-bg-success">Disponível</span>';
}

function locacoesDoCliente(SistemaLocadora $sistema, Cliente $cliente): array
{
    return array_values(array_filter($sistema->listarLocacoes(), function (Locacao $locacao) use ($cliente) {
        return $locacao->getCliente()->getId() === $cliente->getId();
    }));
}

function totalArrecadado(SistemaLocadora $sistema): float
{
    return array_reduce($sistema->listarLocacoesFinalizadas(), function (float $total, Locacao $locacao) {
        return $total + $locacao->getValorTotal();
    }, 0.0);
}

function totalMultas(SistemaLocadora $sistema): float
{
    return array_reduce($sistema->listarLocacoesFinalizadas(), function (float $total, Locacao $locacao) {
        return $total + $locacao->getMulta();
    }, 0.0);
}
