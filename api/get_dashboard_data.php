<?php
require 'db_connection.php';

$data = [];

// Array base com 12 meses (inicialmente com valores zero)
$pedidos = array_fill(0, 12, 0);
$clientes = array_fill(0, 12, 0);

// Pedidos por mês
$pedidosQuery = $pdo->query("SELECT MONTH(data_calculo) AS mes, COUNT(*) AS total FROM fretes GROUP BY MONTH(data_calculo)");
while ($row = $pedidosQuery->fetch(PDO::FETCH_ASSOC)) {
    $pedidos[(int)$row['mes'] - 1] = (int)$row['total']; // Atualiza o mês correto
}

// Clientes por mês
$clientesQuery = $pdo->query("SELECT MONTH(data_cadastro) AS mes, COUNT(*) AS total FROM clientes GROUP BY MONTH(data_cadastro)");
while ($row = $clientesQuery->fetch(PDO::FETCH_ASSOC)) {
    $clientes[(int)$row['mes'] - 1] = (int)$row['total']; // Atualiza o mês correto
}

// Retornar os dados como JSON
$data['pedidosPorMes'] = $pedidos;
$data['clientesPorMes'] = $clientes;

header('Content-Type: application/json');
echo json_encode($data);
