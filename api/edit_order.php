<?php
// Conectar ao banco de dados
include 'db_connection.php';

// Atualizar frete no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar os dados recebidos e atualizar o banco de dados
    if ($data) {
        $id_frete = $data['id_frete'];
        $endereco_origem = $data['endereco_origem'];
        $endereco_destino = $data['endereco_destino'];
        $altura = $data['altura'];
        $largura = $data['largura'];
        $comprimento = $data['comprimento'];
        $peso = $data['peso'];
        $valor_frete = $data['valor_frete'];

        // Conectar ao banco de dados e executar a atualização
        $sql = "UPDATE fretes SET endereco_origem = :endereco_origem, endereco_destino = :endereco_destino, altura = :altura, largura = :largura, comprimento = :comprimento, peso = :peso, valor_frete = :valor_frete WHERE id_frete = :id_frete";
        
        // Preparar a consulta
        $stmt = $pdo->prepare($sql);

        // Vincular os parâmetros aos valores
        $stmt->bindParam(':endereco_origem', $endereco_origem, PDO::PARAM_STR);
        $stmt->bindParam(':endereco_destino', $endereco_destino, PDO::PARAM_STR);
        $stmt->bindParam(':altura', $altura, PDO::PARAM_STR);
        $stmt->bindParam(':largura', $largura, PDO::PARAM_STR);
        $stmt->bindParam(':comprimento', $comprimento, PDO::PARAM_STR);
        $stmt->bindParam(':peso', $peso, PDO::PARAM_STR);
        $stmt->bindParam(':valor_frete', $valor_frete, PDO::PARAM_STR);
        $stmt->bindParam(':id_frete', $id_frete, PDO::PARAM_INT);

        // Executar a consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
?>
