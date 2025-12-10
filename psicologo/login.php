<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "psiconecta") 
or die("Erro ao conectar: " . mysqli_error($conn));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM psicologo WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['crp'] = $row['crp'];
            header("Location: pagPrincipal.php");
            exit;
        }
    }
    header("Location: erroLogin.html");
}
?>
