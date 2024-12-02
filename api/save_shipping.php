<?php
session_start();

// Conexão com o banco de dados
require 'db_connection.php';

// Defina o cabeçalho para JSON
header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

$id_cliente = $_SESSION['id_cliente']; // ID do cliente logado

// Recebe os dados enviados em JSON
$data = json_decode(file_get_contents('php://input'), true);


if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

// Extrai os dados do JSON e valida
$endereco_origem = $data['pickup'] ?? null;
$endereco_destino = $data['delivery'] ?? null;
$altura = isset($data['height']) ? (float)$data['height'] : 0;
$largura = isset($data['width']) ? (float)$data['width'] : 0;
$comprimento = isset($data['length']) ? (float)$data['length'] : 0;
$peso = isset($data['weight']) ? (float)$data['weight'] : 0;
$valor_frete = isset($data['freightValue']) ? (float)$data['freightValue'] : 0.0;

// Verifica se todos os dados obrigatórios estão presentes
if (is_null($endereco_origem) || is_null($endereco_destino) || $peso <= 0 || $valor_frete <= 0) {
    echo json_encode(['success' => false, 'message' => 'Dados faltando ou inválidos.']);
    exit;
}

// Insere os dados na tabela fretes usando PDO
try {
    // Prepara a consulta de inserção
    $sql = "INSERT INTO fretes (id_cliente, endereco_origem, endereco_destino, altura, largura, comprimento, peso, valor_frete)
            VALUES (:id_cliente, :endereco_origem, :endereco_destino, :altura, :largura, :comprimento, :peso, :valor_frete)";
    
    $stmt = $pdo->prepare($sql);

    // Vincula os parâmetros
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->bindParam(':endereco_origem', $endereco_origem, PDO::PARAM_STR);
    $stmt->bindParam(':endereco_destino', $endereco_destino, PDO::PARAM_STR);
    $stmt->bindParam(':altura', $altura, PDO::PARAM_STR);
    $stmt->bindParam(':largura', $largura, PDO::PARAM_STR);
    $stmt->bindParam(':comprimento', $comprimento, PDO::PARAM_STR);
    $stmt->bindParam(':peso', $peso, PDO::PARAM_STR);
    $stmt->bindParam(':valor_frete', $valor_frete, PDO::PARAM_STR);

    // Executa a consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Frete salvo com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar os dados.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao comunicar com o banco de dados: ' . $e->getMessage()]);
}
?>
