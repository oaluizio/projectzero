document.addEventListener("DOMContentLoaded", () => {
    const registerForm = document.getElementById("registerForm");
    const loginForm = document.getElementById("loginForm");
    const postForm = document.getElementById("postForm");

    // Registro
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            fetch("../ajax/register.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById("registerResult").innerText = data === "success" ? "Registrado com sucesso!" : data;
            });
        });
    }

    // Login
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            fetch("../ajax/login.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data === "success") {
                    window.location.href = "../pages/feed.php";
                } else {
                    document.getElementById("loginResult").innerText = data;
                }
            });
        });
    }

    // Enviar post
    if (postForm) {
        postForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(postForm);
            fetch("../ajax/upload_post.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                const result = document.getElementById("uploadResult");
                if (data === "success") {
                    result.innerText = "Post enviado com sucesso!";
                    postForm.reset();
                    loadFeed();
                } else {
                    result.innerText = data;
                }
            });
        });
    }

    // Curtir post
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("likeBtn")) {
            const post = e.target.closest(".post");
            const postId = post.dataset.postId;

            fetch("../ajax/like_post.php", {
                method: "POST",
                body: new URLSearchParams({ post_id: postId })
            })
            .then(res => res.text())
            .then(data => {
                const likeCount = document.querySelector(`.likeCount[data-post-id='${postId}']`);
                if (likeCount) likeCount.innerText = data;
            });
        }
    });

    // Comentar
    document.addEventListener("submit", function (e) {
        if (e.target.classList.contains("commentForm")) {
            e.preventDefault();
            const form = e.target;
            const post = form.closest(".post");
            const postId = post.dataset.postId;
            const comment = form.comment.value.trim();

            if (comment !== '') {
                fetch("../ajax/comment_post.php", {
                    method: "POST",
                    body: new URLSearchParams({ post_id: postId, comment: comment })
                }).then(() => {
                    form.comment.value = "";
                    loadComments(postId);
                });
            }
        }
    });

    // Seguir / deixar de seguir
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("followBtn")) {
            const btn = e.target;
            const userId = btn.dataset.id;

            fetch("../ajax/follow_user.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "following_id=" + encodeURIComponent(userId)
            })
            .then(res => res.text())
            .then(result => {
                if (result === "seguindo") {
                    btn.textContent = "Seguindo ✅";
                } else if (result === "seguir") {
                    btn.textContent = "Seguir";
                }
                loadFeed(); // atualiza feed após seguir/desseguir
            })
            .catch(() => alert("Erro na conexão."));
        }
    });

    // Função para carregar comentários de um post
    function loadComments(postId) {
        fetch("../ajax/load_comments.php", {
            method: "POST",
            body: new URLSearchParams({ post_id: postId })
        })
        .then(res => res.text())
        .then(data => {
            const box = document.querySelector(`.comments[data-post-id='${postId}']`);
            if (box) box.innerHTML = data;
        });
    }

    // Carrega comentários de todos os posts
    function loadAllComments() {
        document.querySelectorAll(".post").forEach(post => {
            const id = post.dataset.postId;
            loadComments(id);
        });
    }

    // Carrega o feed
    function loadFeed() {
        fetch("../ajax/load_feed.php")
        .then(res => res.text())
        .then(html => {
            const container = document.getElementById("feedContainer");
            if (container) {
                container.innerHTML = html;
                loadAllComments(); // depois que carrega o feed, carrega comentários
            }
        });
    }

    // Carrega o feed automaticamente ao entrar na página
    if (document.getElementById("feedContainer")) {
        loadFeed();
    }
});
