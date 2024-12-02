<?php
// Inclui a conexão com o banco de dados
require_once 'db_connection.php';

// Inicia a sessão para recuperar o ID do cliente logado
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recupera o ID do cliente da sessão
        if (!isset($_SESSION['id_cliente'])) {
            throw new Exception("ID do cliente não encontrado na sessão.");
        }
        $id_cliente = $_SESSION['id_cliente'];

        // Dados recebidos do formulário
        $nome = $_POST['nome'] ?? null;
        $cpf = $_POST['cpf'] ?? null;
        $razao_social = $_POST['razao_social'] ?? null;
        $cnpj = $_POST['cnpj'] ?? null;
        $email = $_POST['email'] ?? null;
        $telefone = $_POST['telefone'] ?? null;
        $senha = $_POST['senha'] ?? null;
        $endereco = $_POST['endereco'] ?? null;
        $metodo_envio = $_POST['metodo_envio'] ?? null;
        $cidade = $_POST['cidade'] ?? null;
        $bairro = $_POST['bairro'] ?? null;
        $cep = $_POST['cep'] ?? null;

        // Validações básicas
        if (empty($nome) || empty($cpf) || empty($email) || empty($telefone)) {
            throw new Exception("Por favor, preencha todos os campos obrigatórios.");
        }

        // Verifica se a senha foi alterada
        $senha_hashed = null;
        if (!empty($senha)) {
            $senha_hashed = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a nova senha
        }

        // Monta a query SQL para atualização
        $sql = "UPDATE clientes SET
                nome_cliente = :nome,
                cpf_cliente = :cpf,
                razao_social = :razao_social,
                cnpj_cliente = :cnpj,
                email_cliente = :email,
                telefone_cliente = :telefone,
                endereco_cliente = :endereco,
                metodo_envio = :metodo_envio,
                cidade_cliente = :cidade,
                bairro_cliente = :bairro,
                cep_cliente = :cep";

        // Adiciona a senha se foi alterada
        if ($senha_hashed !== null) {
            $sql .= ", senha_cliente = :senha";
        }

        $sql .= " WHERE id_cliente = :id_cliente";

        // Prepara a consulta
        $stmt = $pdo->prepare($sql);

        // Vincula os valores
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':razao_social', $razao_social);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':metodo_envio', $metodo_envio);
        $stmt->bindParam(':cidade',$cidade);
        $stmt->bindParam(':bairro',$bairro);
        $stmt->bindParam(':cep',$cep);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);

        // Vincula a senha somente se foi alterada
        if ($senha_hashed !== null) {
            $stmt->bindParam(':senha', $senha_hashed);
        }

       // Executa a consulta
       if ($stmt->execute()) {
        $_SESSION['message'] = 'Dados atualizados com sucesso.';  // Mensagem de sucesso na sessão
        header("Location: dashboard.php#minha-conta"); // Redireciona para a página de edição
        exit;
    } else {
        throw new Exception("Erro ao atualizar os dados. Tente novamente.");
    }
} catch (Exception $e) {
    // Retorna o erro
    $_SESSION['message'] = 'Erro: ' . $e->getMessage();
    header("Location: dashboard.php#minha-conta");
    exit;
}
}

?>

