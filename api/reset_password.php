<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <!-- JS -->
    <script src="script_login.js" defer></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/style_login.css">
</head>
<body>
    <!-- Redefinir senha -->
    <div class="login-container">
        <div class="login-box">
            <p>______ Alterar sua senha ______</p>
            <form id="reset-password-form" action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="input-container">
                    <input type="password" name="new_password" id="new-password" placeholder="Nova senha" required>
                    <i class="fas fa-lock icon-lock"></i>
                </div>
                <div class="input-container">
                    <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirme a senha" required>
                    <i class="fas fa-lock icon-lock"></i>
                </div>
                <button type="submit" class="reset-button">Redefinir senha</button>
            </form>
        </div>
    </div>
</body>
</html>
