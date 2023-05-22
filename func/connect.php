<?php

try {
    $user = "root";
    $password = "Acera221hql";
    $host = "localhost";
    $db = "to-do";
    $dbh = "mysql:host=" . $host . ';dbname=' . $db . ';charset=utf8';
    $pdo = new PDO($dbh, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // код, который работает с базой данных

} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
