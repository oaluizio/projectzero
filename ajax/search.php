<?php
include('../config/db.php');

$term = trim($_GET['q']);

if (strpos($term, '#') === 0) {
    $tag = substr($term, 1);
    $stmt = $conn->prepare("SELECT * FROM posts WHERE caption LIKE ?");
    $like = "%#$tag%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $res = $stmt->get_result();

    echo "<h3>Posts com #" . htmlspecialchars($tag) . "</h3>";
    while ($row = $res->fetch_assoc()) {
        echo '<div><img src="../assets/uploads/' . htmlspecialchars($row['image']) . '" width="100"></div>';
    }
} else {
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE username LIKE ?");
    $like = "%$term%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $res = $stmt->get_result();

    echo "<h3>Usuários encontrados:</h3>";
    while ($row = $res->fetch_assoc()) {
        echo '<div><a href="profile.php?id=' . $row['id'] . '">' . htmlspecialchars($row['username']) . '</a></div>';
    }
}
?>
