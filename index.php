<?php 
    require_once 'boot.php';

    $stmt = pdo()->prepare(
        "SELECT `title`, `content`, `author`, `posted_at`, 
                (SELECT `name`
                 FROM `users`
                 WHERE `username` = `posts`.`author`) AS `author_name`
         FROM `posts`
         ORDER BY `posted_at` DESC"
    );
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Разработка веб - приложений</title>

    <style>
        @import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css";
        @import "https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css";
    </style>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script lang='javascript'>
        jQuery(function($) {
            $(document).ready(function() {
                $('.modal-auth form').each(function() {
                    $(this).hide();
                });

                $('.modal-auth form.form-auth').show();

                $('.tabs li').click(function() {
                    $('.tabs li').removeClass('is-active');
                    $(this).addClass('is-active');
                    $('.modal-card-body form').hide();
                    $('.form-' + $(this).data("tab")).show();
                })

                $(".form-auth button").click(function() {
                    $.ajax({
                        type: "POST",
                        url: "/login.action.php",
                        data: $(".form-auth").serialize(),
                        success: function(response) {
                            if (response == "ok") {
                                alert("Вы успешно авторизовались");
                                location = "/";
                            } else {
                                alert(response);
                            }
                        }
                    });

                    return false;
                });

                $(".form-reg button").click(function() {
                    $.ajax({
                        type: "POST",
                        url: "/register.action.php",
                        data: $(".form-reg").serialize(),
                        success: function(response) {
                            console.log(response);
                            if (response == "ok") {
                                alert("Вы успешно зарегистрировались");
                                location = "/";
                            } else {
                                alert(response);
                            }
                        }
                    });

                    return false;
                });

                $("#btn-login").click(function() {
                    $(".modal-auth").addClass("is-active");
                });
            });
        })
    </script>
</head>
<body>
    <nav class="navbar is-primary">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <div class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="/">Главная</a>
            </div>
            <div class="navbar-end">
                <?php if (!isset($_SESSION['user'])): ?>
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-light" id="btn-login">
                                Войти
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-light" href="/logout.action.php" id="btn-logout">
                                Выйти
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <section>
        <div class="modal modal-auth">
            <div class="modal-background"></div>

            <div class="modal-card">
                <header class="modal-card-head">
                    <div class="tabs">
                        <ul>
                            <li data-tab="auth" class="is-active"><a>Авторизация</a> </li>
                            <li data-tab="reg"> <a id="tab-register">Регистрация</a> </li>
                        </ul>
                    </div>

                    <button class="modal-close is-large" aria-label="close"></button>
                </header>
                <section class="modal-card-body">
                    <form class="form-auth">
                        <div class="field">
                            <label class="label">Логин</label>
                            <div class="control">
                                <input class="input" type="text" name="username" placeholder="Введите логин">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Пароль</label>
                            <div class="control">
                                <input class="input" type="password" name="password" placeholder="Введите пароль">
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button class="button is-link">Войти</button>
                            </div>
                        </div>
                    </form>

                    <form class="form-reg">
                        <div class="field">
                            <label class="label">Логин</label>
                            <div class="control">
                                <input class="input" type="text" name="username" placeholder="Введите логин">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Ваше имя</label>
                            <div class="control">
                                <input class="input" type="text" name="name" placeholder="Введите имя">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Пароль</label>
                            <div class="control">
                                <input class="input" type="password" name="password" placeholder="Введите пароль">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Повторите пароль</label>
                            <div class="control">
                                <input class="input" type="password" name="password_confirm" placeholder="Повторите пароль">
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button class="button is-link">Зарегистрироваться</button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>

            <script>
                jQuery(function($) {
                    $(".modal-close").click(function() {
                        $(".modal").removeClass("is-active");
                    });
                })
            </script>
        </div>

        <?php if (isset($_SESSION['user'])): ?>
            <div class="container">
                <div class="notification is-secondary">
                    <h1>Привет, <?php
                        $stmt = pdo()->prepare(
                            "SELECT `name`
                            FROM `users`
                            WHERE `username` = :username"
                        );
                        $stmt->execute([
                            'username' => $_SESSION['user']
                        ]);
                        $username = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo $username['name'];
                    ?></h1>
                </div>

                <style>
                    .notification {
                        margin: 35px 0 !important;
                    }
                </style>

                <button id="add-post" class="button is-primary">Добавить пост</button>

                <div class="modal modal-add-new">
                    <div class="modal-background"></div>

                    <div class="modal-card">
                        <button class="modal-close is-large" aria-label="close"></button>
                        <div class="modal-card-body">
                            <form> 
                                <div class="field">
                                    <label class="label">Заголовок</label>
                                    <div class="control">
                                        <input class="input" type="text" name="title" placeholder="Введите заголовок">
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Содержание</label>
                                    <div class="control">
                                        <textarea class="textarea" name="content" placeholder="Введите содержание"></textarea>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-link">Добавить</button>
                                    </div>
                                </div>
                            </form>

                            <script lang='javascript'>
                                jQuery(function($) {
                                    $(".modal-add-new .modal-card-body button").click(function() {
                                        $.ajax({
                                            type: "POST",
                                            url: "/publish.action.php",
                                            data: $(".modal-add-new form").serialize(),
                                            success: function(response) {
                                                console.log(response);
                                                if (response == "ok") {
                                                    alert("Пост успешно добавлен");
                                                    location = "/";
                                                } else {
                                                    alert(response);
                                                }
                                            }
                                        });

                                        return false;
                                    });

                                    $("#add-post").click(function() {
                                        $(".modal-add-new").addClass("is-active");
                                    });
                                })

                            </script>
                        </div>

                    </div>
                </div>

                <?php foreach( $posts as $key => $post ): ?>
                    <article class="box media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="https://bulma.io/images/placeholders/64x64.png">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                            <p>
                                <strong><?= $post['author_name'] ?></strong>
                                <small><?= $post['posted_at'] ?></small>
                                <br>
                                <u style="font-style: italic"><?= $post['title'] ?></u>
                                <br>
                                <?= $post['content'] ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>

                <style>
                    .box {
                        margin: 15px 0;
                    }
                </style>

            </div>
        <?php else: ?>
            <div class="container">
                <div class="notification is-warning">
                    <h1>Привет, гость</h1>
                    <h3>Для доступа к сайту необходимо авторизоваться</h3>
                </div>
            </div>
        <?php endif; ?>
    </section>
    <footer class="footer">
        <div class="content has-text-centered">
            Башилов Михаил, ПРОГ-С-20, 2023г.
        </div>
    </footer>
</body>
</html>