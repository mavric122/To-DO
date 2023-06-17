<?php
session_start();
require_once "../connect.php";

$data = json_decode($_COOKIE["user"], true);

$login = $data["login"];
$idTask = $_SESSION["idTask"];
$tokenUser = $data["token"];

$sql = $pdo->prepare("SELECT user_token FROM user WHERE login = :login");
$sql->execute(array("login" => $login));
$tokenUserBD = $sql->fetch(PDO::FETCH_ASSOC);

if ($tokenUser == $tokenUserBD["user_token"]){
    $sql = $pdo->prepare("UPDATE todos SET status = 0 WHERE id = :idTask");
    $sql->execute([":idTask" => $idTask]);
    header("Location:/../index.php");
    exit();
} else {
    $_SESSION["msg"] = "Вы не авторизованы!";
    header("Location:/../login.php");
    exit();
}


