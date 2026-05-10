<?php
require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/layout.php';

$busca = trim($_GET['busca'] ?? '');
$clientes = $sistema->listarClientes();

if ($busca !== '') {
    $clientes = array_values(array_filter($clientes, function (Cliente $cliente) use ($busca) {
        return stripos($cliente->getNome(), $busca) !== false || stripos($cliente->getCpf(), $busca) !== false;
    }));
}

renderHeader('Clientes', 'clientes');
renderFeedback();
renderPageHeader(
    'Clientes',
    'Consulte os clientes cadastrados, veja idade, histórico e inicie novas locações.',
    '<a class="btn btn-primary" href="pages/cliente_form.php"><i class="bi bi-person-plus me-2"></i>Novo cliente</a>'
);
?>

<section class="content-surface">
    <form class="row g-2 mb-4" method="get">
        <div class="col-md-10">
            <label class="visually-hidden" for="busca">Buscar cliente</label>
            <input class="form-control" id="busca" name="busca" value="<?= h($busca) ?>" placeholder="Buscar por nome ou CPF">
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search me-2"></i>Buscar</button>
        </div>
    </form>

    <?php if (empty($clientes)): ?>
        <div class="empty-state">
            <i class="bi bi-person-x"></i>
            Nenhum cliente encontrado.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Idade</th>
                    <th>Perfil</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td>#<?= h($cliente->getId()) ?></td>
                        <td><strong><?= h($cliente->getNome()) ?></strong></td>
                        <td><?= h($cliente->getCpf()) ?></td>
                        <td><?= h($cliente->getTelefone()) ?></td>
                        <td><?= h($cliente->getIdade()) ?> anos</td>
                        <td>
                            <?= $cliente->getIdade() >= 18 ? '<span class="badge text-bg-primary">Maior de idade</span>' : '<span class="badge text-bg-warning text-dark">Menor de idade</span>' ?>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="pages/cliente_detalhes.php?id=<?= h($cliente->getId()) ?>" title="Ver detalhes">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a class="btn btn-sm btn-outline-primary" href="pages/nova_locacao.php?cliente_id=<?= h($cliente->getId()) ?>" title="Nova locação">
                                <i class="bi bi-bag-plus"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php renderFooter(); ?>
