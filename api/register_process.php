<?php
// Iniciar a sessão
session_start();

// Incluir conexão com o banco de dados
include 'db_connection.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $email = trim($_POST['email']);
    $confirmEmail = trim($_POST['confirm-email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);
    $shippingMethod = trim($_POST['shipping_method']);
    $companyName = trim($_POST['company-name']);
    $cnpj = trim($_POST['cnpj']);
    $responsibleName = trim($_POST['responsible-name']);
    $cpf = trim($_POST['cpf']);
    $neighborhood = trim($_POST['neighborhood']);
    $city = trim($_POST['city']);
    $zipcode = trim($_POST['zipcode']);

    // Validar dados
    if ($email !== $confirmEmail) {
        $_SESSION['error'] = 'Os e-mails não coincidem!';
        header('Location: register.php');
        exit;
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'As senhas não coincidem!';
        header('Location: register.php');
        exit;
    }

     // Verifica se a senha atende aos critérios de segurança
     if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $_SESSION['error'] = "A senha deve ter pelo menos 8 caracteres, incluindo letras e números.";
        header("Location: register.php");
        exit();
    }

    // Validar CNPJ e CPF
    if (!preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/', $cnpj)) {
        $_SESSION['error'] = 'O CNPJ informado é inválido!';
        header('Location: register.php');
        exit;
    }

    if (!preg_match('/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', $cpf)) {
        $_SESSION['error'] = 'O CPF informado é inválido!';
        header('Location: register.php');
        exit;
    }

    // Criptografar a senha
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Inserir dados no banco utilizando PDO
    try {
        // Preparar a query
        $query = "INSERT INTO clientes (nome_cliente, email_cliente, telefone_cliente, endereco_cliente, senha_cliente, metodo_envio, razao_social, cnpj_cliente, cpf_cliente, bairro_cliente, cidade_cliente, cep_cliente)
                  VALUES (:responsible_name, :email, :phone, :address, :password, :shipping_method, :company_name, :cnpj, :cpf, :neighborhood, :city, :zipcode)";

        $stmt = $pdo->prepare($query);

        // Substituir os parâmetros pelos dados do formulário
        $stmt->bindParam(':responsible_name', $responsibleName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':shipping_method', $shippingMethod);
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':neighborhood', $neighborhood);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':zipcode', $zipcode);

        // Executar a query
        $stmt->execute();

        // Obter o ID do cliente recém-cadastrado
        $clientId = $pdo->lastInsertId();

        // Criar sessão para autenticar o cliente
        $_SESSION['cliente_id'] = $clientId;
        $_SESSION['cliente_nome'] = $responsibleName;
        $_SESSION['cliente_email'] = $email;

        // Redirecionar para a dashboard
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $e) {
        // Lidar com erros
        $_SESSION['error'] = 'Erro ao processar o cadastro: ' . $e->getMessage();
        header('Location: register.php');
        exit;
    }
} else {
    // Redirecionar caso o acesso seja direto
    header('Location: dashboard.php');
    exit;
}
?>
