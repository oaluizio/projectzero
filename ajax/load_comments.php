<?php
session_start();
include('../config/db.php');

$post_id = $_POST['post_id'];

$query = "SELECT comments.*, users.username FROM comments
          JOIN users ON comments.user_id = users.id
          WHERE post_id = ?
          ORDER BY comments.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

while ($c = $result->fetch_assoc()) {
    echo "<p><strong>" . htmlspecialchars($c['username']) . ":</strong> " . htmlspecialchars($c['comment']) . "</p>";
}
?>
