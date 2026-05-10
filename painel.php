<?php
require_once 'Classes/Filme.php';
require_once 'Classes/Cliente.php';
require_once 'Classes/Locacao.php';
require_once 'Classes/SistemaLocadora.php';

session_start();

if (!isset($_SESSION['ultimoCliente'])) {
    header('Location: index.php');
    exit;
}

$sistema = $_SESSION['sistema'];
$cliente = $_SESSION['ultimoCliente'];

$filme1 = new Filme(1, "Matrix", "Ação", 1999, "16", 3, 10.0);
$filme2 = new Filme(2, "Vingadores", "Ação", 2019, "12", 5, 12.0);
$sistema->cadastrarFilme($filme1);
$sistema->cadastrarFilme($filme2);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Locadora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h3>Cliente cadastrado</h3>
                <hr>
                <p><strong>Nome:</strong> <?= $cliente->getNome() ?></p>
                <p><strong>CPF:</strong> <?= $cliente->getCpf() ?></p>
                <p><strong>Telefone:</strong> <?= $cliente->getTelefone() ?></p>
                <p><strong>Data Nasc.:</strong> <?= $cliente->getDataNascimento() ?></p>
                <p><strong>Endereço:</strong> <?= $cliente->getEndereco() ?></p>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <h3>Filmes disponíveis</h3>
                <hr>
                <div class="row">
                    <?php foreach ([$filme1, $filme2] as $f): ?>
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-2">
                            <h5><?= $f->getTitulo() ?></h5>
                            <p class="mb-1">Gênero: <?= $f->getGenero() ?></p>
                            <p class="mb-1">Ano: <?= $f->getAno() ?></p>
                            <p class="mb-1">Classificação: <?= $f->getClassificacao() ?> anos</p>
                            <p class="mb-1">Estoque: <?= $f->getQuantidade() ?></p>
                            <p class="mb-0">Preço: R$ <?= number_format($f->getPrecoLocacao(), 2, ',', '.') ?>/dia</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <a href="formulario.html" class="btn btn-primary">Novo cadastro</a>
    </div>
</body>
</html>
