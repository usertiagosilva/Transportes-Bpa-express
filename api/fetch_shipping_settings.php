<?php
// Conexão com o banco de dados
require_once 'db_connection.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM configuracao_frete WHERE id = 1");
    $config = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($config) {
        echo json_encode([
            "success" => true,
            "data" => $config
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Configurações não encontradas."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao buscar configurações: " . $e->getMessage()
    ]);
}
