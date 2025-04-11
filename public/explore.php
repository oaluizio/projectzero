<?php
session_start();
include('../config/db.php');

// Explorar usuários
$users = $conn->query("SELECT id, username, profile_pic FROM users ORDER BY RAND() LIMIT 10");

// Explorar posts
$posts = $conn->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY RAND() LIMIT 10");
?>

<h2>Explorar Usuários</h2>
<?php while ($u = $users->fetch_assoc()): ?>
    <div>
        <a href="profile.php?id=<?= $u['id'] ?>">
            <strong>@<?= htmlspecialchars($u['username']) ?></strong>
        </a>
    </div>
<?php endwhile; ?>

<h2>Explorar Posts</h2>
<?php while ($p = $posts->fetch_assoc()): ?>
    <div class="post">
        <strong>@<?= htmlspecialchars($p['username']) ?></strong><br>
        <img src="../assets/uploads/<?= htmlspecialchars($p['image']) ?>" width="300"><br>
        <p><?= nl2br(htmlspecialchars($p['caption'])) ?></p>
    </div>
    <hr>
<?php endwhile; ?>
