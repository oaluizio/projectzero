<?php
session_start();
include('../config/db.php');

$my_id = $_SESSION['user_id'] ?? 0;
$user_id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "Usuário não encontrado.";
    exit;
}

// Verifica se já segue
$followCheck = $conn->prepare("SELECT * FROM followers WHERE follower_id = ? AND following_id = ?");
$followCheck->bind_param("ii", $my_id, $user_id);
$followCheck->execute();
$isFollowing = $followCheck->get_result()->num_rows > 0;

echo "<h2>@" . htmlspecialchars($user['username']) . "</h2>";

if ($user_id != $my_id) {
    echo '<button id="followBtn" data-user-id="' . $user_id . '">' . ($isFollowing ? 'Deixar de seguir' : 'Seguir') . '</button>';
}

echo '<div id="userPosts">';
$stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

while ($p = $res->fetch_assoc()) {
    echo '<div class="post">';
    echo '<img src="../assets/uploads/' . htmlspecialchars($p['image']) . '" width="250"><br>';
    echo '<p>' . htmlspecialchars($p['caption']) . '</p>';
    echo '</div><hr>';
}
echo '</div>';
?>

<script>
document.getElementById("followBtn")?.addEventListener("click", function () {
    const userId = this.dataset.userId;

    fetch("../ajax/follow_user.php", {
        method: "POST",
        body: new URLSearchParams({ following_id: userId })
    })
    .then(res => res.text())
    .then(txt => {
        this.textContent = txt === "seguir" ? "Seguir" : "Deixar de seguir";
    });
});
</script>
