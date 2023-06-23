<?php
session_start();
require_once "../func/connect.php";
require_once "../func/func.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$task = $_POST["task"];
$description = $_POST["description"];


if(isUserLoggedIn($_COOKIE["user"])){
    $dataUser = json_decode($_COOKIE["user"], true);
    $token = $dataUser["token"];
    $sql = $pdo->prepare("SELECT id, user_token FROM user WHERE user_token=:token");
    $sql->execute(array("token" => $token));
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    $sqlTask = $pdo->prepare("INSERT INTO todos(user_id, task, description) VALUES (:user_id, :task, :description)");
    $sqlTask->execute(array("user_id" => $user["id"], "task" => $task, "description" => $description));
    header("Location:../index.php");
    exit();
} else {
    $_SESSION["msg"] = "Ошибка доступа!";
    header("Location:../login.html");
    exit();
}