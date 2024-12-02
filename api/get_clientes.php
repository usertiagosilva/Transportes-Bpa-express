<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

try {
    // Monta a query SQL
    $stmt = $pdo->query("SELECT id_cliente, nome_cliente, email_cliente, telefone_cliente, razao_social, data_cadastro, status_cliente FROM clientes");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($clientes);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erro ao carregar dados: " . $e->getMessage()]);
}
?>
