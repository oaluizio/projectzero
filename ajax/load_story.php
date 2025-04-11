<?php
session_start();
include('../config/db.php');

$res = $conn->query("SELECT stories.*, users.username FROM stories JOIN users ON stories.user_id = users.id WHERE created_at >= NOW() - INTERVAL 1 DAY ORDER BY created_at DESC");

while ($row = $res->fetch_assoc()) {
    $file = htmlspecialchars($row['file']);
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    echo '<div class="story">';
    echo '<strong>' . htmlspecialchars($row['username']) . '</strong><br>';
    if (in_array($ext, ['mp4'])) {
        echo '<video src="../assets/stories/' . $file . '" controls width="200"></video>';
    } else {
        echo '<img src="../assets/stories/' . $file . '" width="200">';
    }
    echo '</div>';
}
?>
