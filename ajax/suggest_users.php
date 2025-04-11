<?php
session_start();
include('../config/db.php');

$user_id = $_SESSION['user_id'] ?? 0;

$query = "SELECT id, username FROM users 
          WHERE id != ? AND id NOT IN (
              SELECT following_id FROM followers WHERE follower_id = ?
          ) 
          ORDER BY RAND() LIMIT 5";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

while ($u = $res->fetch_assoc()) {
    echo '<div class="suggestion" data-user-id="' . $u['id'] . '">';
    echo '<a href="../public/profile.php?id=' . $u['id'] . '">@' . htmlspecialchars($u['username']) . '</a> ';
    echo '<button class="followBtn" data-id="' . $u['id'] . '">Seguir</button>';
    echo '</div>';
}
?>
