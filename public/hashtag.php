<?php
session_start();
include('../config/db.php');

$tag = $_GET['tag'] ?? '';

$stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE caption LIKE ?");
$searchTerm = "%#$tag%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$res = $stmt->get_result();

echo "<h2>Resultados para #" . htmlspecialchars($tag) . "</h2>";

while ($row = $res->fetch_assoc()) {
    echo '<div class="post">';
    echo '<strong>@' . htmlspecialchars($row['username']) . '</strong><br>';
    echo '<img src="../assets/uploads/' . htmlspecialchars($row['image']) . '" width="300"><br>';
    echo '<p>' . nl2br(htmlspecialchars($row['caption'])) . '</p>';
    echo '</div><hr>';
}
