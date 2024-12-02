<?php
// Iniciar a sessão
session_start();

require_once 'db_connection.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    header("Location: login.php");
    exit;
}

// Recupera o ID do cliente logado
$cliente_id = $_SESSION['id_cliente'];

// Consulta para buscar os dados do cliente
$query = "SELECT * FROM clientes WHERE id_cliente = :id_cliente";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_cliente', $cliente_id, PDO::PARAM_INT);
$stmt->execute();

// Busca os dados do cliente
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o cliente existe
if (!$cliente) {
    echo "Cliente não encontrado!";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16"  href="assets/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

     <!-- API google -->
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARLiv-4fC4XefW7g533SI5Mbwr2hQZYaU"></script>

     <!-- JS -->
     <script src="js/script_dashboard.js" defer></script>
 
     <!-- Biblioteca animação -->
     <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
     <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
 
     <!-- Google fonts -->
     <link rel="preconnect" href="https://fonts.googleapis.com" />
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
     <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet" />
 
     <!-- Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/style_dashboard.css">
</head>
<body>
    <div class="container">
         <!-- Botão de Toggle -->
         <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

         <!-- Menu Lateral -->
         <nav class="sidebar" id="sidebar">
            <div class="logo">
                <img src="assets/logo-dashboard.PNG" alt="Logo">
            </div>
            <ul>
                <li><a href="#home" onclick="showSection('home')"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#meus-envios" onclick="showSection('meus-envios')"><i class="fas fa-box"></i> Meus Envios</a></li>
                <li><a href="#simular-frete" onclick="showSection('simular-frete')"><i class="fas fa-truck"></i> Simular Frete</a></li>
                <li><a href="#minha-conta" onclick="showSection('minha-conta')"><i class="fas fa-user"></i> Minha Conta</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
        </nav>

        <!-- Conteúdo Principal -->
        <div class="main-content">
            <header>
                <div class="header-info">
                    <h1>Boas-vindas à BPA EXPRESS!</h1>
                    <p>Aqui você encontra as informações necessárias para gerenciar seus envios.</p>

                    <!-- Verifica se existe uma mensagem na sessão -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert" id="alertMessage">
                        <?php
                        // Exibe a mensagem
                        echo $_SESSION['message'];
                        // Apaga a mensagem após exibi-la
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- Usuário logado -->
                </div>
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['nome_cliente']); ?></span>
                </div>
            </header>

             <!-- Home -->
             <section id="home" class="content-section">
                <h2>Como fazer seu primeiro envio?</h2>
                <p>Facilitamos o envio de suas encomendas com rapidez e segurança.</p>
                
                <div class="options-container">
                    <!-- Simular Frete -->
                    <div class="option">
                        <img src="assets/simule-seu-frete.PNG" alt="Simular Frete">
                        <h3>Simule seu Frete</h3>
                        <p>Calcule o valor do frete de acordo com a origem, destino e peso da sua encomenda.</p>
                    </div>
                    <!-- Acompanhar Entrega -->
                    <div class="option">
                        <img src="assets/acompanhe-entrega.PNG" alt="Acompanhar Entrega">
                        <h3>Acompanhe a Entrega</h3>
                        <p>Acompanhe o status da entrega em tempo real.</p>
                    </div>
                </div>
            </section>
            
           <!-- Meus Envios -->
            <section id="meus-envios" class="content-section" style="display: none;">
                <h2>Gestão de Envios</h2>
                <input type="text" id="enviosSearchInput" placeholder="Pesquisar envios..." onkeyup="filterEnviosTable()" class="search-input">
                <table id="enviosTable">
                    <thead>
                        <tr>
                            <th onclick="sortEnviosTable(0)" onkeypress="">Pedido</th>
                            <th onclick="sortEnviosTable(1)" onkeypress="">Origem</th>
                            <th onclick="sortEnviosTable(2)" onkeypress="">Destino</th>
                            <th onclick="sortEnviosTable(3)" onkeypress="">Valor</th>
                            <th onclick="sortEnviosTable(4)" onkeypress="">Data de Envio</th>
                            <th onclick="sortEnviosTable(5)" onkeypress="">Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="envios-list">
                        <!-- Os dados serão preenchidos dinamicamente -->
                    </tbody>
                </table>

                <div id="enviosPagination" class="pagination">
                    <button onclick="prevEnviosPage()" class="pagination-btn">Anterior</button>
                    <span id="enviosPageNumber">1</span>
                    <button onclick="nextEnviosPage()" class="pagination-btn">Próxima</button>
                </div>
            </section>

            <!-- Simulação de Frete -->
            <section  id="simular-frete" class="frete-simulacao content-section" style="display:none;">
                <h2>Simule o frete e faça o seu envio</h2>
                <form id="freightForm">
                    <div class="form-group">
                        <label for="origem">Origem:</label>
                        <input type="text" id="pickupAddress" name="pickup" placeholder="Rua exemplo, 000 - bairro, Curitiba-PR" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="destino">Destino:</label>
                        <input type="text" id="deliveryAddress" name="delivery" placeholder="Rua exemplo, 000 - bairro, Curitiba-PR" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="altura">Altura (cm):</label>
                        <input type="number" id="height" placeholder="Altura" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="largura">Largura (cm):</label>
                        <input type="number" id="width" placeholder="Largura" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="comprimento">Comprimento (cm):</label>
                        <input type="number" id="length" placeholder="Comprimento" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="peso">Peso (kg):</label>
                        <input type="number" id="weight" name="weight" placeholder="Peso da Carga (kg)" required>
                    </div>
                    
                    <button type="submit" class="btn-calcular">Calcular Frete</button>
                </form>
                
                <div id="result" class="result"></div>

                <!-- Orçamento do frete -->
                <div class="result-freight" id="resultadoFrete" style="display:none;">
                    <h3>Detalhes do Seu Frete</h3>
                    <p><strong>Endereço de Coleta:</strong> <span id="enderecoColeta"></span></p>
                    <p><strong>Endereço de Entrega:</strong> <span id="enderecoEntrega"></span></p>
                    <p><strong>Distância:</strong> <span id="distancia"></span> km</p>
                    <p><strong>Peso da Carga:</strong> <span id="pesoCarga"></span> kg</p>
                    <p><strong>Valor do Frete:</strong> R$ <span id="valorFrete"></span></p>
                    <button class="button-result-freight" id="enviarOrcamento" type="submit">Solicitar frete</button>
                    <button class="button-result-freight" id="salvarEdicao" type="button">Salvar Alterações</button>
                    <button class="button-result-freight" id="cancelarEdicao" type="button">Cancelar</button>
                </div>
            </section>

            <!-- Minha Conta -->
            <section id="minha-conta" class="conta-simulacao content-section" style="display:none;">
                <h2>Configurações da sua Conta</h2>
                <form class="conta-form" id="updateForm" action="edit_registration.php" method="POST">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome_cliente']); ?>" placeholder="Digite seu nome completo" required>
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cliente['cpf_cliente']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="company-name">Razão Social:</label>
                        <input type="text" id="company-name" name="razao_social" value="<?php echo htmlspecialchars($cliente['razao_social']); ?>" placeholder="Razão Social">
                    </div>
                    <div class="form-group">
                        <label for="cnpj">CNPJ:</label>
                        <input type="text" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($cliente['cnpj_cliente']); ?>" placeholder="CNPJ da Empresa">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email_cliente']); ?>" placeholder="Digite seu melhor e-mail" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-email">Confirme seu e-mail:</label>
                        <input type="email" id="confirm-email" name="confirm_email" value="<?php echo htmlspecialchars($cliente['email_cliente']); ?>" placeholder="Confirme seu e-mail" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefone:</label>
                        <input type="tel" id="phone" name="telefone" value="<?php echo htmlspecialchars($cliente['telefone_cliente']); ?>" placeholder="Telefone com DDD" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" id="password" name="senha" placeholder="Crie uma senha" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirme sua senha:</label>
                        <input type="password" id="confirm-password" name="confirm_senha" placeholder="Confirme a senha">
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP:</label>
                        <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($cliente['cep_cliente']); ?>" placeholder="CEP da empresa">
                    </div>
                    <div class="form-group">
                        <label for="address">Endereço:</label>
                        <input type="text" id="address" name="endereco" value="<?php echo htmlspecialchars($cliente['endereco_cliente']); ?>" placeholder="Endereço da Empresa" required>
                    </div>
                    <div class="form-group">
                        <label for="neighborhood">Bairro:</label>
                        <input type="text" id="neighborhood" name="bairro" value="<?php echo htmlspecialchars($cliente['bairro_cliente']); ?>" placeholder="Bairro" required>
                    </div>
                    <div class="form-group">
                        <label for="city">Cidade:</label>
                        <input type="text" id="city" name="cidade" value="<?php echo htmlspecialchars($cliente['cidade_cliente']); ?>" placeholder="Cidade" required>
                    </div>
                    <div class="form-group radio-group">
                        <p>Como você faz seus envios?</p>
                        <div class="radio-container">
                            <label><input type="radio" name="metodo_envio" value="declaration" <?php echo ($cliente['metodo_envio'] == 'declaration') ? 'checked' : ''; ?> required> Declaração de Conteúdo</label>
                            <label><input type="radio" name="metodo_envio" value="invoice" <?php echo ($cliente['metodo_envio'] == 'invoice') ? 'checked' : ''; ?>> Nota Fiscal</label>
                            <label><input type="radio" name="metodo_envio" value="either" <?php echo ($cliente['metodo_envio'] == 'either') ? 'checked' : ''; ?>> Ambos</label>
                        </div>
                    </div>
                    <button type="submit" class="btn-salvar"><i class="fas fa-save"></i> Salvar</button>
                    <p id="feedback" class="feedback-message" style="display: none; color: green;">Dados atualizados com sucesso!</p>
                </form>
            </section>
        </div>
    </div>
</body>
</html>
