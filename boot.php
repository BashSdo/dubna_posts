<?php
session_start();

function pdo(): PDO {
    static $pdo;

    if (!$pdo) {
        $config = include __DIR__.'/config.php';
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_SERVER;
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    }

    return $pdo;
}
