<?php
header("Content-Type: application/json");

$mysqli = new mysqli("localhost", "root", "", "PsiConecta");

$sql = "
SELECT p.nome, p.crp, p.foto
FROM psicologo p
JOIN on_line o ON p.crp = o.crp
WHERE o.status = 'online'
";

$result = $mysqli->query($sql);

$lista = [];

while ($row = $result->fetch_assoc()) {
    $lista[] = $row;
}

echo json_encode($lista);
?>
