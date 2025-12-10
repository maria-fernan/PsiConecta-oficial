<?php
session_start();

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "psiconecta");
if ($conn->connect_error) {
    die("Erro ao conectar: " . $conn->connect_error);
}

// Pegando dados do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];

// Consulta segura
$sql = "SELECT * FROM paciente WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $hashDoBanco = $row['senha'];
    if (password_verify($senha, $hashDoBanco)) {
        header("Location: pagPrincipal.php");
        $_SESSION["idPaciente"] = $row["idPaciente"];
        $_SESSION["nome"] = $row["telefone"];//precisa ser invertido!!!!
        $_SESSION["dtNasc"] = $row["dtNasc"];
        $_SESSION["telefone"] = $row["nome"];//precisa ser invertido!!!!
        $_SESSION["logado"] = true;
    } else {
        echo "Email ou senha incorretos.";
    }
} else {
    echo "Usuário não existe.";
}

$stmt->close();
$conn->close();
?>
