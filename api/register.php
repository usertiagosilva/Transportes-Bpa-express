<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16"  href="assets/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

     <!-- JS -->
     <script src="js/script_register.js" defer></script>
 
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
     <!-- Botão de Voltar -->
     <a href="index.php">
        <button class="back-button" onclick="window.history.back();">
           <i class="fas fa-arrow-left"></i> Voltar
       </button>
       </a>
       
     <!-- Exibição de Erros -->
     <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Formulário de Cadastro -->
    <section class="registration">
        <div class="container">
            <h2>Cadastro na BPA Express</h2>
            
            <!-- Barra de Progresso -->
            <div class="progress-bar">
                <div class="progress" id="progress"></div>
                <div class="progress-step progress-step-active" data-step="1"></div>
                <div class="progress-step" data-step="2"></div>
                <div class="progress-step" data-step="3"></div>
            </div>

            <form id="registrationForm" action="register_process.php" method="POST">
                <!-- Etapa 1 -->
                <div class="form-step" id="step1">
                    <h3>Informações de usuário</h3>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Digite seu melhor e-mail" required aria-required="true">
                    </div>
                    <div class="form-group">
                        <input type="email" id="confirm-email" name="confirm-email" placeholder="Confirme seu e-mail" required aria-required="true">
                    </div>
                    <div class="form-group">
                        <input type="tel" id="phone" name="phone" placeholder="Telefone com DDD" required aria-required="true">
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Crie uma senha" required aria-required="true">
                    </div>
                    <div class="form-group">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirme a senha" required aria-required="true">
                    </div>
                    <button type="button" class="registration-button" onclick="nextStep(2)">Avançar</button>
                </div>

                <!-- Etapa 2 -->
                <div class="form-step" id="step2" style="display: none;">
                    <h3>Método de Envio</h3>
                    <div class="form-group radio-group">
                        <p>Como você faz seus envios?</p>
                        <div class="radio-container">
                            <label><input type="radio" name="shipping_method" value="declaration" required> Declaração de Conteúdo</label>
                            <label><input type="radio" name="shipping_method" value="invoice"> Nota Fiscal</label>
                            <label><input type="radio" name="shipping_method" value="either"> Ambos</label>
                        </div>
                    </div>
                    <button type="button" class="registration-button" onclick="previousStep(1)">Voltar</button>
                    <button type="button" class="registration-button" onclick="nextStep(3)">Avançar</button>
                </div>

                <!-- Etapa 3 -->
                <div class="form-step" id="step3" style="display: none;">
                    <h3>Cadastro Empresarial</h3>
                    <div class="form-group">
                        <input type="text" id="company-name" name="company-name" placeholder="Razão Social" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="cnpj" name="cnpj" placeholder="CNPJ da Empresa" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="responsible-name" name="responsible-name" placeholder="Nome do Responsável" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="cpf" name="cpf" placeholder="CPF do Responsável" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="address" name="address" placeholder="Endereço da Empresa" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="neighborhood" name="neighborhood" placeholder="Bairro" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="city" name="city" placeholder="Cidade" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="zipcode" name="zipcode" placeholder="CEP" required>
                    </div>
                    <button type="button" class="registration-button" onclick="previousStep(2)">Voltar</button>
                    <button type="submit" class="registration-button">Cadastrar</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-icons">
            <a href="#"><i class="fa-brands fa-instagram fa-2x"></i></a>
            <a href="#"><i class="fa-brands fa-facebook fa-2x"></i></a>
        </div>
        <div class="footer-logo">
            <img src="assets/default_transparent_765x625.png" alt="Logo BPA Express">
        </div>
        <p>&copy; 2024 BPA Express - Todos os direitos reservados.</p>
    </footer>
</body>
</html>
