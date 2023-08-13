<?php
    require_once 'boot.php';

    $username = @$_POST['username'];
    $password = @$_POST['password'];
    $password_confirm = @$_POST['password_confirm'];
    $name = @$_POST['name'];

    if (empty($username) || empty($password) || empty($password_confirm)) {
        die('Заполните все поля');
    }

    if ($password !== $password_confirm) {
        die('Пароли не совпадают');
    }

    $stmt = pdo()->prepare(
        "SELECT 1
         FROM `users`
         WHERE `username` = :username"
    );
    $stmt->execute(['username' => $username]);
    if ($stmt->rowCount() > 0) {
        die('Это имя пользователя уже занято');
    }

    $stmt = pdo()->prepare(
        "INSERT INTO `users` (`username`, `password`, `name`)
         VALUES (:username, :password, :name)"
    );
    $stmt->execute([
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'name' => $name
    ]);

    die('ok')
?>