<?php include('../config/db.php'); ?>
<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="../assets/css/style.css">
<h2>Registrar</h2>
<form id="registerForm">
    <input type="text" name="username" placeholder="Usuário" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Senha" required><br>
    <button type="submit">Registrar</button>
</form>
<div id="registerResult"></div>
<script src="../assets/js/main.js"></script>
<?php include('../includes/footer.php'); ?>
