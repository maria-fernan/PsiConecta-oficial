<?php
// Mostrar erros (apenas para desenvolvimento; depois desligue)
// Coloque isto no topo para ver warnings/erros reais
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão segura e verificação
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'psiconecta';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    // Mensagem clara de erro de conexão
    die("Erro ao conectar ao MySQL: " . mysqli_connect_error());
}

// garantir charset
$conn->set_charset('utf8mb4');

// Consulta
$sql = "SELECT * FROM psicologo ORDER BY data_cadastro DESC";
$result = $conn->query($sql);
if ($result === false) {
    die("Erro na consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <!--Link da fonte-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kameron:wght@400..700&display=swap" rel="stylesheet">
  <style>
    body {
    height: 100%;
    background-color:#183e64;
    justify-content: center;
    font-family: "Kameron", serif;
    font-optical-sizing: auto;
    background-repeat: no-repeat;
    padding: 20px;}
    h2{color:white;
    text-align:center}
    table { width: 100%; }
    th, td { text-align: center; vertical-align: middle; }
  </style>
</head>
<body>
<a href="../geral/pagPrincipal.html" style="color: white;" ><- Voltar</a>
<h2>Psicólogos cadastrados</h2>
<br>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>CRP</th>
            <th>Status</th>
            <th width="180">Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($result->num_rows === 0) { ?>
        <tr><td colspan="5">Nenhum psicólogo encontrado.</td></tr>
    <?php } else { 
        while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['crp'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <?php
                $st = (int)$row['validado'];
                if ($st === 0) echo "Pendente";
                elseif ($st === 1) echo "Validado";
                elseif ($st === 2) echo "Recusado";
                else echo "Desconhecido";
                ?>
            </td>
            <td>
                <a href="validar.php?crp=<?php echo urlencode(trim($row['crp'])); ?>&acao=1">Validar</a> |
                <a href="validar.php?crp=<?php echo urlencode(trim($row['crp'])); ?>&acao=2">Recusar</a>
            </td>
        </tr>
    <?php } } ?>
    </tbody>
</table>
</div>
</body>
</html>
