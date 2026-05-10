<?php
require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/layout.php';

$locacoes = $sistema->listarLocacoes();
$finalizadas = $sistema->listarLocacoesFinalizadas();
$totalArrecadado = totalArrecadado($sistema);
$totalMultas = totalMultas($sistema);
$filmesMaisAlugados = [];
$clientesMaisAtivos = [];

foreach ($locacoes as $locacao) {
    $filme = $locacao->getFilme()->getTitulo();
    $cliente = $locacao->getCliente()->getNome();
    $filmesMaisAlugados[$filme] = ($filmesMaisAlugados[$filme] ?? 0) + 1;
    $clientesMaisAtivos[$cliente] = ($clientesMaisAtivos[$cliente] ?? 0) + 1;
}

arsort($filmesMaisAlugados);
arsort($clientesMaisAtivos);

renderHeader('Relatórios', 'relatorios');
renderPageHeader('Relatórios', 'Indicadores simples para apresentar o funcionamento da locadora.');
?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="metric-card">
            <span class="icon-box"><i class="bi bi-receipt"></i></span>
            <strong><?= moeda($totalArrecadado) ?></strong>
            <span>Total arrecadado</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card">
            <span class="icon-box bg-warning text-dark"><i class="bi bi-cash-coin"></i></span>
            <strong><?= moeda($totalMultas) ?></strong>
            <span>Total em multas</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card">
            <span class="icon-box bg-success"><i class="bi bi-check2-circle"></i></span>
            <strong><?= count($finalizadas) ?></strong>
            <span>Locações finalizadas</span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <section class="content-surface">
            <div class="section-title">
                <h2>Filmes mais alugados</h2>
            </div>
            <?php if (empty($filmesMaisAlugados)): ?>
                <div class="empty-state">
                    <i class="bi bi-film"></i>
                    Sem locações para exibir.
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($filmesMaisAlugados, 0, 5, true) as $filme => $total): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span><?= h($filme) ?></span>
                            <strong><?= h($total) ?> locação(ões)</strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
    <div class="col-lg-6">
        <section class="content-surface">
            <div class="section-title">
                <h2>Clientes com mais locações</h2>
            </div>
            <?php if (empty($clientesMaisAtivos)): ?>
                <div class="empty-state">
                    <i class="bi bi-people"></i>
                    Sem locações para exibir.
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($clientesMaisAtivos, 0, 5, true) as $cliente => $total): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span><?= h($cliente) ?></span>
                            <strong><?= h($total) ?> locação(ões)</strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<section class="content-surface">
    <div class="section-title">
        <h2>Histórico completo</h2>
    </div>

    <?php if (empty($locacoes)): ?>
        <div class="empty-state">
            <i class="bi bi-table"></i>
            Nenhuma locação registrada.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Filme</th>
                    <th>Locação</th>
                    <th>Prevista</th>
                    <th>Devolução</th>
                    <th>Multa</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (array_reverse($locacoes) as $locacao): ?>
                    <tr>
                        <td><?= h($locacao->getCliente()->getNome()) ?></td>
                        <td><?= h($locacao->getFilme()->getTitulo()) ?></td>
                        <td><?= dataBr($locacao->getDataLocacao()) ?></td>
                        <td><?= dataBr($locacao->getDataDevolucaoPrevista()) ?></td>
                        <td><?= $locacao->getDataDevolucaoReal() ? dataBr($locacao->getDataDevolucaoReal()) : '-' ?></td>
                        <td><?= moeda($locacao->getMulta()) ?></td>
                        <td><?= moeda($locacao->getValorTotal()) ?></td>
                        <td><?= badgeStatusLocacao($locacao) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php renderFooter(); ?>
