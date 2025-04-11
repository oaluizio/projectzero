<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('../config/db.php');
include('../includes/header.php');

$receiver_id = $_GET['id'];
?>
<h2>Mensagens com usuário #<?php echo $receiver_id; ?></h2>

<div id="messagesBox">Carregando...</div>

<form id="messageForm">
    <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
    <input type="text" name="message" placeholder="Digite uma mensagem..." required>
    <button type="submit">Enviar</button>
</form>

<script>
function loadMessages() {
    const receiverId = <?php echo $receiver_id; ?>;
    fetch("../ajax/get_messages.php", {
        method: "POST",
        body: new URLSearchParams({ receiver_id: receiverId })
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById("messagesBox").innerHTML = data;
    });
}

setInterval(loadMessages, 5000);
loadMessages();

document.getElementById("messageForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("../ajax/send_message.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(result => {
        if (result === "sent") {
            this.message.value = "";
            loadMessages();
        }
    });
});
</script>

<?php include('../includes/footer.php'); ?>
