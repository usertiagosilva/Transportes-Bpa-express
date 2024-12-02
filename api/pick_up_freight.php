<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
include 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

// Defina o cabeçalho para JSON
header('Content-Type: application/json');

try {
    $id_cliente = $_SESSION['id_cliente']; // ID do cliente logado

    // Consultando os fretes associados ao usuário
    $query = "SELECT id_frete, endereco_origem, endereco_destino, valor_frete, data_calculo AS data, status
              FROM fretes
              WHERE id_cliente = :id_cliente"; // Consulta os fretes relacionados ao cliente
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();

    // Obtendo os resultados
    $fretes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verifica se existem fretes para o cliente
    if ($fretes) {
        // Adicionando status legível para o frontend, se necessário
        foreach ($fretes as &$frete) {
            switch ($frete['status']) {
                case 'pendente':
                    $frete['status_legivel'] = 'Pendente';
                    break;
                case 'saiu_para_entrega':
                    $frete['status_legivel'] = 'Saiu para entrega';
                    break;
                case 'entregue':
                    $frete['status_legivel'] = 'Entregue';
                    break;
                case 'cancelado':
                    $frete['status_legivel'] = 'Cancelado';
                    break;
                default:
                    $frete['status_legivel'] = 'Status desconhecido';
            }
        }

        echo json_encode($fretes);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nenhum frete encontrado para este cliente.']);
    }
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => "Erro ao buscar os dados dos fretes: " . $e->getMessage()
    ]);
}
?>
