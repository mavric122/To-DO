<?php
session_start();
require_once "../func/connect.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Блок для логирования
$date = date('Y-m-d H:i:s');
$log_text = "\n-------------- $date -----------------\n";
if (!file_put_contents('log.txt', $log_text, FILE_APPEND)) {
    echo "Не удалось записать в файл лога.";
    exit;
}
// Конец блока для логирования

$login = $_POST["login"];
$password = $_POST["password"];
$password2 = $_POST["password2"];
$email = $_POST["email"];

function testPassword($password, $password2) // проверка что пароли совпадают
{
    if ($password === $password2) {
        return true;
    } else {
        return false;
    }
}

// подготовка запроса SQL, который будет использоваться для поиска пользователя в базе данных по логину, паролю и email.
$sql = $pdo->prepare("SELECT id FROM user WHERE login=:login OR password=:password OR email=:email");
// выполнение подготовленного запроса SQL с передачей параметров логина, пароля и email пользователя.
$sql->execute(array("login" => $login, "password" => $password, "email" => $email));
//  получение результата запроса SQL в виде ассоциативного массива.
$array = $sql->fetch(PDO::FETCH_ASSOC);

if (!empty($array)) {
    file_put_contents('log.txt', print_r($array, true), FILE_APPEND); // Логи
}



if (testPassword($password, $password2)) { // Проверка на одинаковые пароли
    if ($array["id"] == 0) {
        $sql = $pdo->prepare("INSERT INTO user (admin, login, password, email) VALUES (0,:login, :password, :email)");
        $sql->execute(array("login" => $login, "password" => $password, "email" => $email));
        $_SESSION["login"] = $login;
        file_put_contents('log.txt', "Пользователь $login добавлен в базу", FILE_APPEND);// Логи
        header('Location:../admin.php');
    } else {
        file_put_contents('log.txt', "Пользователь $login уже зарегистрирован", FILE_APPEND);// Логи
        echo "Пользователь уже зарегистрирован";
        header('Location:../register.html');
    }
} else {
    file_put_contents('log.txt', "Пароли $password и $password2 не совпадают", FILE_APPEND); // Логи
    echo "Пароли не совпадают";
    header('Location:../register.html');
}

?>