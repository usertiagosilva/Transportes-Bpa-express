<?php
// Configurações de conexão
$host = "localhost";        // Servidor MySQL
$dbname = "bpa_express";    // Nome do banco de dados
$username = "root";         // Nome de usuário do banco de dados
$password = "";             // Senha do banco de dados

try {
    // Criando uma nova instância de conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurando o modo de erro do PDO
    // echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}
?>
