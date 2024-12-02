<?php
session_start();

// Conexão com o banco de dados
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'bpa_express';

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Função para retornar mensagens de erro
function redirecionarComErro($mensagem) {
    $_SESSION['erro_login'] = $mensagem;
    header("Location: login.php");
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitiza e valida os dados recebidos
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirecionarComErro("E-mail inválido.");
    }

    // Verifica na tabela 'usuarios' se o e-mail está cadastrado
    $stmt = $conn->prepare("SELECT id_usuario, nome_usuario, senha_usuario, tipo_usuario FROM usuarios WHERE email_usuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuário encontrado na tabela 'usuarios'
        $usuario = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario['senha_usuario'])) {
            // Define as sessões para o usuário logado
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['nome_usuario'] = $usuario['nome_usuario'];

            // Redireciona para o painel de administração
            header("Location: control_panel.php");
            exit;
        } else {
            redirecionarComErro("Senha incorreta.");
        }
    } else {
        // Caso o e-mail não seja encontrado na tabela 'usuarios', verifica na tabela 'clientes'
        $stmt = $conn->prepare("SELECT id_cliente, nome_cliente, senha_cliente FROM clientes WHERE email_cliente = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Cliente encontrado na tabela 'clientes'
            $cliente = $result->fetch_assoc();

            // Verifica a senha
            if (password_verify($senha, $cliente['senha_cliente'])) {
                // Define as sessões para o cliente logado
                $_SESSION['id_cliente'] = $cliente['id_cliente'];
                $_SESSION['nome_cliente'] = $cliente['nome_cliente'];

                // Redireciona para o painel do cliente
                header("Location: dashboard.php");
                exit;
            } else {
                redirecionarComErro("Senha incorreta.");
            }
        } else {
            redirecionarComErro("E-mail não cadastrado.");
        }
    }

    $stmt->close();
} else {
    // Se o método não for POST, redireciona para a página de login
    header("Location: login.php");
    exit;
}
$conn->close();
?>
