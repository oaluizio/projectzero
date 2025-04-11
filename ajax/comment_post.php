<?php
session_start();
include('../config/db.php');

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$comment = trim($_POST['comment']);

if ($comment !== '') {
    // Inserir comentário
    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $post_id, $comment);
    $stmt->execute();

    // Buscar dono do post
    $getOwner = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $getOwner->bind_param("i", $post_id);
    $getOwner->execute();
    $ownerResult = $getOwner->get_result();
    if ($ownerRow = $ownerResult->fetch_assoc()) {
        $post_owner_id = $ownerRow['user_id'];

        // Evita notificar a si mesmo
        if ($post_owner_id != $user_id) {
            // Inserir notificação
            $insertNotif = $conn->prepare("INSERT INTO notifications (receiver_id, sender_id, type, post_id, message) VALUES (?, ?, 'comment', ?, ?)");
            $insertNotif->bind_param("iiis", $post_owner_id, $user_id, $post_id, $comment);
            $insertNotif->execute();
        }
    }
}
?>
