<?php
$mysqli = new mysqli("localhost", "root", "", "PsiConecta");

if ($mysqli->connect_errno) {
    die(json_encode(["erro" => "Falha na conexão"]));
}

$sql = "SELECT nome, crp, foto, dtNasc, area_atuacao, tempo_atuacao 
        FROM psicologo 
        WHERE validado = 1";

$result = $mysqli->query($sql);

$lista = [];

while ($row = $result->fetch_assoc()) {
    $lista[] = $row;
}

echo json_encode($lista);
?>
