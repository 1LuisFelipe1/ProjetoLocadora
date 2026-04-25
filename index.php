<?php

/* CODIGO FEITO PARA TESTE DE SCRIPT */


require_once 'Classes/Filme.php';
require_once 'Classes/Cliente.php';
require_once 'Classes/Locacao.php';
require_once 'Classes/SistemaLocadora.php';

// Criando sistema
$sistema = new SistemaLocadora([], [], []);

// Criando filmes
$filme1 = new Filme(1, "Matrix", "Ação", 1999, "16", 3, 10.0);
$filme2 = new Filme(2, "Vingadores", "Ação", 2019, "12", 5, 12.0);

// Criando clientes
$cliente1 = new Cliente(1, "Gabriel", "12345678900", "99999-9999", "01/01/2000", "Rua A");
$cliente2 = new Cliente(2, "Maria", "98765432100", "88888-8888", "01/01/2010", "Rua B");

// Cadastrando
$sistema->cadastrarFilme($filme1);
$sistema->cadastrarFilme($filme2);

$sistema->cadastrarCliente($cliente1);
$sistema->cadastrarCliente($cliente2);

// Listando
echo "=== FILMES ===\n";
$sistema->listarFilmes();

echo "\n=== CLIENTES ===\n";
$sistema->listarClientes();

// Realizando locação
echo "\n=== LOCACAO ===\n";

if ($cliente1->podeAlugar($filme1->getClassificacao())) {
    if ($filme1->alugar()) {
        $sistema->realizarLocacao($cliente1, $filme1, 3);
        echo "Locacao realizada com sucesso!\n";
    } else {
        echo "Filme sem estoque!\n";
    }
} else {
    echo "Cliente nao pode alugar esse filme!\n";
}

// Listar locações
echo "\n=== LOCACOES ===\n";
foreach ($sistema->listarLocacoes() as $locacao) {
    $dados = $locacao->exibirDados();
    foreach ($dados as $chave => $valor) {
        echo $chave . $valor . PHP_EOL;
    }
    echo "----------------------\n";
}

// Teste de busca
echo "\n=== BUSCA FILME ===\n";
$sistema->buscarFilme(1);

echo "\n=== BUSCA CLIENTE ===\n";
$sistema->buscarCliente(1);

// Simular devolução
echo "\n=== DEVOLUCAO ===\n";
$locacoes = $sistema->listarLocacoes();

if (!empty($locacoes)) {
    $locacao = $locacoes[0];
    $dataDevolucao = new DateTime('+5 days'); // simula atraso

    $sistema->devolverLocacao($locacao, $dataDevolucao);

    echo "Locacao finalizada!\n";

    $dados = $locacao->exibirDados();
    foreach ($dados as $chave => $valor) {
        echo $chave . $valor . PHP_EOL;
    }
}

?>