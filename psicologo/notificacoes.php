<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Página A</title>

  <style>
    #notificacoes {
      width: 300px;
      padding: 10px;
      background: #f1f1f1;
      border-radius: 10px;
      margin-top: 20px;
      font-family: Arial;
    }
    .msg {
      background: #d1ffd1;
      padding: 8px;
      margin: 5px 0;
      border-radius: 6px;
    }
  </style>

</head>
<body>
  <h2>Página A</h2>
  <button id="btn">Notificar B</button>

  <h3>Notificações recebidas:</h3>
  <div id="notificacoes"></div>

<?php
session_start();

if (!isset($_SESSION['paciente_id'])) {
    die("Nenhum paciente selecionado.");
}

$nome = $_SESSION['paciente_nome'];
$telefone = $_SESSION['paciente_telefone'];
?>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-app.js";
  import { getDatabase, ref, set, onValue, remove } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-database.js";

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

  document.getElementById("btn").onclick = () => {
    set(ref(db, "notify/B"), {
      from: "D",
      time: Date.now()
    });
  };

  function adicionarMensagem(texto) {
  const caixa = document.getElementById("notificacoes");
  const div = document.createElement("div");
  div.className = "msg";
  div.innerHTML = texto;   // <-- AGORA O HTML FUNCIONA
  caixa.appendChild(div);
}


onValue(ref(db, "notify/<?=$idPsicologo?>"), snap => {
  if (snap.exists()) {
    const data = snap.val();

    // Aqui montamos a mensagem com os dados da sessão PHP
    adicionarMensagem(
      "<a href='https://wa.me/55<?=$telefone?>' target='_blank'>Abrir WhatsApp</a> — <?=$nome?>"
    );

    remove(ref(db, "notify/A"));
  }
});
</script>
</body>
</html>