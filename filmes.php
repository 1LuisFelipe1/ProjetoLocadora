<?php
require_once __DIR__ . '/includes/app.php';
require_once __DIR__ . '/includes/layout.php';

$busca = trim($_GET['busca'] ?? '');
$generoFiltro = trim($_GET['genero'] ?? '');
$disponibilidade = trim($_GET['disponibilidade'] ?? '');
$filmes = $sistema->listarFilmes();
$generos = array_values(array_unique(array_map(fn(Filme $filme) => $filme->getGenero(), $filmes)));
sort($generos);

$filmesFiltrados = array_values(array_filter($filmes, function (Filme $filme) use ($busca, $generoFiltro, $disponibilidade) {
    if ($busca !== '' && stripos($filme->getTitulo(), $busca) === false) {
        return false;
    }

    if ($generoFiltro !== '' && $filme->getGenero() !== $generoFiltro) {
        return false;
    }

    if ($disponibilidade === 'disponivel' && !$filme->estaDisponivel()) {
        return false;
    }

    if ($disponibilidade === 'sem-estoque' && $filme->estaDisponivel()) {
        return false;
    }

    return true;
}));

renderHeader('Filmes', 'filmes');
renderFeedback();
renderPageHeader(
    'Catálogo de filmes',
    'Gerencie títulos, estoque, classificação indicativa e valores de locação.',
    '<a class="btn btn-primary" href="filme_form.php"><i class="bi bi-plus-circle me-2"></i>Novo filme</a>'
);
?>

<section class="content-surface mb-4">
    <form class="row g-2" method="get">
        <div class="col-lg-5">
            <label class="visually-hidden" for="busca">Buscar filme</label>
            <input class="form-control" id="busca" name="busca" value="<?= h($busca) ?>" placeholder="Buscar por título">
        </div>
        <div class="col-lg-3">
            <label class="visually-hidden" for="genero">Gênero</label>
            <select class="form-select" id="genero" name="genero">
                <option value="">Todos os gêneros</option>
                <?php foreach ($generos as $genero): ?>
                    <option value="<?= h($genero) ?>" <?= $generoFiltro === $genero ? 'selected' : '' ?>><?= h($genero) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-2">
            <label class="visually-hidden" for="disponibilidade">Disponibilidade</label>
            <select class="form-select" id="disponibilidade" name="disponibilidade">
                <option value="">Todos</option>
                <option value="disponivel" <?= $disponibilidade === 'disponivel' ? 'selected' : '' ?>>Disponíveis</option>
                <option value="sem-estoque" <?= $disponibilidade === 'sem-estoque' ? 'selected' : '' ?>>Sem estoque</option>
            </select>
        </div>
        <div class="col-lg-2 d-grid">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-funnel me-2"></i>Filtrar</button>
        </div>
    </form>
</section>

<?php if (empty($filmesFiltrados)): ?>
    <section class="content-surface">
        <div class="empty-state">
            <i class="bi bi-film"></i>
            Nenhum filme encontrado.
        </div>
    </section>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($filmesFiltrados as $filme): ?>
            <div class="col-md-6 col-xl-4">
                <article class="movie-tile">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="d-flex gap-3">
                            <span class="movie-cover"><i class="bi bi-play-fill"></i></span>
                            <div>
                                <h2 class="h5 mb-1"><?= h($filme->getTitulo()) ?></h2>
                                <p class="text-muted mb-2"><?= h($filme->getGenero()) ?> · <?= h($filme->getAno()) ?></p>
                                <div class="d-flex flex-wrap gap-2">
                                    <?= badgeClassificacao($filme->getClassificacao()) ?>
                                    <?= badgeEstoque($filme) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Estoque</span>
                        <strong class="text-dark"><?= h($filme->getQuantidade()) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Preço por dia</span>
                        <strong class="text-dark"><?= moeda($filme->getPrecoLocacao()) ?></strong>
                    </div>
                    <div class="d-grid mt-3">
                        <a class="btn btn-outline-primary <?= !$filme->estaDisponivel() ? 'disabled' : '' ?>" href="nova_locacao.php?filme_id=<?= h($filme->getId()) ?>">
                            <i class="bi bi-bag-plus me-2"></i>Alugar
                        </a>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php renderFooter(); ?>
