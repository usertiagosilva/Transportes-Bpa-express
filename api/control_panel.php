<?php

// Incluir a conexão com o banco
require_once 'db_connection.php';

session_start();
// Verifica se o usuário está logado e se é administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php"); // Redireciona para o login se não estiver autenticado
    exit;
}

// Consultas para as métricas

// Consultar o total de clientes
$query_clientes = "SELECT COUNT(id_cliente) AS total_clientes FROM clientes";
$stmt_clientes = $pdo->query($query_clientes);
$total_clientes = $stmt_clientes->fetch(PDO::FETCH_ASSOC)['total_clientes'];

// Consulta SQL para contar os clientes cadastrados hoje
$stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes WHERE DATE(data_cadastro) = CURDATE()");
$stmt->execute();
$clientes_hoje = $stmt->fetchColumn();

// Consultar o total de pedidos de frete (todos os pedidos, sem filtro de status)
$query_pedidos_total = "SELECT COUNT(id_frete) AS total_pedidos FROM fretes";
$stmt_pedidos_total = $pdo->query($query_pedidos_total);
$total_pedidos = $stmt_pedidos_total->fetch(PDO::FETCH_ASSOC)['total_pedidos'];

// Consultar o total de pedidos de frete feitos hoje
$query_pedidos_hoje = "SELECT COUNT(id_frete) AS pedidos_hoje FROM fretes WHERE DATE(data_calculo) = CURDATE()";
$stmt_pedidos_hoje = $pdo->query($query_pedidos_hoje);
$pedidos_hoje = $stmt_pedidos_hoje->fetch(PDO::FETCH_ASSOC)['pedidos_hoje'];

// Consultar a receita total (valor total dos fretes entregues)
$query_receita_total = "SELECT SUM(valor_frete) AS receita_total FROM fretes WHERE status = 'entregue'";
$stmt_receita_total = $pdo->query($query_receita_total);
$receita_total = $stmt_receita_total->fetch(PDO::FETCH_ASSOC)['receita_total'];

// Consultar a receita de hoje
$query_receita_hoje = "SELECT SUM(valor_frete) AS receita_hoje FROM fretes WHERE status = 'entregue' AND DATE(data_calculo) = CURDATE()";
$stmt_receita_hoje = $pdo->query($query_receita_hoje);
$receita_hoje = $stmt_receita_hoje->fetch(PDO::FETCH_ASSOC)['receita_hoje'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16"  href="assets/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

     <!-- JS -->
     <script src="js/script_controlPanel.js" defer></script>
    
     <!-- Links para Bibliotecas Externas -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     
     <!-- Google fonts -->
     <link rel="preconnect" href="https://fonts.googleapis.com" />
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
     <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet" />
 
     <!-- Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/style_controlPanel.css">
</head>
<body>
<div class="container">
          <!-- Botão de Toggle -->
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

         <!-- Sidebar -->
         <nav class="sidebar" id="sidebar">
            <div class="logo">
                <img src="assets/logo-dashboard.PNG" alt="Logo">
            </div>
            <ul>
                <li><a href="#home" onclick="showSection('home')"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#dashboard" onclick="showSection('dashboard')"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="#gestao-pedidos" onclick="showSection('gestao-pedidos')"><i class="fas fa-clipboard-list"></i> Pedidos</a></li>
                <li><a href="#clientes" onclick="showSection('cadastro-clientes')"><i class="fas fa-user-plus"></i> Clientes</a></li>
                <li><a href="#configuracoes-frete" onclick="showSection('configuracoes-frete')"><i class="fas fa-truck"></i> Configurações de Frete</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
        </nav>

        <!-- Área Principal de Conteúdo -->
        <div class="main-content" id="bg">
            <header>
                <div class="header-info">
                    <h1>Boas-vindas Administrador!</h1>
                    <p>Aqui você encontra as informações necessárias para gerenciar sua empresa.</p>
                </div>
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></span>
                </div>
            </header>

             <!-- Home -->
             <section id="home" class="content-section">
                <p>Visão geral das principais métricas e funcionalidades.</p>
                
                 <!-- Cards de Resumo -->
                <div class="summary-cards">
                    <div class="card">
                        <div class="card-title">
                            <h3>Clientes</h3>
                        </div>
                        <p>Total: <strong><?php echo $total_clientes; ?></strong></p>
                        <p>Hoje: <strong><?php echo $clientes_hoje; ?></strong></p>

                    </div>
                    <div class="card">
                        <div class="card-title">
                            <h3>Receita</h3>
                        </div>
                        <p>Total: <strong>R$<?php echo number_format($receita_total, 2, ',', '.'); ?></strong></p>
                        <p>Hoje: <strong>R$<?php echo number_format($receita_hoje, 2, ',', '.'); ?></strong></p>
                    </div>
                    <div class="card">
                        <div class="card-title">
                            <h3>Pedidos</h3>
                        </div>
                        <p>Total: <strong><?php echo $total_pedidos; ?></strong></p>
                        <p>Hoje: <strong><?php echo $pedidos_hoje; ?></strong></p>
                    </div>
                </div>
            </section>

            <!-- Dashboard Geral -->
            <section id="dashboard" class="content-section" style="display: none;">
                <h2>Dashboard Geral</h2>
                <!-- Gráficos com Chart.js -->
                <div class="dashboard-cards">
                    <canvas id="freteChart" class="chart-size"></canvas>
                    <canvas id="clientesChart" class="chart-size"></canvas>
                </div>
            </section>

            <!-- Gestão de Pedidos -->
            <section id="gestao-pedidos" class="content-section" style="display: none;">
                <h2>Gestão de Pedidos e Fretes</h2>
                <input type="text" id="searchInput" placeholder="Pesquisar pedidos..." onkeyup="filterTable()">
    
                <table id="pedidosTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)" onkeypress="">Pedido</th>
                            <th onclick="sortTable(1)" onkeypress="">Cliente</th>
                            <th onclick="sortTable(2)" onkeypress="">Origem</th>
                            <th onclick="sortTable(3)" onkeypress="">Destino</th>
                            <th onclick="sortTable(4)" onkeypress="">Valor</th>
                            <th onclick="sortTable(5)" onkeypress="">Data</th>
                            <th onclick="sortTable(6)" onkeypress="">Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                         <!-- Os dados serão preenchidos dinamicamente -->
                    </tbody>
                </table>

                <div id="pagination">
                    <button onclick="prevPage()">Anterior</button>
                    <span id="pageNumber">1</span>
                    <button onclick="nextPage()">Próxima</button>
                </div>

                <!-- Modal de edição de status -->
                <div id="statusModal" class="modal">
                    <div class="modal-content">
                        <button class="close-btn" onclick="closeModal()" aria-label="Fechar modal" tabindex="0">&times;</button>
                        <h3>Atualizar Status do Pedido</h3>
                        <form id="updateStatusForm" onsubmit="updateStatus(event)">
                            <input type="hidden" id="orderId" name="orderId">
                            <label for="newStatus">Novo Status:</label>
                            <select id="newStatus" name="newStatus">
                                <option value="pendente">Pendente</option>
                                <option value="saiu_para_entrega">Saiu para entrega</option>
                                <option value="entregue">Entregue</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                            <button type="submit">Atualizar</button>
                        </form>
                    </div>
                </div>
            </section>

             <!-- Gestão de Clientes -->
            <section id="cadastro-clientes" class="content-section" style="display: none;">
                <h2>Gestão de Clientes e Usuários</h2>
                <input type="text" id="searchClienteInput" placeholder="Pesquisar clientes..." onkeyup="filterClientesTable()">
                
                <table id="clientesTable">
                    <thead>
                        <tr>
                            <th onclick="sortClientesTable(0)" onkeypress="">Cliente</th>
                            <th onclick="sortClientesTable(1)" onkeypress="">Email</th>
                            <th onclick="sortClientesTable(2)" onkeypress="">Telefone</th>
                            <th onclick="sortClientesTable(3)" onkeypress="">Razão social</th>
                            <th onclick="sortClientesTable(4)" onkeypress="">Data de Cadastro</th>
                            <th onclick="sortClientesTable(5)" onkeypress="">Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dados dos clientes serão preenchidos dinamicamente -->
                    </tbody>
                </table>

                <div id="paginationClientes">
                    <button onclick="prevClientePage()">Anterior</button>
                    <span id="clientePageNumber">1</span>
                    <button onclick="nextClientePage()">Próxima</button>
                </div>

                <!-- Modal para editar status do cliente -->
                <div id="clienteModal" class="modal">
                    <div class="modal-content">
                        <button class="close-btn" onclick="closeClienteModal()">&times;</button>
                        <h3>Editar Status do Cliente</h3>
                        <form id="updateStatusForm" onsubmit="updateClienteStatus(event)">
                            <input type="hidden" id="clienteId" />
                            <label for="clienteStatus">Novo Status:</label>
                            <select id="clienteStatus">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                            </select>
                            <button type="submit">Atualizar</button>
                        </form>
                    </div>
                </div>

            </section>

            <!-- Configurações de Frete -->
            <section id="configuracoes-frete" class="content-section" style="display: none;">
                <h2>Configurações de Frete</h2>
                <form id="configuracao-frete-form">
                    <label for="tarifa">Tarifa Base:</label>
                    <input type="number" id="tarifa" name="tarifa_base" step="0.01" placeholder="Digite a tarifa base">
                    
                    <label for="taxa">Taxa por Km:</label>
                    <input type="number" id="taxa" name="taxa_km" step="0.01" placeholder="Digite a taxa por km">
                    
                    <label for="taxaKg">Taxa por Kg:</label>
                    <input type="number" id="taxaKg" name="taxa_kg" step="0.01" placeholder="Digite a taxa por kg">
                    
                    <label for="taxaVolume">Taxa por Volume:</label>
                    <input type="number" id="taxaVolume" name="taxa_m3" step="0.01" placeholder="Digite a taxa por m3">

                    <button type="button" onclick="salvarConfiguracoesFrete()">Salvar Configurações</button>
                    <p id="mensagemConfirmacao" style="display:none; color:green; margin-top: 10px;">Configurações salvas com sucesso!</p>
                </form>
            </section>
        </div>
    </div>
</body>
</html>
