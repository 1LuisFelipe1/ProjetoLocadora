<?php
require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/layout.php';

$erro = '';
$valores = [
    'nome' => '',
    'cpf' => '',
    'telefone' => '',
    'dataNascimento' => '',
    'endereco' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores = [
        'nome' => trim($_POST['nome'] ?? ''),
        'cpf' => trim($_POST['cpf'] ?? ''),
        'telefone' => trim($_POST['telefone'] ?? ''),
        'dataNascimento' => trim($_POST['dataNascimento'] ?? ''),
        'endereco' => trim($_POST['endereco'] ?? ''),
    ];

    if (in_array('', $valores, true)) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } elseif (!validarCpf($valores['cpf'])) {
        $erro = 'Informe um CPF válido.';
    } else {
        $cliente = new Cliente(
            proximoId('clienteId'),
            $valores['nome'],
            $valores['cpf'],
            $valores['telefone'],
            dataParaBr($valores['dataNascimento']),
            $valores['endereco']
        );

        $sistema->cadastrarCliente($cliente);
        redirecionar(BASE_URL . '/pages/clientes.php?sucesso=cliente');
    }
}

renderHeader('Cadastro de cliente', 'clientes');
renderPageHeader('Cadastro de cliente', 'Registre os dados pessoais para permitir locações e controle de classificação indicativa.');
?>

<?php if ($erro): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= h($erro) ?></div>
<?php endif; ?>

<section class="form-surface">
    <form method="post" class="row g-3">
        <div class="col-md-8">
            <label class="form-label" for="nome">Nome completo</label>
            <input class="form-control" id="nome" name="nome" value="<?= h($valores['nome']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="cpf">CPF</label>
            <input class="form-control" id="cpf" name="cpf" value="<?= h($valores['cpf']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="telefone">Telefone</label>
            <input class="form-control" id="telefone" name="telefone" value="<?= h($valores['telefone']) ?>" placeholder="(99) 99999-9999" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="dataNascimento">Data de nascimento</label>
            <input class="form-control" type="date" id="dataNascimento" name="dataNascimento" value="<?= h($valores['dataNascimento']) ?>" required>
        </div>
        <div class="col-12">
            <label class="form-label" for="endereco">Endereço</label>
            <input class="form-control" id="endereco" name="endereco" value="<?= h($valores['endereco']) ?>" required>
        </div>
        <div class="col-12 d-flex gap-2 justify-content-end">
            <a class="btn btn-outline-secondary" href="pages/clientes.php">Cancelar</a>
            <button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-2"></i>Salvar cliente</button>
        </div>
    </form>
</section>

<?php renderFooter(); ?>
