<?php
require_once __DIR__ . '/includes/app.php';
require_once __DIR__ . '/includes/layout.php';

$locacoesAtivas = $sistema->listarLocacoesAtivas();
$locacoesFinalizadas = array_slice(array_reverse($sistema->listarLocacoesFinalizadas()), 0, 6);

renderHeader('Locações', 'locacoes');
renderFeedback();
renderPageHeader(
    'Locações ativas',
    'Acompanhe prazos, valores e devoluções pendentes da DiguinhoMax.',
    '<a class="btn btn-primary" href="nova_locacao.php"><i class="bi bi-bag-plus me-2"></i>Nova locação</a>'
);
?>

<section class="content-surface mb-4">
    <div class="section-title">
        <h2>Em andamento</h2>
        <span class="badge text-bg-primary"><?= count($locacoesAtivas) ?> ativas</span>
    </div>

    <?php if (empty($locacoesAtivas)): ?>
        <div class="empty-state">
            <i class="bi bi-check2-circle"></i>
            Nenhuma locação ativa no momento.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Filme</th>
                    <th>Locação</th>
                    <th>Devolução prevista</th>
                    <th>Dias</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th class="text-end">Ação</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($locacoesAtivas as $locacao): ?>
                    <tr class="<?= $locacao->estaAtrasada() ? 'table-danger' : '' ?>">
                        <td><?= h($locacao->getCliente()->getNome()) ?></td>
                        <td><?= h($locacao->getFilme()->getTitulo()) ?></td>
                        <td><?= dataBr($locacao->getDataLocacao()) ?></td>
                        <td><?= dataBr($locacao->getDataDevolucaoPrevista()) ?></td>
                        <td><?= h($locacao->getDiasLocados()) ?></td>
                        <td><?= moeda($locacao->getValorTotal()) ?></td>
                        <td><?= badgeStatusLocacao($locacao) ?></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-warning" href="devolucao.php?id=<?= h($locacao->getId()) ?>">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Devolver
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<section class="content-surface">
    <div class="section-title">
        <h2>Histórico recente</h2>
        <a href="relatorios.php" class="btn btn-sm btn-outline-primary">Ver relatório</a>
    </div>

    <?php if (empty($locacoesFinalizadas)): ?>
        <div class="empty-state">
            <i class="bi bi-clock-history"></i>
            Nenhuma devolução finalizada ainda.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Filme</th>
                    <th>Devolvido em</th>
                    <th>Multa</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($locacoesFinalizadas as $locacao): ?>
                    <tr>
                        <td><?= h($locacao->getCliente()->getNome()) ?></td>
                        <td><?= h($locacao->getFilme()->getTitulo()) ?></td>
                        <td><?= $locacao->getDataDevolucaoReal() ? dataBr($locacao->getDataDevolucaoReal()) : '-' ?></td>
                        <td><?= moeda($locacao->getMulta()) ?></td>
                        <td><?= moeda($locacao->getValorTotal()) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php renderFooter(); ?>
