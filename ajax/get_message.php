<?php
session_start();
include('../config/db.php');

$sender = $_SESSION['user_id'];
$receiver = $_POST['receiver_id'];

$stmt = $conn->prepare("
    SELECT * FROM messages
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $sender, $receiver, $receiver, $sender);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    echo '<div><strong>' . ($row['sender_id'] == $sender ? 'Você' : 'Eles') . ':</strong> ' . htmlspecialchars($row['message']) . '</div>';
}
?>
