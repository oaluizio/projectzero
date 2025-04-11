<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    exit("Você não está logado.");
}

$user_id = $_SESSION['user_id'];
$caption = $_POST['caption'] ?? '';
$image = $_FILES['image'];

if ($image['error'] === 0) {
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $ext;
    $path = "../assets/uploads/" . $filename;
    move_uploaded_file($image['tmp_name'], $path);

    $stmt = $conn->prepare("INSERT INTO posts (user_id, image, caption) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $filename, $caption);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Erro ao salvar no banco.";
    }
} else {
    echo "Erro no upload da imagem.";
}
?>
