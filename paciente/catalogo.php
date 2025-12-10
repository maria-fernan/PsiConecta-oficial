<?php

session_start();

$mysqli = new mysqli("localhost", "root", "", "PsiConecta");
if ($mysqli->connect_errno) {
    die("Erro ao conectar: " . $mysqli->connect_error);
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catálogo de Psicólogos Online</title>
  <link rel="stylesheet" href="catalogo.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kameron:wght@400..700&display=swap" rel="stylesheet">
</head>

<body>

<br>
<div class="alinhamento">
    <a href="../paciente/pagPrincipal.php" style="margin-left: -90%;">
        <button type="button" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg>
        </button>
    </a>

    <h1 style="position: absolute;">Psicólogos Online</h1>
</div>

<br><br>

<!-- ONLINE -->
<div class="container text-center">
    <div class="row justify-content-center" id="lista">
        <!-- JS insere os cards online aqui -->
    </div>
</div>

<!-- CADASTRADOS (não atualiza) -->
<br><br><br>
<div class="container text-center">
    <h2 style="color:black; text-align:center; margin-left:15px;">Todos os Psicólogos</h2>
    <div class="row justify-content-center" id="listaCadastrados">
        <!-- JS insere os cards cadastrados aqui -->
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>

// CONTAINER GLOBAL DE MODAIS
let modalContainer = document.getElementById('modal-container');
if (!modalContainer) {
    modalContainer = document.createElement('div');
    modalContainer.id = 'modal-container';
    document.body.appendChild(modalContainer);
}

/* ===============================
   CARREGAR PSICÓLOGOS ONLINE
   =============================== */
function carregar() {
    fetch("../psicologo/buscarOnline.php")
        .then(r => r.json())
        .then(dados => {

            const lista = document.getElementById("lista");

            // mover modais para fora antes de limpar
            const modais = lista.querySelectorAll('.modal');
            const abertos = [];
            modais.forEach(m => {
                if (m.classList.contains("show")) abertos.push(m.id);
                modalContainer.appendChild(m);
            });

            lista.innerHTML = "";

            if (!Array.isArray(dados) || dados.length === 0) {
                lista.innerHTML = "<p style='color:white;'>Nenhum psicólogo online no momento.</p>";
                return;
            }

            dados.forEach(p => {

                // card online
                lista.innerHTML += `
                <div class="col-md-3 mb-4">
                    <div class="card" style="background-color: #183e64; color:white;">
                        <div class="card-body">
                              <img src="${p.foto ? '../psicologo/uploads/' + p.foto : '../imagens/fotoperfil.png'}"
                                 class="mb-3"
                                 alt="foto"
                                 style="width: 55px; height: 55px; border-radius: 70%;">

                            <h5 class="card-title">${p.nome}</h5>

                            <button type="button" class="btn btn-light"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-${p.crp}">
                                Iniciar conversa
                            </button>
                        </div>
                    </div>
                </div>
                `;

                const modalId = `modal-${p.crp}`;
                if (!document.getElementById(modalId)) {

                    modalContainer.insertAdjacentHTML("beforeend", `
                    <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" style="background-color: #183e64; color:white;">
                                <div class="modal-header">
                                    <h5 class="modal-title">Conversar com ${p.nome}?</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Não</button>
                                    <button class="btn btn-success btnConfirmar"
                                            data-bs-dismiss="modal"
                                            data-crp="${p.crp}">
                                        Sim
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `);
                }
            });

            // reabre modais abertos
            setTimeout(() => {
                abertos.forEach(id => {
                    const modalEl = document.getElementById(id);
                    if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
                });
            }, 50);

        })
        .catch(err => console.log(err));
}

// INICIAR ATUALIZAÇÃO ONLINE
setInterval(carregar, 5000);
carregar();
</script>

<script>
/* ===============================
   CARREGAR TODOS OS CADASTRADOS
   =============================== */
function carregarTodosPsicologos() {
    fetch("../psicologo/listarTodos.php")
        .then(r => r.json())
        .then(dados => {

            const lista = document.getElementById("listaCadastrados");

            dados.forEach(p => {

                if (document.getElementById("card-" + p.crp)) return;

                lista.insertAdjacentHTML("beforeend", `
                    <div class="col-md-2 mb-3" id="card-${p.crp}">
                    <br>
                        <div class="card" style="background-color: #1d6277; color:white; padding:4px; height:150px">
                            <div class="card-body" style="padding:4px; text-align:center;">


                            <img src="${p.foto ? '../psicologo/uploads/' + p.foto : '../imagens/fotoperfil.png'}"
                                 class="mb-3"
                                 alt="foto"
                                 style="width: 50px; height: 50px; border-radius: 70%;">

                            <h5 class="card-title">${p.nome}</h5>

                            <button type="button" class="btn btn-light"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-info-${p.crp}">
                                Ver informações
                            </button>

                        </div>
                    </div>
                </div>
                `);

                if (!document.getElementById("modal-info-" + p.crp)) {

                    let idade = "Não informada";
                    if (p.dtNasc) {
                        const nasc = new Date(p.dtNasc);
                        idade = Math.floor((Date.now() - nasc.getTime()) / 31536000000);
                    }

                    modalContainer.insertAdjacentHTML("beforeend", `
                    <div class="modal fade" id="modal-info-${p.crp}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" style="background-color:#1d6277; color:white;">
                                <div class="modal-header">
                                    <h5 class="modal-title">Informações de ${p.nome}</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <p><strong>Nome:</strong> ${p.nome}</p>
                                    <p><strong>Idade:</strong> ${idade} anos</p>
                                    <p><strong>Área de atuação:</strong> ${p.area_atuacao || "Não informada"}</p>
                                    <p><strong>Tempo de atuação:</strong> ${p.tempo_atuacao || "Não informado"}</p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `);
                }

            });

        })
        .catch(err => console.log(err));
}

// CARREGAR CADASTRADOS UMA VEZ
carregarTodosPsicologos();

</script>


<!-- ===========================
       NOTIFICAR VIA FIREBASE
     =========================== -->

<script>
    const nomeDoUsuario     = "<?php echo $_SESSION['telefone']; ?>";
    const telefoneDoUsuario = "<?php echo $_SESSION['nome']; ?>";
</script>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-app.js";
import { getDatabase, ref, push } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-database.js";

const firebaseConfig = {
    apiKey: "AIzaSyBbTn2O1Fphyuf1gK91Yj867z2YsbjI2LA",
    authDomain: "teste2-75701.firebaseapp.com",
    databaseURL: "https://teste2-75701-default-rtdb.firebaseio.com",
    projectId: "teste2-75701",
    storageBucket: "teste2-75701.firebasestorage.app",
    messagingSenderId: "279932092192",
    appId: "1:279932092192:web:c5f96a26a94ed1a6a56145"
};

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

document.addEventListener("click", (e) => {
    if (e.target && e.target.classList.contains("btnConfirmar")) {
        const crp = e.target.dataset.crp;
        const caminho = `notify/${crp}`;

        const nomePaciente = nomeDoUsuario;
        const telefonePaciente = telefoneDoUsuario;
        const linkParaEnviar = "https://wa.me/55" + telefonePaciente;

        push(ref(db, caminho), {
            link: linkParaEnviar,
            nome: nomePaciente,
            telefone: telefonePaciente,
            ts: Date.now()
        })
        .then(() => console.log("Notificação enviada!"))
        .catch(err => console.log(err));
    }
});
</script>

</body>
</html>
