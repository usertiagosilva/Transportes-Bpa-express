<?php
// Inclui a conexão com o banco
require_once 'db_connection.php';

header('Content-Type: application/json');

// Decodifica os dados recebidos
$input = json_decode(file_get_contents("php://input"), true);

if (empty($input['id_frete']) || empty($input['novo_status'])) {
    echo json_encode(["success" => false, "message" => "Dados inválidos recebidos."]);
    exit;
}

$id_frete = $input['id_frete'];
$novo_status = $input['novo_status'];


try {
    // Atualiza o status no banco de dados
    $stmt = $pdo->prepare("UPDATE fretes SET status = :novo_status WHERE id_frete = :id_frete");
    $stmt->bindParam(':novo_status', $novo_status);
    $stmt->bindParam(':id_frete', $id_frete);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Status atualizado com sucesso."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao atualizar o status do pedido."]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao processar a solicitação: " . $e->getMessage()
    ]);
}
