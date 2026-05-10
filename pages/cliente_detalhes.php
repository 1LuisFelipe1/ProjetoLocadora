<?php
require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/layout.php';

$cliente = $sistema->buscarCliente((int)($_GET['id'] ?? 0));

if (!$cliente) {
    redirecionar('pages/clientes.php');
}

$historico = locacoesDoCliente($sistema, $cliente);

renderHeader('Detalhes do cliente', 'clientes');
renderPageHeader(
    $cliente->getNome(),
    'Dados completos do cliente e histórico de locações.',
    '<a class="btn btn-primary" href="pages/nova_locacao.php?cliente_id=' . h($cliente->getId()) . '"><i class="bi bi-bag-plus me-2"></i>Nova locação</a>'
);
?>

<div class="row g-4">
    <div class="col-lg-4">
        <section class="content-surface">
            <div class="section-title">
                <h2>Informações pessoais</h2>
            </div>
            <p><strong>CPF:</strong> <?= h($cliente->getCpf()) ?></p>
            <p><strong>Telefone:</strong> <?= h($cliente->getTelefone()) ?></p>
            <p><strong>Data de nascimento:</strong> <?= h($cliente->getDataNascimento()) ?></p>
            <p><strong>Idade:</strong> <?= h($cliente->getIdade()) ?> anos</p>
            <p class="mb-0"><strong>Endereço:</strong> <?= h($cliente->getEndereco()) ?></p>
        </section>
    </div>
    <div class="col-lg-8">
        <section class="content-surface">
            <div class="section-title">
                <h2>Histórico de locações</h2>
            </div>

            <?php if (empty($historico)): ?>
                <div class="empty-state">
                    <i class="bi bi-clock-history"></i>
                    Este cliente ainda não possui locações.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Filme</th>
                            <th>Locação</th>
                            <th>Prevista</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($historico as $locacao): ?>
                            <tr>
                                <td><?= h($locacao->getFilme()->getTitulo()) ?></td>
                                <td><?= dataBr($locacao->getDataLocacao()) ?></td>
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
</div>

<?php renderFooter(); ?>
