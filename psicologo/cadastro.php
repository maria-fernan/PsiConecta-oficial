<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "psiconecta") 
or die("Erro ao conectar: " . mysqli_error($conn));

$nome          = $_POST['nome'];
$cpf           = $_POST['cpf'];
$dtNasc        = $_POST['data'];
$email         = $_POST['email'];
$senha         = $_POST['senha'];
$crp           = trim($_POST['crp']);
$telefone      = $_POST['telefone'];
$area_atuacao  = $_POST['area_atuacao'];
$tempo_atuacao = $_POST['tempo_atuacao'];

// Verifica duplicidade de CRP ou Email
$sql = "SELECT * FROM psicologo WHERE crp = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $crp, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: novoCadastro.html");
    exit;
}

// Cria hash da senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Inserir psicólogo com os novos campos
$sql = "INSERT INTO psicologo (nome, cpf, dtNasc, email, senha, crp, area_atuacao, tempo_atuacao, telefone) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql); 
$stmt->bind_param("sssssssss", $nome, $cpf, $dtNasc, $email, $senhaHash, $crp, $area_atuacao, $tempo_atuacao, $telefone);
$stmt->execute();

// Inserir status inicial OFFLINE
$sqlStatus = "INSERT INTO on_line (crp, status) VALUES (?, 'offline')";
$stmtStatus = $conn->prepare($sqlStatus);
$stmtStatus->bind_param("s", $crp);
$stmtStatus->execute();

// Cria sessão para o psicólogo logado
$_SESSION['crp'] = $crp;

// Redireciona para página de verficação
header("Location: verificandoCadastro.html");
exit;

$stmt->close();
$conn->close();
?>
