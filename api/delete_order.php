<?php
// Conectar ao banco de dados
include 'db_connection.php';

try {
    // Obter o ID do envio a ser cancelado
    $id_frete = $_POST['id_frete'];

    if (empty($id_frete)) {
        echo json_encode(["success" => false, "message" => "O ID do envio é obrigatório."]);
        exit;
    }

    // Atualizar o status do envio para "Cancelado"
    $query = "UPDATE fretes SET status = 'Cancelado' WHERE id_frete = :id_frete";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_frete', $id_frete, PDO::PARAM_INT);

    header('Content-Type: application/json');

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pedido cancelado com sucesso!"], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao cancelar o Pedido."], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erro inesperado: " . $e->getMessage()]);
}
?>
