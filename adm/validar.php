<?php
// mostrar erros só em desenvolvimento
ini_set('display_errors',1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'psiconecta';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}
$conn->set_charset('utf8mb4');

// Ler e validar parâmetros
$crp  = isset($_GET['crp']) ? trim($_GET['crp']) : '';
$acao = isset($_GET['acao']) ? intval($_GET['acao']) : 0;

// Verificações básicas
if ($crp === '' || ($acao !== 1 && $acao !== 2)) {
    // Parâmetros inválidos — volta para admin com mensagem
    header('Location: admin.php?msg=' . urlencode('Parâmetros inválidos.'));
    exit;
}

// Mapear ação para status
$status = ($acao === 1) ? 1 : 2;

// Atualizar usando prepared statement para evitar injeção
$stmt = $conn->prepare("UPDATE psicologo SET validado = ? WHERE crp = ?");
if (!$stmt) {
    header('Location: admin.php?msg=' . urlencode('Erro no sistema: prepare falhou.'));
    exit;
}
$stmt->bind_param('is', $status, $crp);

if ($stmt->execute()) {
    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        header('Location: admin.php?msg=' . urlencode('Operação realizada com sucesso.'));
        exit;
    } else {
        // nenhum registro atualizado — crp pode não existir
        header('Location: admin.php?msg=' . urlencode('CRP não encontrado.'));
        exit;
    }
} else {
    header('Location: admin.php?msg=' . urlencode('Erro ao atualizar: ' . $conn->error));
    exit;
}
