<?php
session_start();
include('../config/db.php');

$user_id = $_SESSION['user_id'] ?? 0;

$query = "SELECT posts.*, users.username, users.profile_pic
          FROM posts
          JOIN users ON posts.user_id = users.id
          WHERE posts.user_id = ? OR posts.user_id IN (
              SELECT following_id FROM followers WHERE follower_id = ?
          )
          ORDER BY posts.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $post_id = $row['id'];

    // Contar curtidas
    $likesQuery = $conn->query("SELECT COUNT(*) AS total FROM likes WHERE post_id = $post_id");
    $likes = $likesQuery->fetch_assoc()['total'];

    // Verificar se o usuário curtiu
    $userLikedQuery = $conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
    $userLikedQuery->bind_param("ii", $post_id, $user_id);
    $userLikedQuery->execute();
    $userLikedResult = $userLikedQuery->get_result();
    $userLiked = $userLikedResult->num_rows > 0;

    echo '<div class="post" data-post-id="' . $post_id . '">';

    // Nome do usuário e imagem de perfil
    echo '<strong>' . htmlspecialchars($row['username']) . '</strong><br>';

    // Imagem do post
    echo '<img src="../assets/uploads/' . htmlspecialchars($row['image']) . '" width="300"><br>';

    // Legenda
    if ($row['caption']) {
        echo '<p>' . nl2br(htmlspecialchars($row['caption'])) . '</p>';
    }

    // Botão de curtir
    echo '<button class="likeBtn">' . ($userLiked ? 'Descurtir' : 'Curtir') . '</button> ';
    echo '<span class="likeCount" data-post-id="' . $post_id . '">' . $likes . '</span> curtidas<br>';

    // Comentários
    echo '<div class="commentSection">';
    echo '<form class="commentForm">';
    echo '<input type="text" name="comment" placeholder="Comente..." required>';
    echo '<button type="submit">Enviar</button>';
    echo '</form>';
    echo '<div class="comments" data-post-id="' . $post_id . '">Carregando comentários...</div>';
    echo '</div>';

    // Data
    echo '<small>Publicado em: ' . $row['created_at'] . '</small>';

    echo '</div><hr>';
}
?>
