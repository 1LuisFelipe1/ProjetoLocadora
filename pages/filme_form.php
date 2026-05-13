<?php
require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/layout.php';

$erro   = '';
$valores = [
    'titulo'       => '',
    'genero'       => '',
    'ano'          => '',
    'classificacao'=> 'Livre',
    'quantidade'   => '',
    'precoLocacao' => '',
    'tmdb_id'      => '',
    'poster_url'   => '',
    'sinopse'      => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores = [
        'titulo'       => trim($_POST['titulo']       ?? ''),
        'genero'       => trim($_POST['genero']       ?? ''),
        'ano'          => trim($_POST['ano']           ?? ''),
        'classificacao'=> trim($_POST['classificacao'] ?? 'Livre'),
        'quantidade'   => trim($_POST['quantidade']   ?? ''),
        'precoLocacao' => trim($_POST['precoLocacao'] ?? ''),
        'tmdb_id'      => (int)($_POST['tmdb_id']     ?? 0),
        'poster_url'   => trim($_POST['poster_url']   ?? ''),
        'sinopse'      => trim($_POST['sinopse']      ?? ''),
    ];

    $obrigatorios = array_filter($valores, fn($k) => in_array($k, ['titulo','genero','ano','quantidade','precoLocacao']), ARRAY_FILTER_USE_KEY);
    if (in_array('', $obrigatorios, true)) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } else {
        $filme = new Filme(
            proximoId('filmeId'),
            $valores['titulo'],
            $valores['genero'],
            (int)$valores['ano'],
            $valores['classificacao'],
            max(0, (int)$valores['quantidade']),
            max(0.0, (float)str_replace(',', '.', $valores['precoLocacao'])),
            $valores['poster_url']
        );

        $sistema->cadastrarFilme($filme);
        redirecionar(BASE_URL . '/pages/filmes.php?sucesso=filme');
    }
}

renderHeader('Cadastro de filme', 'filmes');
renderPageHeader('Cadastro de filme', 'Busque pelo título na base TMDB e os dados serão preenchidos automaticamente.');
?>

<?php if ($erro): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= h($erro) ?></div>
<?php endif; ?>

<section class="form-surface mb-3">
    <label class="form-label fw-semibold">Buscar na base do TMDB</label>
    <div class="input-group mb-2">
        <input class="form-control" id="tmdbQuery" placeholder="Digite o título do filme..." autocomplete="off">
        <button class="btn btn-outline-primary" type="button" id="btnBuscar">
            <i class="bi bi-search me-1"></i>Buscar
        </button>
    </div>
    <div id="tmdbSpinner" class="text-muted small d-none">
        <div class="spinner-border spinner-border-sm me-1"></div> Buscando...
    </div>
    <div id="tmdbResults" class="row g-2 mt-1"></div>
</section>


<section class="form-surface">

    <div id="posterPreview" class="d-none mb-3 d-flex align-items-start gap-3">
        <img id="posterImg" src="" alt="Pôster" class="rounded" style="width:80px; height:120px; object-fit:cover; border:1px solid #dee2e6;">
        <div>
            <span class="badge bg-success mb-1"><i class="bi bi-check-circle me-1"></i>Dados importados do TMDB</span>
            <p id="posterTitle" class="mb-0 fw-semibold"></p>
            <p id="posterSinopse" class="text-muted small mb-0" style="max-width:420px;"></p>
        </div>
    </div>

    <form method="post" class="row g-3">

        <input type="hidden" name="tmdb_id"    id="f_tmdb_id">
        <input type="hidden" name="poster_url" id="f_poster_url">
        <input type="hidden" name="sinopse"    id="f_sinopse">

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
                <?php foreach (['Livre','10','12','14','16','18'] as $opcao): ?>
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
            <a class="btn btn-outline-secondary" href="pages/filmes.php">Cancelar</a>
            <button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-2"></i>Salvar filme</button>
        </div>
    </form>
</section>

<script>
(function () {
    const query    = document.getElementById('tmdbQuery');
    const btnBusc  = document.getElementById('btnBuscar');
    const spinner  = document.getElementById('tmdbSpinner');
    const results  = document.getElementById('tmdbResults');

    // ── Busca ──────────────────────────────────────────────────
    async function search() {
        const q = query.value.trim();
        if (q.length < 2) return;

        spinner.classList.remove('d-none');
        results.innerHTML = '';

        try {
            const res = await fetch(`<?= BASE_URL ?>/assets/api/tmdb-search.php?action=search&q=${encodeURIComponent(q)}`);
            const data = await res.json();
            renderResults(data.results ?? []);
        } catch {
            results.innerHTML = '<p class="text-danger small">Erro ao buscar. Tente novamente.</p>';
        } finally {
            spinner.classList.add('d-none');
        }
    }

    // ── Renderiza cards de resultado ───────────────────────────
    function renderResults(movies) {
        if (!movies.length) {
            results.innerHTML = '<p class="text-muted small">Nenhum resultado encontrado.</p>';
            return;
        }
        results.innerHTML = movies.map(m => `
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card h-100 border tmdb-card" role="button" data-id="${m.tmdb_id}" style="cursor:pointer;">
                    ${m.poster_url
                        ? `<img src="${m.poster_url}" class="card-img-top" alt="${escHtml(m.titulo)}" loading="lazy" style="aspect-ratio:2/3;object-fit:cover;">`
                        : `<div class="d-flex align-items-center justify-content-center bg-light" style="aspect-ratio:2/3;"><i class="bi bi-film" style="font-size:2rem;color:#ccc;"></i></div>`
                    }
                    <div class="card-body p-2">
                        <p class="card-title small fw-semibold mb-0" style="font-size:12px;line-height:1.3;">${escHtml(m.titulo)}</p>
                        <p class="text-muted mb-0" style="font-size:11px;">${m.ano || '—'}</p>
                    </div>
                </div>
            </div>`).join('');

        document.querySelectorAll('.tmdb-card').forEach(card => {
            card.addEventListener('click', () => selectMovie(parseInt(card.dataset.id)));
        });
    }

    // ── Busca detalhes e preenche formulário ───────────────────
    async function selectMovie(tmdbId) {
        spinner.classList.remove('d-none');
        try {
            const res = await fetch(`<?= BASE_URL ?>/assets/api/tmdb-search.php?action=details&id=${tmdbId}`);
            const details = await res.json();
            if (details.error) throw new Error(details.error);
            fillForm(details);
        } catch (e) {
            alert('Erro ao carregar detalhes: ' + e.message);
        } finally {
            spinner.classList.add('d-none');
        }
    }

    // ── Preenche campos do formulário ──────────────────────────
    function fillForm(d) {
        setValue('titulo',       d.titulo);
        setValue('ano',          d.ano);
        setValue('genero',       d.genero);
        setValue('f_tmdb_id',    d.tmdb_id);
        setValue('f_poster_url', d.poster_url ?? '');
        setValue('f_sinopse',    d.sinopse ?? '');


        const sel = document.getElementById('classificacao');
        [...sel.options].forEach(o => { o.selected = o.value === d.classificacao; });


        const preview = document.getElementById('posterPreview');
        preview.classList.remove('d-none');
        document.getElementById('posterImg').src       = d.poster_url ?? '';
        document.getElementById('posterTitle').textContent   = `${d.titulo} (${d.ano})`;
        document.getElementById('posterSinopse').textContent = d.sinopse ?? '';


        document.querySelector('form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function setValue(id, val) {
        const el = document.getElementById(id);
        if (el) el.value = val ?? '';
    }

    function escHtml(str) {
        return String(str).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }


    btnBusc.addEventListener('click', search);
    query.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); search(); } });
})();
</script>

<?php renderFooter(); ?>