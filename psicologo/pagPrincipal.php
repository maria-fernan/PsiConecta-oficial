<?php
session_start();
if (!isset($_SESSION['crp'])) {
    header("Location: login.html");
    exit();
}
$crp = $_SESSION['crp'];

$conexao = new mysqli("localhost", "root", "", "PsiConecta");

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$crp = $_SESSION['crp'];

$sql = "SELECT foto FROM psicologo WHERE crp = '$crp'";

$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro na consulta SQL (paciente): " . $conexao->error);
}

$dados = $resultado->fetch_assoc();

$foto = $dados["foto"];
?>


<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PsiConecta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="pagPrincipal.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Fonte -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kameron:wght@400..700&display=swap" rel="stylesheet"> 
  </head>
  <body>
    <!-- Barra de navegação -->
    <nav class="navbar col-12 m-auto navbar-expand-lg position-fixed" style="z-index: 999;">
      <div class="container-fluid" style="width: 50%;">
        <a class="navbar-brand d-inline-flex" href="#inicio">
          <img src="../imagens/logo.png" width="11%" height="11%" class="d-inline-block align-top" alt="Logo">
          <span class="texto-marca">PsiConecta</span>
        </a>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
              data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
              aria-expanded="false" aria-label="Toggle navigation" 
              style="margin-right: 2%; margin-left: 25%;">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse item-navegacao" id="navbarSupportedContent">
        <ul class="perfilpsico navbar-nav me-auto mb-2 mb-lg-2">
          <div class="vazia">&ensp;&ensp;</div>
          <div class="perfilTotal">
            <a href="perfil.php" style="text-decoration: none;">
                <?php if (!empty($dados["foto"])): ?> 
                  <img id="previewFoto" src="uploads/<?= $dados['foto'] ?>" 
                  style="width:55px; height:55px; object-fit:cover; border-radius:50%;"> 
                <?php else: ?>
                  <img src="../imagens/fotoperfil.png" alt="fotoperfil" width="55px" height="55px" 
                   style="border:solid 2px rgba(24, 62, 100, 1); border-radius: 50%;">  
                <?php endif; ?> 
              <p class="perfil">Perfil</p>
            </a>
          </div>
        </ul>
      </div>
    </nav>
    <div id="inicio"></div>
    <br><br><br><br><br><br><br>

    <!-- notificação -->
     <div style="background-color: rgba(154, 206, 255, 1); width: 500px; text-align: center; margin-left: 500px; margin-right:250px;" id="0000"> 
                
      </div>
         
    <!-- Parte Inicial -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md texto">
          <span style="background-color:rgba(214, 217, 228, 1); border-radius: 3px;">
            &ensp;&ensp;TRANSFORME SEU INTERIOR PARA MUDAR O SEU EXTERIOR&ensp;&ensp;
          </span><br>
          <span class="slogan">PsiConecta, onde se cuida da mente e do coração.</span><br>
          <span class="frase">
            Toda mudança começa de dentro pra fora e o <span class="destaque">PsiConecta é a chave</span> 
            para se entender, se organizar internamente e mudar tudo ao seu redor.
          </span>
        </div>
        <div class="col-md imagem">
          <img width="100%" style="border: solid 6.5px rgba(24, 62, 100, 1); border-radius: 5%;" 
               src="../imagens/equipe.jpeg" alt="equipe">
        </div>
      </div>
    </div>
    <br><br><br><br><br><br>

<!-- Botão de atendimento -->
<h2>Iniciar atendimento</h2>
<div class="linha" style="text-align: center;">
  <div class="botao">
    <button id="statusBtn" class="status" 
            style="height: 70px; width: 180px; border-radius: 40px; font-weight:bolder;">
      CARREGANDO...
    </button>
  </div>
</div>
    <br><br><br><br>

    <!-- Dúvidas -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-1"></div>
        <div class="col-md duvidas">
          <img src="../imagens/logo.png" width="15%"><br>
          <div class="nome">PsiConecta</div><br>
          <div class="questao">Ficou com alguma dúvida?</div><br>
          <div class="sugestao">Entre em contato conosco.</div><br>
          <div class="contato">ATENDIMENTO POR EMAIL:&ensp; psiconecta.contato@gmail.com</div>
          <br><br><br><br>
        </div>
        <div class="col-1"></div>
      </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const statusBtn = document.getElementById("statusBtn");
        const crp = "<?= $crp ?>";

        // Pega status atual
        fetch("getStatus.php?crp=" + crp)
            .then(r => r.json())
            .then(data => {
            if (data.status === "online") {
                statusBtn.textContent = "ON-LINE";
                statusBtn.classList.add("online");
            } else {
                statusBtn.textContent = "OFF-LINE";
                statusBtn.classList.add("offline");
            }
            });

        // Troca status ao clicar
        statusBtn.addEventListener("click", () => {
            const novoStatus = statusBtn.textContent === "OFF-LINE" ? "online" : "offline";
            fetch("atualizaStatus.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `crp=${crp}&status=${novoStatus}`
            })
            .then(() => {
            statusBtn.textContent = novoStatus === "online" ? "ON-LINE" : "OFF-LINE";
            statusBtn.classList.toggle("online");
            statusBtn.classList.toggle("offline");
            });
        });
        });
    </script>

<!-- notificação -->
     

  
<!-- Script -->
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-app.js";
import { getDatabase, ref, onChildAdded, remove } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-database.js";

const firebaseConfig = {
  apiKey: "AIzaSyBbTn2O1Fphyuf1gK91Yj867z2YsbjI2LA",
  authDomain: "teste2-75701.firebaseapp.com",
  projectId: "teste2-75701",
  storageBucket: "teste2-75701.firebasestorage.app",
  messagingSenderId: "279932092192",
  appId: "1:279932092192:web:c5f96a26a94ed1a6a56145"
};

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

// o CRP do psicólogo logado
const crpPsicologo = "<?php echo $_SESSION['crp']; ?>";

// caminho do psicólogo
const caminho = `notify/${crpPsicologo}`;

const lista = document.getElementById("0000");

onChildAdded(ref(db, caminho), snapshot => {
    const data = snapshot.val(); // correto
    if (!data) return;

  console.log('Notificação recebida (firebase):', data, 'key=', snapshot.key);

  const div = document.createElement("div");
  div.style.margin = "10px 0";
  div.innerHTML = `
    <a href="${data.link}" target="_blank" style="font-size:18px;">
      📞 ${data.nome} (${data.telefone}) solicitou atendimento
    </a>
  `;

  lista.appendChild(div);

  // remove a notificação do Firebase (evita repetição)
  const childPath = `${caminho}/${snapshot.key}`;
  remove(ref(db, childPath)).catch(err => console.error('Erro ao remover notificação:', err));

});

</script>


  </body>
</html>
<!-- antigo que da certo-->