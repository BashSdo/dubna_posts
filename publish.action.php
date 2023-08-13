<?php
    require_once "boot.php";

    if (!@$_SESSION['user']) {
        die("Вы не авторизованы");
    }

    $user = $_SESSION['user'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = pdo()->prepare(
        "INSERT posts(`title`, `content`, `author`)
         VALUES(:title, :content, :author)"
    );
    $stmt->execute([
        'title' => $title,
        'content' => $content,
        'author' => $user,
    ]);

    die("ok");
?>