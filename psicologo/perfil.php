<html>
<?php
    session_start();

    if(!isset($_SESSION['crp'])){
        header("Location: entrar.html");
        exit;
    }

    $conexao = new mysqli("localhost", "root", "", "PsiConecta");

    if ($conexao->connect_error) {
        die("Erro na conexão: " . $conexao->connect_error);
    }

    $crp = $_SESSION['crp'];

    // Consulta com os campos corretos
    $sql = "SELECT nome, dtNasc, email, foto, telefone FROM psicologo WHERE crp = '$crp'";

    $resultado = $conexao->query($sql);

    if (!$resultado) {
        die("Erro na consulta SQL (psicologo): " . $conexao->error);
    }

    $dados = $resultado->fetch_assoc();

    $nome = $dados["nome"];
    $dtNasc = $dados["dtNasc"];
    $dtNascFormatada = date("d/m/Y", strtotime($dtNasc));
    $email = $dados["email"];
    $foto = $dados["foto"];
    $telefone = $dados["telefone"];

?>
<head>
    <meta charset="UTF-8">
    <title>Perfil Psicólogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kameron:wght@400..700&display=swap" rel="stylesheet">
</head>
<body style="background-color: rgba(253, 251, 245, 1); font-family: 'Kameron', serif; font-optical-sizing: auto;font-weight: 700; color:#183e64;">
    <div class="container text-center" style="margin-top: 50px;">
        <div class="row">
            <div class="col-4 col-md-4">
                <a href="pagPrincipal.php"><button type="button" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg><br>
                    Voltar
                </button></a>
            </div>
            <div class="col-4 col-md-4">
                <?php if (!empty($dados["foto"])): ?> 
                    <img id="previewFoto" src="uploads/<?= $dados['foto'] ?>" 
                    style="width:100px; height:100px; object-fit:cover; border-radius:50%;"> 
                <?php else: ?> 
                    <svg id="previewFoto" style="color: #24609b;" xmlns="http://www.w3.org/2000/svg" 
                        width="100" height="100" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16"> 
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/> 
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/> 
                    </svg> 
                <?php endif; ?> 
                <div style="font-size: 120%;"><?= $nome ?><br>
                </div>
            </div>
            <div class="col-4 col-md-4">
                <button style="color:#183e64;" type="button" class="btn" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-file-earmark-fill" viewBox="0 0 16 16">
                        <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2z"/>
                    </svg><br>
                    Editar <br>
                    Informações
                </button><br>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body">
                        <form id="formulario" method="POST" action="editarPerfil.php" enctype="multipart/form-data" class="formulario w-100">
                            <div class="campo">
                                <label for="nome">Foto:</label><br>
                                <input type="file" name="foto" id="inputFoto" accept="image/*" style="display:none;">
                                <button type="button" class="btn btn-primary btn-sm mt-2" onclick="document.getElementById('inputFoto').click();">
                                    Adicionar / Trocar Foto
                                </button>
                                <?php if (!empty($dados["foto"])): ?>
                                    <button type="submit" name="excluir_foto" value="1" class="btn btn-danger btn-sm mt-2">
                                        Excluir foto
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="linha">
                                <div class="campo">
                                    <label>Nome:</label>
                                    <input type="text" id="nome" name="nome" class="form-control" value="<?= $nome ?>"/>
                                </div>

                                <div class="campo">
                                    <label>Data de nascimento:</label>
                                    <input type="date" id="data" name="dtNasc" class="form-control" value="<?= $dtNasc ?>"/>
                                </div>

                                <div class="campo">
                                    <label>Email:</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>"/>
                                </div>

                                <div class="campo">
                                    <label>Telefone com DDD:</label>
                                    <input type="tel" id="telefone" name="telefone" class="form-control" value="<?= $telefone ?>"/>
                                </div>

                                <div class="campo">
                                    <label>Senha atual:</label>
                                    <input type="password" id="senha" name="senha_atual" class="form-control" />
                                </div>

                                <div class="campo">
                                    <label>Nova senha:</label>
                                    <input type="password" id="senha" name="nova_senha" class="form-control" />
                                </div>

                                <div class="campo">
                                    <label>Confirmar nova senha:</label>
                                    <input type="password" id="senha" name="confirmar_senha" class="form-control" />
                                </div>
                                <button type="submit">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="w-100"></div>
            <br><br>
            <div class="col-md" style="font-size: 150%;">
                <h3 style="color: #24609b; ">Informações Pessoais:</h3>
                <p>Data de nascimento: <?= $dtNascFormatada ?></p>
                <p>E-mail: <?= $email ?></p>
                <p>Telefone: <?= $telefone?></p>
                <p>CRP: <?= $crp ?></p>
            </div>
            <div class="w-100"></div>
            <br><br><br><br>
            <div class="col-md-4"></div>
            <div class="col-md-4 offset-md-4">
                <a href="../psicologo/logout.php" class="btn">
                    Sair da conta <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</body>
</html>