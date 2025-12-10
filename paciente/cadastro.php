<?php

$senha = $_POST['senha'];
$confirmaSenha = $_POST['confirma_senha'];

if ($senha === $confirmaSenha) {
    // Conexão com o banco (orientado a objeto)
$conn = new mysqli("localhost", "root", "", "psiconecta");

// Verifica se deu erro na conexão
if ($conn->connect_error) {
    die("Erro ao conectar: " . $conn->connect_error);
}

// Pegando os dados do formulário
$email = $_POST['email'];
$nome = $_POST['nome'];
$dtNasc = $_POST['data'];
$senha = $_POST['senha'];
$telefone = $_POST['telefone'];

// 1. Verificar se o email já existe
$sql = "SELECT idPaciente FROM paciente WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: novoCadastro.html");
} else {
    // 2. Gerar hash da senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // 3. Inserir paciente
    $sql = "INSERT INTO paciente (email, nome, dtNasc, senha, telefone) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $email, $nome, $dtNasc, $senhaHash, $telefone);

    if ($stmt->execute()) {
    // Pega o ID gerado
    $idPaciente = $stmt->insert_id;

    // 4. CRIA SESSÃO AUTOMÁTICA APÓS CADASTRO
    session_start();
    $_SESSION['idPaciente'] = $idPaciente;
    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;
    $_SESSION['logado'] = true;

    // 5. Redireciona para página de sucesso (ou para o painel do paciente)
    header("Location: cadastroSucesso.html");
    exit;
} else {
        echo "Erro ao cadastrar paciente: " . $conn->error;
    }
}

// Fecha conexões
$stmt->close();
$conn->close();
} else {
    header("Location: novoCadastroSenha.html");
}


?>