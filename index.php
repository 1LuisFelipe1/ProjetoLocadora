<?php
require_once __DIR__ . '/includes/app.php';
require_once __DIR__ . '/includes/layout.php';

$filmes = $sistema->listarFilmes();
$clientes = $sistema->listarClientes();
$locacoes = $sistema->listarLocacoes();
$locacoesAtivas = $sistema->listarLocacoesAtivas();
$locacoesAtrasadas = array_values(array_filter($locacoesAtivas, fn(Locacao $locacao) => $locacao->estaAtrasada()));
$baixoEstoque = array_values(array_filter($filmes, fn(Filme $filme) => $filme->getQuantidade() <= 1));
$ultimasLocacoes = array_slice(array_reverse($locacoes), 0, 5);

renderHeader('Dashboard', 'dashboard');
renderFeedback();
renderPageHeader(
    'Dashboard',
    'Resumo operacional da locadora, com clientes, filmes, locações ativas e alertas de estoque.',
    '<a class="btn btn-primary" href="nova_locacao.php"><i class="bi bi-bag-plus me-2"></i>Nova locação</a>'
);
?>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="metric-card">
            <span class="icon-box"><i class="bi bi-film"></i></span>
            <strong><?= count($filmes) ?></strong>
            <span>Filmes cadastrados</span>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="metric-card">
            <span class="icon-box"><i class="bi bi-people"></i></span>
            <strong><?= count($clientes) ?></strong>
            <span>Clientes cadastrados</span>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="metric-card">
            <span class="icon-box"><i class="bi bi-arrow-left-right"></i></span>
            <strong><?= count($locacoesAtivas) ?></strong>
            <span>Locações ativas</span>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="metric-card">
            <span class="icon-box bg-danger"><i class="bi bi-exclamation-triangle"></i></span>
            <strong><?= count($locacoesAtrasadas) ?></strong>
            <span>Devoluções atrasadas</span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <section class="content-surface">
            <div class="section-title">
                <h2>Últimas locações</h2>
                <a href="locacoes.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>

            <?php if (empty($ultimasLocacoes)): ?>
                <div class="empty-state">
                    <i class="bi bi-inboxes"></i>
                    Nenhuma locação registrada ainda.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Filme</th>
                            <th>Prevista</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ultimasLocacoes as $locacao): ?>
                            <tr>
                                <td><?= h($locacao->getCliente()->getNome()) ?></td>
                                <td><?= h($locacao->getFilme()->getTitulo()) ?></td>
                                <td><?= dataBr($locacao->getDataDevolucaoPrevista()) ?></td>
                                <td><?= moeda($locacao->getValorTotal()) ?></td>
                                <td><?= badgeStatusLocacao($locacao) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <div class="col-xl-4">
        <section class="content-surface">
            <div class="section-title">
                <h2>Alertas de estoque</h2>
                <a href="filmes.php" class="btn btn-sm btn-outline-primary">Catálogo</a>
            </div>

            <?php if (empty($baixoEstoque)): ?>
                <div class="empty-state">
                    <i class="bi bi-check2-circle"></i>
                    Estoque saudável no momento.
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($baixoEstoque as $filme): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= h($filme->getTitulo()) ?></strong>
                                <div class="text-muted small"><?= h($filme->getGenero()) ?> · <?= h($filme->getAno()) ?></div>
                            </div>
                            <?= badgeEstoque($filme) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php renderFooter(); ?>
