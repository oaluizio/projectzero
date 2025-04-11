<?php include('../config/db.php'); ?>
<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="../assets/css/style.css">
<h2>Login</h2>
<form id="loginForm">
    <input type="text" name="username" placeholder="Usuário ou Email" required><br>
    <input type="password" name="password" placeholder="Senha" required><br>
    <button type="submit">Entrar</button>
</form>
<div id="loginResult"></div>
<script src="../assets/js/main.js"></script>
<?php include('../includes/footer.php'); ?>
