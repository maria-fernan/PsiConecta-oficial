<?php
$conn = mysqli_connect("localhost", "root", "", "psiconecta");

$crp = $_POST['crp'];
$status = $_POST['status'];

$sql = "UPDATE on_line SET status = ?, fim = CASE WHEN ? = 'offline' THEN CURRENT_TIMESTAMP ELSE NULL END WHERE crp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $status, $status, $crp);
$stmt->execute();

echo "Status atualizado para $status";
?>
