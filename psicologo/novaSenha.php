<?php
// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "psiconecta");
if ($conn->connect_error) {
    die("Erro ao conectar: " . $conn->connect_error);
}

// Dados vindos do formulário
$crp = $_POST['crp'];
$novaSenha = $_POST['novaSenha'];
$confirmaSenha = $_POST['senha'];

// Verificar se os campos foram preenchidos
if (empty($crp) || empty($novaSenha) || empty($confirmaSenha)) {
    die("Por favor, preencha todos os campos.");
}

// Verificar se as senhas coincidem
if ($novaSenha !== $confirmaSenha) {
    die("As senhas não conferem.");
}

// Verificar se o CRP existe no banco
$sql = "SELECT * FROM psicologo WHERE crp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $crp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("CRP não encontrado no sistema.");
}

// Gerar novo hash da senha
$novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);

// Atualizar a senha no banco
$update = $conn->prepare("UPDATE psicologo SET senha = ? WHERE crp = ?");
$update->bind_param("ss", $novoHash, $crp);

if ($update->execute()) {
    header("Location: entrar.html");
} else {
    echo "Erro ao atualizar a senha: " . $conn->error;
}

// Fechar conexões
$update->close();
$stmt->close();
$conn->close();
?>