<?php
session_start();
require_once "../func/connect.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST["login"]) && isset($_POST["password"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];

    // Подготовка запроса SQL для получения хэша пароля из базы данных
    $sql = $pdo->prepare("SELECT * FROM user WHERE login = :login");
    $sql->execute(array("login" => $login));
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        if(!empty($user["user_token"] )) { // Если токен есть.
            $token = $user["user_token"];
            $_SESSION["name"] = $user["login"];
            setcookie("token", $token, time() + 86400, "/");
            header("Location:../index.html");
            exit();
        } else {
            $token = bin2hex(random_bytes(16)); // Если нет, то создаём.
            $id_user = $user["id"];
            $_SESSION["name"] = $user["login"];
            $newTokenBD = $pdo->prepare("UPDATE user SET user_token = :token WHERE id = :id_user"); // Записываем в БД
            $newTokenBD->execute([':token' => $token, ':id_user' => $id_user]);
            setcookie("token", $token, time() + 86400, "/");
            header("Location:../index.html");
            exit();
        }
    } else {
        $_SESSION["msg"] = "Ошибка входа. Проверьте логин и пароль!";
        header("Location:../login.html");
        exit();
    }
} else {
    $_SESSION["msg"] = "Не переданы данные для входа";
    header("Location:../login.html");
    exit();
}
?>