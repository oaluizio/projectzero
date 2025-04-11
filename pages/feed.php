<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('../config/db.php');
include('../includes/header.php');
?>
<link rel="stylesheet" href="../assets/css/style.css">

<h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

<!-- Formulário de upload -->
<form id="postForm" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required><br>
    <textarea name="caption" placeholder="Escreva uma legenda..."></textarea><br>
    <button type="submit">Postar</button>
</form>
<div id="uploadResult"></div>

<hr>

<!-- 🔎 Sugestões de amigos -->
<div id="suggestionsBox">
    <h3>Sugestões para você</h3>
    <div id="suggestions"><em>Carregando sugestões...</em></div>
</div>

<hr>

<!-- Link para explorar todos os usuários -->
<div style="margin-top: 10px;">
    <a href="explore.php">🔍 Explorar todos os usuários</a>
</div>


<!-- Área do feed -->
<div id="feedContainer">Carregando feed...</div>

<!-- JS principal -->
<script src="../assets/js/main.js"></script>

<!-- AJAX: carregar sugestões -->
<script>
fetch("../ajax/suggest_users.php")
    .then(res => res.text())
    .then(data => {
        document.getElementById("suggestions").innerHTML = data;
    });
</script>

<!-- AJAX: seguir/desseguir usuários -->
<script>
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("followBtn")) {
        const btn = e.target;
        const userId = btn.dataset.id;

        fetch("../ajax/follow_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "following_id=" + encodeURIComponent(userId)
        })
        .then(res => res.text())
        .then(result => {
            if (result === "seguindo") {
                btn.textContent = "Seguindo ✅";
            } else if (result === "seguir") {
                btn.textContent = "Seguir";
            } else {
                alert("Erro ao processar ação.");
            }
        })
        .catch(() => alert("Erro na conexão."));
    }
});
</script>

<!-- AJAX: carregar o feed -->
<script>
function loadFeed() {
    fetch("../ajax/load_feed.php")
        .then(res => res.text())
        .then(data => {
            document.getElementById("feedContainer").innerHTML = data;
        });
}
loadFeed();
</script>

<?php include('../includes/footer.php'); ?>

<!-- Em pages/feed.php -->
<div id="notifications"></div>
<script>
function loadNotifications() {
    fetch("../ajax/get_notifications.php")
        .then(res => res.text())
        .then(html => {
            document.getElementById("notifications").innerHTML = html;
        });
}
setInterval(loadNotifications, 10000);
loadNotifications();
</script>

<div id="notificationPanel">
    <h3>🔔 Notificações</h3>
    <div id="notificationBox"><em>Carregando...</em></div>
</div>

