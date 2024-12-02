<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

// Monta a query SQL
try {
    $stmt = $pdo->prepare("SELECT
        f.id_frete AS id,
        f.id_frete AS pedido,
        c.nome_cliente AS cliente,
        f.endereco_origem AS origem,
        f.endereco_destino AS destino,
        f.valor_frete AS valor,
        DATE_FORMAT(f.data_calculo, '%Y-%m-%d') AS data,
        f.status
    FROM fretes f
    JOIN clientes c ON f.id_cliente = c.id_cliente
    ORDER BY f.data_calculo DESC");

    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["pedidos" => $pedidos]);
} catch (Exception $e) {
    echo json_encode([
        "error" => true,
        "message" => "Erro ao listar pedidos: " . $e->getMessage()
    ]);
}
