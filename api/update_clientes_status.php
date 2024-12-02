<?php
// Inclui a conexão com o banco
require_once 'db_connection.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['id']) || !isset($input['status'])) {
    echo json_encode(["success" => false, "message" => "Dados insuficientes."]);
    exit;
}

$clienteId = $input['id'];
$novoStatus = $input['status'];

try {
    // Cria a query SQL
    $stmt = $pdo->prepare("UPDATE clientes SET status_cliente = :novoStatus WHERE id_cliente = :clienteId");
    $stmt->bindParam(':novoStatus', $novoStatus);
    $stmt->bindParam(':clienteId', $clienteId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Status atualizado com sucesso."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao atualizar o status do cliente."]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao processar a solicitação: " . $e->getMessage()
    ]);
}
?>
