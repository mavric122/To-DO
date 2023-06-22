<?php
session_start();

require_once "../connect.php";
require_once "../func.php";

$idTask = $_POST["task_id"];



if (isUserLoggedIn($_COOKIE["user"])) {
    if (isset($_POST["task"]) && isset($_POST["description"])) {
//        echo $_SESSION["task_id"];


        $task = $_POST["task"];
        $description = $_POST["description"];
        $idTask = $_SESSION["task_id"];
        $sql = $pdo->prepare("UPDATE todos SET task = :task, description = :description WHERE id = :idTask");
        $sql->execute(array("idTask" => $idTask, "task" => $task, "description" => $description));
        unset($_SESSION["task_id"]);
        header("Location:/../index.php");
        exit();
    } else {
        $sql = $pdo->prepare("SELECT task, description FROM todos WHERE id = :idTask");
        $sql->execute(array("idTask" => $idTask));
        $thisTodo = $sql->fetch(PDO::FETCH_ASSOC);
        $_SESSION["task"] = $thisTodo["task"];
        $_SESSION["description"] = $thisTodo["description"];
        $_SESSION["task_id"] = $idTask;
        header("Location:/../edit_task.html");
        exit();
    }
} else {
    header("Location:/../login.html");
    $_SESSION["msg"] = "Вы не авторизованы!";
}


$editTask = $_POST["task"];
$editDescription = $_POST["description"];
