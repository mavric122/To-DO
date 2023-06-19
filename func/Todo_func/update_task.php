<?php
session_start();
require_once "../connect.php";
require_once "../func.php";

$idTask = $_POST['task_id'];

if (isUserLoggedIn($_COOKIE["user"])) {
    $sql = $pdo->prepare("UPDATE todos SET status = 0 WHERE id = :idTask");
    $sql->execute([":idTask" => $idTask]);
    header("Location:/../index.php");
    exit();
} else {
    $_SESSION["msg"] = "Вы не авторизованы!";
    header("Location:/../login.php");
    exit();
}


