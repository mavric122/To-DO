<?php
session_start();
require_once "../func/connect.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$task = $_POST["task"];
$description = $_POST["description"];

if (isset($_COOKIE["user"])) { // Проверяем наличие токена
    $user = json_decode($_COOKIE["user"], true);
    $token = $user["token"];
    $sql = $pdo->prepare("SELECT id, user_token FROM user WHERE user_token=:token");
    $sql->execute(array("token" => $token));
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    if ($token == $user["user_token"]) {
        $sqlTask = $pdo->prepare("INSERT INTO todos(user_id, task, description) VALUES (:user_id, :task, :description)");
        $sqlTask->execute(array("user_id" => $user["id"], "task" => $task, "description" => $description));
        header("Location:../index.html");
        exit();
    } else {
        $_SESSION["msg"] = "Ошибка доступа!";
        header("Location:../login.html");
        exit();
    }
} else {
    $_SESSION["msg"] = "Авторизуйтесь!";
    header("Location:../login.html");
    exit();
}