<?php
session_start();
include('../config/db.php');

$follower_id = $_SESSION['user_id'];
$following_id = $_POST['follow_id'];

// Verifica se já segue
$check = $conn->prepare("SELECT * FROM followers WHERE follower_id = ? AND following_id = ?");
$check->bind_param("ii", $follower_id, $following_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    // Seguir
    $insert = $conn->prepare("INSERT INTO followers (follower_id, following_id) VALUES (?, ?)");
    $insert->bind_param("ii", $follower_id, $following_id);
    $insert->execute();

    // Evita notificar a si mesmo
    if ($follower_id !== $following_id) {
        $notif = $conn->prepare("INSERT INTO notifications (receiver_id, sender_id, type) VALUES (?, ?, 'follow')");
        $notif->bind_param("ii", $following_id, $follower_id);
        $notif->execute();
    }

    echo "Seguindo";
} else {
    echo "Já segue";
}
?>
    