<?php
// Conexão
$conn = new mysqli("localhost","root","","psiconecta");
if ($conn->connect_error) {
    die("Erro ao conectar: " . $conn->connect_error);
}

// Dados vindos do formulário
$crp       = $_POST['crp'];
$novaSenha = $_POST['novaSenha'];
$confirma  = $_POST['novaSenhaConf'];

// Verifica se senhas conferem
if ($novaSenha !== $confirma) {
    die("A nova senha e a confirmação não conferem.");
}

// Gera novo hash
$novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);

// Atualiza no banco
$sql = "UPDATE psicologo SET senha = ?, reset_token=NULL, reset_expira=NULL WHERE crp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $novoHash, $crp);

if ($stmt->execute()) {
    echo "Senha atualizada com sucesso!";
} else {
    echo "Erro ao atualizar senha: " . $conn->error;
}

$stmt->close();
$conn->close();
?>