<?php
session_start();
include('../config/db.php');

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

// Verifica se já curtiu
$check = $conn->prepare("SELECT * FROM likes WHERE user_id=? AND post_id=?");
$check->bind_param("ii", $user_id, $post_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    // Já curtiu, remover
    $del = $conn->prepare("DELETE FROM likes WHERE user_id=? AND post_id=?");
    $del->bind_param("ii", $user_id, $post_id);
    $del->execute();
} else {
    // Curtir
    $ins = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $ins->bind_param("ii", $user_id, $post_id);
    $ins->execute();

    // Buscar dono do post
    $postOwnerQuery = $conn->prepare("SELECT user_id FROM posts WHERE id=?");
    $postOwnerQuery->bind_param("i", $post_id);
    $postOwnerQuery->execute();
    $result = $postOwnerQuery->get_result();
    $postOwner = $result->fetch_assoc();
    $post_owner_id = $postOwner['user_id'];

    if ($post_owner_id != $user_id) {
        $insertNotif = $conn->prepare("INSERT INTO notifications (receiver_id, sender_id, type, post_id) VALUES (?, ?, 'like', ?)");
        $insertNotif->bind_param("iii", $post_owner_id, $user_id, $post_id);
        $insertNotif->execute();
    }
}

$count = $conn->query("SELECT COUNT(*) AS total FROM likes WHERE post_id = $post_id")->fetch_assoc();
echo $count['total'];
?>
