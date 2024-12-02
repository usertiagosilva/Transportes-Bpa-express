<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16"  href="assets/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

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
    <link rel="stylesheet" href="css/style_login.css">

    <!-- JS -->
    <script src="js/script_login.js" defer></script>
</head>
<body>
     <!-- Botão de Voltar -->
      <a href="index.php">
     <button class="back-button" onclick="window.history.back();">
        <i class="fas fa-arrow-left"></i> Voltar
    </button>
    </a>

    <div class="login-container">
        <div class="login-box">
            <p id="form-title">______ Acesse sua conta _______</p>
             <!-- Exibir mensagens de erro -->
             <?php
                if (isset($_SESSION['erro_login'])) {
                    echo "<div class='error-message'>{$_SESSION['erro_login']}</div>";
                    unset($_SESSION['erro_login']);
                }
                ?>

            <!-- Formulário de Login -->
            <form id="login-form" action="verify_login.php" method="POST">
                <div class="input-container">
                    <input type="email" name="email" placeholder="E-mail" required autocomplete="off">
                    <i class="fas fa-user icon-user"></i>
                </div>
                <div class="input-container">
                    <input type="password" name="senha" placeholder="Senha" required autocomplete="off">
                    <i class="fas fa-lock icon-lock"></i>
                </div>
                <a href="#" id="forgot-password" class="forgot-password">Esqueci minha senha</a>
                <button type="submit" class="login-button">Entrar</button>
            </form>

            <!-- Formulário de Redefinição de Senha (inicialmente oculto) -->
            <form id="reset-form" action="send_reset_link.php" method="POST" style="display: none;">
            <!-- Mensagens de sucesso ou erro -->
            <?php
            if (isset($_SESSION['success'])) {
                echo "<div class='success-message'>{$_SESSION['success']}</div>";
                unset($_SESSION['success']);
            }

            if (isset($_SESSION['error'])) {
                echo "<div class='error-message'>{$_SESSION['error']}</div>";
                unset($_SESSION['error']);
            }
            ?>
                <div class="input-container">
                    <input type="email" name="email" placeholder="Digite seu e-mail" required>
                    <i class="fas fa-user icon-user"></i>
                </div>
                <button type="submit" class="reset-button">Enviar link de redefinição</button>
                <a href="#" id="back-to-login" class="back-to-login">Voltar para o login</a>
            </form>

        </div>
    </div>
</body>
</html>
