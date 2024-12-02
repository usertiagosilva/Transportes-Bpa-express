<?php
// Inclui a conexão com o banco
require_once 'db_connection.php';

header('Content-Type: application/json');

// Obtém o corpo da requisição
$input = file_get_contents("php://input");

// Decodifica o JSON recebido
$data = json_decode($input, true);

// Verifica erros na decodificação do JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao processar JSON recebido."
    ]);
    exit;
}

// Valida se todos os campos necessários foram enviados
if (!isset($data['tarifa_base'], $data['taxa_km'], $data['taxa_kg'], $data['taxa_m3'])) {
    echo json_encode([
        "success" => false,
        "message" => "Dados insuficientes."
    ]);
    exit;
}

// Atribui os dados às variáveis
$tarifaBase = $data['tarifa_base'];
$taxaKm = $data['taxa_km'];
$taxaKg = $data['taxa_kg'];
$taxaM3 = $data['taxa_m3'];

try {
    // Verifica se já existe uma configuração na tabela
    $checkStmt = $pdo->query("SELECT COUNT(*) AS total FROM configuracao_frete");
    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
    $exists = $row['total'] > 0;

    if ($exists) {
        // Atualiza o registro existente
        $stmt = $pdo->prepare("UPDATE configuracao_frete SET tarifa_base = ?, taxa_km = ?, taxa_kg = ?, taxa_m3 = ? WHERE id = 1");
        $stmt->execute([$tarifaBase, $taxaKm, $taxaKg, $taxaM3]);

        echo json_encode([
            "success" => true,
            "message" => $stmt->rowCount() > 0 ? "Configurações atualizadas com sucesso." : "Nenhuma alteração foi feita."
        ]);
    } else {
        // Insere os dados na tabela
        $stmt = $pdo->prepare("INSERT INTO configuracao_frete (tarifa_base, taxa_km, taxa_kg, taxa_m3) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tarifaBase, $taxaKm, $taxaKg, $taxaM3]);

        echo json_encode([
            "success" => true,
            "message" => "Configurações salvas com sucesso.",
        ]);
    }
} catch (Exception $e) {
    // Em caso de erro, retorna uma mensagem ao cliente
    echo json_encode([
        "success" => false,
        "message" => "Erro ao salvar configurações no banco de dados."
    ]);
}
