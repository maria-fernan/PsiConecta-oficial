<?php
session_start(); 

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "psiconecta");
if ($conn->connect_error) {
    die("Erro ao conectar: " . $conn->connect_error);
}

// Pegando dados do formulário
$crp = $_POST['crp'];
$senha = $_POST['senha'];

// Consulta segura
$sql = "SELECT * FROM psicologo WHERE crp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $crp);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: naoUsuario.html");  // CRP não existe
    exit;
}

$row = $result->fetch_assoc();
$hashDoBanco = $row['senha'];

if (!password_verify($senha, $hashDoBanco)) {
    header("Location: senhaIncorreta.html");
    exit;
}

// Verificações do status
if ($row['validado'] == 0) {
    header("Location: verificandoCadastro.html");
    exit;
}

if ($row['validado'] == 2) {
    header("Location: cadastroRecusado.html");
    exit;
}

// Usuário ok → salva sessão
$_SESSION['crp'] = $row['crp'];
$_SESSION['nome'] = $row['nome'];

header("Location: pagPrincipal.php");
exit;
?>