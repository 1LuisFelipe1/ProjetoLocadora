<?php

function renderHeader(string $titulo, string $ativo = 'dashboard'): void
{
    $links = [
        'dashboard' => ['href' => 'pages/index.php', 'icone' => 'bi-speedometer2', 'label' => 'Dashboard'],
        'clientes' => ['href' => 'pages/clientes.php', 'icone' => 'bi-people', 'label' => 'Clientes'],
        'filmes' => ['href' => 'pages/filmes.php', 'icone' => 'bi-film', 'label' => 'Filmes'],
        'nova-locacao' => ['href' => 'pages/nova_locacao.php', 'icone' => 'bi-bag-plus', 'label' => 'Nova locação'],
        'locacoes' => ['href' => 'pages/locacoes.php', 'icone' => 'bi-arrow-left-right', 'label' => 'Locações'],
        'relatorios' => ['href' => 'pages/relatorios.php', 'icone' => 'bi-bar-chart', 'label' => 'Relatórios'],
    ];
    ?>
    <!doctype html>
    <html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="<?= BASE_URL ?>/">
        <title><?= h($titulo) ?> | DiguinhoMax</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link href="<?= BASE_URL ?>/assets/css/app.css" rel="stylesheet">
    </head>
    <body>
    <nav class="navbar navbar-expand-lg app-mobile-nav d-lg-none">
        <div class="container-fluid">
            <a class="navbar-brand" href="pages/index.php">
                <img src="<?= BASE_URL ?>/assets/DiguinhoLogo.png" alt="DiguinhoMax" height="60">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mobileMenu">
                <div class="navbar-nav pt-3">
                    <?php foreach ($links as $chave => $link): ?>
                        <a class="nav-link <?= $ativo === $chave ? 'active' : '' ?>" href="<?= h($link['href']) ?>">
                            <i class="bi <?= h($link['icone']) ?>"></i>
                            <?= h($link['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="app-shell">
        <aside class="app-sidebar d-none d-lg-flex">
            <a class="brand" href="pages/index.php">
                <img src="<?= BASE_URL ?>/assets/DiguinhoLogo.png" alt="DiguinhoMax" height="80">
            </a>

            <nav class="sidebar-nav">
                <?php foreach ($links as $chave => $link): ?>
                    <a class="<?= $ativo === $chave ? 'active' : '' ?>" href="<?= h($link['href']) ?>">
                        <i class="bi <?= h($link['icone']) ?>"></i>
                        <?= h($link['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="sidebar-footer">
                <span class="status-dot"></span>
                Sistema em sessão local
            </div>
        </aside>

        <main class="app-main">
    <?php
}

function renderPageHeader(string $titulo, string $descricao, string $acoes = ''): void
{
    ?>
    <div class="page-header">
        <div>
            <p class="eyebrow">DiguinhoMax</p>
            <h1><?= h($titulo) ?></h1>
            <p><?= h($descricao) ?></p>
        </div>
        <?php if ($acoes): ?>
            <div class="page-actions">
                <?= $acoes ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

function renderFeedback(): void
{
    $mensagens = [
        'cliente' => 'Cliente cadastrado com sucesso.',
        'filme' => 'Filme cadastrado com sucesso.',
        'locacao' => 'Locação realizada com sucesso.',
        'devolucao' => 'Devolução finalizada com sucesso.',
    ];

    if (isset($_GET['sucesso'], $mensagens[$_GET['sucesso']])) {
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?= h($mensagens[$_GET['sucesso']]) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
        <?php
    }
}

function renderFooter(): void
{
    ?>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
