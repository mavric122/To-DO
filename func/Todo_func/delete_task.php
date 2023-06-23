<?php

session_start();
require_once "../connect.php";
require_once "../func.php";

$idTask = $_POST["task_id"];

if (isUserLoggedIn($_SESSION["user"])){
    $sql = $pdo->prepare("DELETE FROM todos WHERE id = :idTask");
    $sql->execute(["idTask" => $idTask]);
    header("Location:/../index.php");
    exit();
} else {
    $_SESSION["msg"] = "Ошибка авторизации!";
    header("Location:/../index.php");
    exit();

}