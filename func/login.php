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

        $data = array(
            "token" => $user["user_token"],
            "login" => $user["login"],
            "user_id" => $user["id"],
        );

        if (!empty($data["token"])) { // Если токен есть.
            setcookie("user", json_encode($data), time() + 86400, "/");
            header("Location:../index.php");
            exit();
        } else {
            try {
                $token = bin2hex(random_bytes(16)); // Если нет, то создаём.
                $id_user = $user["id"];
                $data["token"] = $token;
                $newTokenBD = $pdo->prepare("UPDATE user SET user_token = :token WHERE id = :id_user"); // Записываем в БД
                $newTokenBD->execute([':token' => $token, ':id_user' => $id_user]);
                setcookie("user", json_encode($data), time() + 86400, "/");
                header("Location:../index.php");
                exit();
            } catch (Exception $e) {
                echo 'Выброшено исключение: ', $e->getMessage(), "\n";
            }
        }
    }
    else {
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