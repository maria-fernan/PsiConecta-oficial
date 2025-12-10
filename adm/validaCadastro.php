<?php
 session_start();

 $usuario = $_POST['usuario'];
 $senha = $_POST['senha'];

 $conexao = mysqli_connect("localhost","root","", "psiconecta") 
 or die("Erro ao conectar. " . mysqli_error() );

 if ($usuario == "AdministradoresPC" and $senha == "PsiConecta2025"){
    header('Location: admin.php');
 } else{
    echo "Credenciais inválidas.";
 }
 ?>