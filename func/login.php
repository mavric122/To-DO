<?php
session_start();
require_once "../func/connect.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$login = $_POST["login"];
$password = $_POST["password"];

// подготовка запроса SQL, который будет использоваться для поиска пользователя в базе данных по логину, паролю и email.
$sql = $pdo->prepare("SELECT id FROM user WHERE login=:login AND password=:password");
// выполнение подготовленного запроса SQL с передачей параметров логина, пароля и email пользователя.
$sql->execute(array("login" => $login, "password" => $password));
//  получение результата запроса SQL в виде ассоциативного массива.
$user = $sql->fetch(PDO::FETCH_ASSOC);


if ($user && isset($user['password'])) {
    // Пользователь найден. Проверка соответствия пароля.
    if (password_verify($password, $user['password'])) {
        $_SESSION["login"] = $login;


        header('Location:../index.html');
        exit();
    } else {
        echo "Неверный логин или пароль";
    }
} else {
    echo "Неверный логин или пароль.
}

?>