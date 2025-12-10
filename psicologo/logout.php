<?php
session_start();

// Se não existir CRP na sessão, apenas destrói e volta para a página principal
if (!isset($_SESSION['crp'])) {
    session_destroy();
    header("Location: ../geral/pagPrincipal.html");
    exit();
}

$crp = $_SESSION['crp'];

// Conecta ao banco
$mysqli = new mysqli("localhost", "root", "", "PsiConecta");
if ($mysqli->connect_errno) {
    die("Erro ao conectar: " . $mysqli->connect_error);
}

// Atualiza o status para offline
$stmt = $mysqli->prepare("UPDATE on_line SET status = 'offline' WHERE crp = ?");
$stmt->bind_param("s", $crp);
$stmt->execute();
$stmt->close();

// Destroi a sessão
session_destroy();

// Redireciona para a página principal
header("Location: ../geral/pagPrincipal.html");
exit();
?>
