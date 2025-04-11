<?php
session_start();
include('../config/db.php');

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    echo '<div><small>' . $row['created_at'] . '</small> - ' . htmlspecialchars($row['content']) . '</div>';
}
?>
