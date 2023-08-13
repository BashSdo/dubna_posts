<?php
    include_once 'boot.php';

    $username = @$_POST['username'];
    $password = @$_POST['password'];

    if (empty($username) || empty($password)) {
        die('Заполните все поля');
    }

    $stmt = pdo()->prepare(
        "SELECT `password`
         FROM `users`
         WHERE `username` = :username"
    );
    $stmt->execute(['username' => $username]);
    if ($stmt->rowCount() == 0 || !password_verify($password, $stmt->fetch(PDO::FETCH_ASSOC)['password'])) {
        die('Неверный логин или пароль');
    }

    $_SESSION['user'] = $username;

    die("ok");
?>