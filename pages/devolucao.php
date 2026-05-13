<?php
require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/layout.php';

$locacao = $sistema->buscarLocacao((int)($_GET['id'] ?? ($_POST['id'] ?? 0)));
$erro = '';

if (!$locacao || $locacao->estaFinalizada()) {
    redirecionar(BASE_URL . '/pages/locacoes.php');
}

$dataDevolucao = $_POST['dataDevolucao'] ?? date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = DateTime::createFromFormat('Y-m-d', $dataDevolucao);

    if (!$data) {
        $erro = 'Informe uma data de devolução válida.';
    } else {
        $sistema->devolverLocacao($locacao, $data);
        redirecionar(BASE_URL . '/pages/locacoes.php?sucesso=devolucao');
    }
}

$dataPreview = DateTime::createFromFormat('Y-m-d', $dataDevolucao) ?: new DateTime();
$diasAtraso = $locacao->getDataDevolucaoPrevista() < $dataPreview
    ? $locacao->getDataDevolucaoPrevista()->diff($dataPreview)->days
    : 0;
$multaPreview = $diasAtraso * 2.0;
$totalPreview = $locacao->getValorTotal() + $multaPreview;

renderHeader('Devolução', 'locacoes');
renderPageHeader('Devolução de filme', 'Finalize a locação, calcule multa por atraso e devolva o filme ao estoque.');
?>

<?php if ($erro): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= h($erro) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-5">
        <section class="content-surface">
            <div class="section-title">
                <h2>Resumo da locação</h2>
            </div>
            <p><strong>Cliente:</strong> <?= h($locacao->getCliente()->getNome()) ?></p>
            <p><strong>Filme:</strong> <?= h($locacao->getFilme()->getTitulo()) ?></p>
            <p><strong>Data da locação:</strong> <?= dataBr($locacao->getDataLocacao()) ?></p>
            <p><strong>Previsão:</strong> <?= dataBr($locacao->getDataDevolucaoPrevista()) ?></p>
            <p><strong>Valor original:</strong> <?= moeda($locacao->getValorTotal()) ?></p>
            <p class="mb-0"><strong>Status atual:</strong> <?= badgeStatusLocacao($locacao) ?></p>
        </section>
    </div>
    <div class="col-lg-7">
        <section class="form-surface">
            <form method="post" class="row g-3">
                <input type="hidden" name="id" value="<?= h($locacao->getId()) ?>">
                <div class="col-md-6">
                    <label class="form-label" for="dataDevolucao">Data real da devolução</label>
                    <input class="form-control" type="date" id="dataDevolucao" name="dataDevolucao" value="<?= h($dataDevolucao) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dias de atraso</label>
                    <div class="form-control bg-light"><?= h($diasAtraso) ?> dia(s)</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Multa estimada</label>
                    <div class="form-control bg-light"><?= moeda($multaPreview) ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total final</label>
                    <div class="form-control bg-light"><?= moeda($totalPreview) ?></div>
                </div>
                <?php if ($diasAtraso > 0): ?>
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Há atraso nesta devolução. A multa aplicada é de R$ 2,00 por dia.
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a class="btn btn-outline-secondary" href="pages/locacoes.php">Cancelar</a>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-2"></i>Confirmar devolução</button>
                </div>
            </form>
        </section>
    </div>
</div>

<?php renderFooter(); ?>
