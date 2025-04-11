<?php
session_start();
include('../config/db.php');

$user_id = $_SESSION['user_id'];
$media = $_FILES['story']['name'];
$tmp = $_FILES['story']['tmp_name'];
$ext = pathinfo($media, PATHINFO_EXTENSION);
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'mp4'];

if (in_array($ext, $allowed)) {
    $newName = time() . '_' . basename($media);
    move_uploaded_file($tmp, "../assets/stories/$newName");

    $stmt = $conn->prepare("INSERT INTO stories (user_id, file, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $user_id, $newName);
    $stmt->execute();
    echo "success";
}
?>
