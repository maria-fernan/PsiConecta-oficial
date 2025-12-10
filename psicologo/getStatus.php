<?php
$conn = mysqli_connect("localhost", "root", "", "psiconecta");

$crp = $_GET['crp'];

$sql = "SELECT status FROM on_line WHERE crp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $crp);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["status" => $row['status']]);
?>
