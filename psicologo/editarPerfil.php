<?php
    session_start();
    if(!isset($_SESSION['crp'])){
        header("Location: entrar.html");
        exit;
    }

    $conexao=new mysqli("localhost", "root", "", "PsiConecta");
    
    if ($conexao->connect_error) {
        die("Erro na conexão: " . $conexao->connect_error);
    };

    $crp = $_SESSION["crp"];

    $psi = $conexao->query("SELECT nome, email, dtNasc, senha, foto, telefone FROM psicologo WHERE crp = '$crp'")->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome  = $_POST["nome"];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];
        $dtNasc = $_POST["dtNasc"];

        // Atualizar usuário
        $stmt = $conexao->prepare("UPDATE psicologo SET nome = ?, email = ?, dtNasc = ?, telefone = ? WHERE crp = ?");
        $stmt->bind_param("sssss", $nome, $email, $dtNasc, $telefone, $crp );
        $stmt->execute();

        $senha_atual = $_POST["senha_atual"];
        $nova_senha = $_POST["nova_senha"];
        $confirmar_senha = $_POST["confirmar_senha"];

        $erro = "";
        $sucesso = "";

        // Se algum campo de senha foi preenchido
        if (!empty($senha_atual) || !empty($nova_senha) || !empty($confirmar_senha)) {

            // Buscar a senha atual do banco
            $stmt = $conexao->prepare("SELECT senha FROM psicologo WHERE crp = ?");
            $stmt->bind_param("s", $crp);
            $stmt->execute();
            $stmt->bind_result($senha_hash_banco);
            $stmt->fetch();
            $stmt->close();

            // Verifica se a senha atual está correta
            if (!password_verify($senha_atual, $senha_hash_banco)) {
                header("Location: impAlterSenha.html");
                exit;
            }

            // Verifica se nova senha e confirmação batem
            if ($nova_senha !== $confirmar_senha) {
                header("Location: impAlterSenha.html");
                exit;
            }

            // Atualiza senha
            $nova_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            $stmt2 = $conexao->prepare("UPDATE psicologo SET senha = ? WHERE crp = ?");
            $stmt2->bind_param("ss", $nova_hash, $crp);
            $stmt2->execute();
            $stmt2->close();
        }
        // Upload de foto
        $foto = $_FILES["foto"];

        // Se o usuário selecionou uma nova foto
        if (!empty($foto["name"])) {

            $pasta = "uploads/";
            if (!is_dir($pasta)) mkdir($pasta, 0777, true);

            // Cria nome único
            $nomeFoto = uniqid() . "-" . basename($foto["name"]);
            $caminhoDestino = $pasta . $nomeFoto;

            // Move o arquivo enviado
            if (move_uploaded_file($foto["tmp_name"], $caminhoDestino)) {

                // Atualiza no banco
                $conexao->query("UPDATE psicologo SET foto='$nomeFoto' WHERE crp='$crp'");

            } else {
                echo "Erro ao mover o arquivo.";
                exit();
            }
        }
        if (isset($_POST['excluir_foto'])) {

            // Busca a foto atual
            $sqlFoto = "SELECT foto FROM psicologo WHERE crp = '$crp'";
            $resFoto = $conexao->query($sqlFoto);
            $fotoAtual = $resFoto->fetch_assoc()['foto'];

            // Apaga o arquivo do servidor
            if (!empty($fotoAtual) && file_exists("uploads/" . $fotoAtual)) {
                unlink("uploads/" . $fotoAtual);
            }

            // Remove do banco
            $conexao->query("UPDATE psicologo SET foto = NULL WHERE crp = '$crp'");

            // Redireciona de volta ao perfil
            header("Location: perfil.php?msg=foto_excluida");
            exit;
        }

        // Atualiza o nome na sessão
        $_SESSION["usuario_nome"] = $nome;

        header("Location: perfil.php");
        exit;
    }
?>