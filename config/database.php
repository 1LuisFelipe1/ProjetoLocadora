<?php 
$host = 'sql202.infinityfree.com';
$banco = 'if0_42181581_diguinho_max';
$usuario = 'if0_42181581';
$senha = 'TFCUw6HyNBzg6'; 

$dsn = "mysql:host=$host;dbname=$banco;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    exit('Erro ao conectar ao banco: ' . $erro->getMessage());
}
