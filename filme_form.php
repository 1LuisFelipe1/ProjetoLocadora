<?php
require_once __DIR__ . '/includes/app.php';
require_once __DIR__ . '/includes/layout.php';

$erro = '';
$valores = [
    'titulo' => '',
    'genero' => '',
    'ano' => '',
    'classificacao' => 'Livre',
    'quantidade' => '',
    'precoLocacao' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores = [
        'titulo' => trim($_POST['titulo'] ?? ''),
        'genero' => trim($_POST['genero'] ?? ''),
        'ano' => trim($_POST['ano'] ?? ''),
        'classificacao' => trim($_POST['classificacao'] ?? 'Livre'),
        'quantidade' => trim($_POST['quantidade'] ?? ''),
        'precoLocacao' => trim($_POST['precoLocacao'] ?? ''),
    ];

    if (in_array('', $valores, true)) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } else {
        $filme = new Filme(
            proximoId('filmeId'),
            $valores['titulo'],
            $valores['genero'],
            (int)$valores['ano'],
            $valores['classificacao'],
            max(0, (int)$valores['quantidade']),
            max(0, (float)str_replace(',', '.', $valores['precoLocacao']))
        );

        $sistema->cadastrarFilme($filme);
        salvarSistema($sistema);
        redirecionar('filmes.php?sucesso=filme');
    }
}

renderHeader('Cadastro de filme', 'filmes');
renderPageHeader('Cadastro de filme', 'Adicione um novo título ao catálogo com estoque e preço de locação.');
?>

<?php if ($erro): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= h($erro) ?></div>
<?php endif; ?>

<section class="form-surface">
    <form method="post" class="row g-3">
        <div class="col-md-8">
            <label class="form-label" for="titulo">Título</label>
            <input class="form-control" id="titulo" name="titulo" value="<?= h($valores['titulo']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="ano">Ano</label>
            <input class="form-control" type="number" min="1900" max="2100" id="ano" name="ano" value="<?= h($valores['ano']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="genero">Gênero</label>
            <input class="form-control" id="genero" name="genero" value="<?= h($valores['genero']) ?>" placeholder="Ação, Drama, Comédia..." required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="classificacao">Classificação indicativa</label>
            <select class="form-select" id="classificacao" name="classificacao" required>
                <?php foreach (['Livre', '10', '12', '14', '16', '18'] as $opcao): ?>
                    <option value="<?= h($opcao) ?>" <?= $valores['classificacao'] === $opcao ? 'selected' : '' ?>><?= h($opcao) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="quantidade">Quantidade em estoque</label>
            <input class="form-control" type="number" min="0" id="quantidade" name="quantidade" value="<?= h($valores['quantidade']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="precoLocacao">Preço por dia</label>
            <input class="form-control" type="number" min="0" step="0.01" id="precoLocacao" name="precoLocacao" value="<?= h($valores['precoLocacao']) ?>" required>
        </div>
        <div class="col-12 d-flex gap-2 justify-content-end">
            <a class="btn btn-outline-secondary" href="filmes.php">Cancelar</a>
            <button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-2"></i>Salvar filme</button>
        </div>
    </form>
</section>

<?php renderFooter(); ?>
