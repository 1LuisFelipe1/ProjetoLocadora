<?php
require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/layout.php';

$erro = '';
$clienteSelecionado = (int)($_GET['cliente_id'] ?? ($_POST['cliente_id'] ?? 0));
$filmeSelecionado = (int)($_GET['filme_id'] ?? ($_POST['filme_id'] ?? 0));
$diasSelecionados = (int)($_POST['dias'] ?? 3);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $sistema->buscarCliente($clienteSelecionado);
    $filme = $sistema->buscarFilme($filmeSelecionado);

    if (!$cliente || !$filme || $diasSelecionados <= 0) {
        $erro = 'Selecione cliente, filme e uma quantidade válida de dias.';
    } elseif (!$cliente->podeAlugar($filme->getClassificacao())) {
        $erro = 'Este cliente não possui idade suficiente para a classificação do filme.';
    } elseif (!$filme->estaDisponivel()) {
        $erro = 'Este filme está sem estoque no momento.';
    } else {
        $sistema->realizarLocacao($cliente, $filme, $diasSelecionados, proximoId('locacaoId'));
        redirecionar(BASE_URL . '/pages/locacoes.php?sucesso=locacao');
    }
}

$clientePreview = $sistema->buscarCliente($clienteSelecionado);
$filmePreview = $sistema->buscarFilme($filmeSelecionado);
$valorPreview = $filmePreview ? $filmePreview->getPrecoLocacao() * max(1, $diasSelecionados) : 0;

renderHeader('Nova locação', 'nova-locacao');
renderPageHeader('Nova locação', 'Selecione cliente, filme e prazo para validar estoque, idade e valor total.');
?>

<?php if ($erro): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= h($erro) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-xl-7">
        <section class="form-surface">
            <form method="post" class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="cliente_id">Cliente</label>
                    <select class="form-select" id="cliente_id" name="cliente_id" required>
                        <option value="">Selecione um cliente</option>
                        <?php foreach ($sistema->listarClientes() as $cliente): ?>
                            <option value="<?= h($cliente->getId()) ?>" <?= $clienteSelecionado === $cliente->getId() ? 'selected' : '' ?>>
                                <?= h($cliente->getNome()) ?> - <?= h($cliente->getIdade()) ?> anos
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label" for="filme_id">Filme</label>
                    <select class="form-select" id="filme_id" name="filme_id" required>
                        <option value="">Selecione um filme</option>
                        <?php foreach ($sistema->listarFilmes() as $filme): ?>
                            <option value="<?= h($filme->getId()) ?>" <?= $filmeSelecionado === $filme->getId() ? 'selected' : '' ?> <?= !$filme->estaDisponivel() ? 'disabled' : '' ?>>
                                <?= h($filme->getTitulo()) ?> - <?= h($filme->getClassificacao()) ?> - estoque <?= h($filme->getQuantidade()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="dias">Dias locados</label>
                    <input class="form-control" type="number" min="1" id="dias" name="dias" value="<?= h(max(1, $diasSelecionados)) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Valor estimado</label>
                    <div class="form-control bg-light" id="valorEstimado"><?= moeda($valorPreview) ?></div>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a class="btn btn-outline-secondary" href="pages/locacoes.php">Cancelar</a>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-2"></i>Confirmar locação</button>
                </div>
            </form>
        </section>
    </div>
    <div class="col-xl-5">
        <section class="content-surface">
            <div class="section-title">
                <h2>Validação da locação</h2>
            </div>
            <?php if (!$clientePreview || !$filmePreview): ?>
                <div class="empty-state">
                    <i class="bi bi-ui-checks"></i>
                    Escolha um cliente e um filme para visualizar a validação.
                </div>
            <?php else: ?>
                <p><strong>Cliente:</strong> <?= h($clientePreview->getNome()) ?>, <?= h($clientePreview->getIdade()) ?> anos</p>
                <p><strong>Filme:</strong> <?= h($filmePreview->getTitulo()) ?></p>
                <p><strong>Classificação:</strong> <?= badgeClassificacao($filmePreview->getClassificacao()) ?></p>
                <p><strong>Estoque:</strong> <?= badgeEstoque($filmePreview) ?></p>
                <p class="mb-0">
                    <strong>Situação:</strong>
                    <?= $clientePreview->podeAlugar($filmePreview->getClassificacao()) ? '<span class="text-success">Cliente liberado para locar.</span>' : '<span class="text-danger">Cliente bloqueado pela idade.</span>' ?>
                </p>
            <?php endif; ?>
        </section>
    </div>
</div>

<script>
(function () {
    const diasInput   = document.getElementById('dias');
    const filmeSelect = document.getElementById('filme_id');
    const valorDiv    = document.getElementById('valorEstimado');

    const precos = <?= json_encode(array_combine(
        array_map(fn(Filme $f) => $f->getId(), $sistema->listarFilmes()),
        array_map(fn(Filme $f) => $f->getPrecoLocacao(), $sistema->listarFilmes())
    )) ?>;

    function atualizar() {
        const filmeId = parseInt(filmeSelect.value) || 0;
        const dias    = Math.max(1, parseInt(diasInput.value) || 1);
        const preco   = precos[filmeId] ?? 0;
        const total   = preco * dias;
        valorDiv.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
    }

    diasInput.addEventListener('input', atualizar);
    filmeSelect.addEventListener('change', atualizar);
})();
</script>

<?php renderFooter(); ?>
