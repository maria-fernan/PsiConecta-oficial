<?php
    session_start();
    if(!isset($_SESSION['logado'])){
        header("Location: entrar.html");
        exit;
    }

    $conexao=new mysqli("localhost", "root", "", "PsiConecta");
    
    if ($conexao->connect_error) {
        die("Erro na conexão: " . $conexao->connect_error);
    };

    $idPaciente = $_SESSION["idPaciente"];

    $p = $conexao->query("SELECT nome, email, dtNasc, senha, telefone FROM paciente WHERE idPaciente = $idPaciente")->fetch_assoc();
    
    //$t = $conexao->query("SELECT idPaciente, telefone FROM telpaci WHERE idPaciente = $idPaciente")->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome  = $_POST["nome"];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];
        $dtNasc = $_POST["dtNasc"];

        // Atualizar usuário
        $stmt = $conexao->prepare("UPDATE paciente SET nome = ?, email = ?, dtNasc = ?, telefone = ? WHERE idPaciente = ?");
        $stmt->bind_param("ssssi", $nome, $email, $dtNasc, $telefone, $idPaciente);
        $stmt->execute();

        // Atualizar telefone
        //$stmt2 = $conexao->prepare("UPDATE telpaci SET telefone = ? WHERE idPaciente = ?");
        //$stmt2->bind_param("si", $telefone, $idPaciente);
        //$stmt2->execute();

        $senha_atual = $_POST["senha_atual"];
        $nova_senha = $_POST["nova_senha"];
        $confirmar_senha = $_POST["confirmar_senha"];

        $erro = "";
        $sucesso = "";

        // Se algum campo de senha foi preenchido
        if (!empty($senha_atual) || !empty($nova_senha) || !empty($confirmar_senha)) {

            // 1️⃣ Buscar a senha atual do banco
            $stmt = $conexao->prepare("SELECT senha FROM paciente WHERE idPaciente = ?");
            $stmt->bind_param("i", $idPaciente);
            $stmt->execute();
            $stmt->bind_result($senha_hash_banco);
            $stmt->fetch();
            $stmt->close();

            // 2️⃣ Verifica se a senha atual está correta
            if (!password_verify($senha_atual, $senha_hash_banco)) {
                header("Location: impAlterSenha.html");
                exit;
            }

            // 3️⃣ Verifica se nova senha e confirmação batem
            if ($nova_senha !== $confirmar_senha) {
                header("Location: impAlterSenha.html");
                exit;
            }

            // 4️⃣ Atualiza senha
            $nova_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            $stmt2 = $conexao->prepare("UPDATE paciente SET senha = ? WHERE idPaciente = ?");
            $stmt2->bind_param("si", $nova_hash, $idPaciente);
            $stmt2->execute();
            $stmt2->close();
        }
        if (!empty($_FILES["foto"]["name"])) {
            $arquivo = $_FILES["foto"];
            $nomeFoto = time() . "_" . $arquivo["name"];
            $caminho = "uploads/" . $nomeFoto;

            if (!is_dir("uploads")) {
                mkdir("uploads", 0777, true);
            }

            if (move_uploaded_file($arquivo["tmp_name"], $caminho)) {
                $stmtFoto = $conexao->prepare("UPDATE paciente SET foto = ? WHERE idPaciente = ?");
                $stmtFoto->bind_param("si", $nomeFoto, $idPaciente);
                $stmtFoto->execute();
            } else {
                die("❌ Erro ao mover a imagem para $caminho");
            }
        }
        if (isset($_POST['excluir_foto'])) {

            // Busca a foto atual
            $sqlFoto = "SELECT foto FROM paciente WHERE idPaciente = $idPaciente";
            $resFoto = $conexao->query($sqlFoto);
            $fotoAtual = $resFoto->fetch_assoc()['foto'];

            // Apaga o arquivo do servidor
            if (!empty($fotoAtual) && file_exists("uploads/" . $fotoAtual)) {
                unlink("uploads/" . $fotoAtual);
            }

            // Remove do banco
            $conexao->query("UPDATE paciente SET foto = NULL WHERE idPaciente = $idPaciente");

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