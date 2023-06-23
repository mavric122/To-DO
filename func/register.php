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


$fields = array("login", "password", "password2", "email");
$emptyFields = array();

foreach ($fields as $field) {
    if (empty($_POST[$field])) {
        $emptyFields[] = $field;
    }
}

if (!empty($emptyFields)) {
    $_SESSION["msg"] = "Следующие поля не заполнены: " . implode(", ", $emptyFields);
}


// Проверка на одинаковые пароли
if ($password !== $password2) {
    file_put_contents('log.txt', "Пароли $password и $password2 не совпадают", FILE_APPEND); // Логи
    $_SESSION["msg"] = "Пароли не совпадают";
    header('Location:../register.html');
    exit();
}

// Хеширование пароля
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Подготовка запроса SQL для проверки существования пользователя
$sql = $pdo->prepare("SELECT id FROM user WHERE login=:login OR email=:email");
$sql->execute(array("login" => $login, "email" => $email));
$user = $sql->fetch(PDO::FETCH_ASSOC);

if (!empty($user)) {
    file_put_contents('log.txt', print_r($user, true), FILE_APPEND); // Логи
    $_SESSION["msg"] = "Логин уже занят";
    header('Location:../register.html');
    exit();
}

// Регистрация нового пользователя
$sql = $pdo->prepare("INSERT INTO user (admin, login, password, email) VALUES (0, :login, :password, :email)");
$sql->execute(array("login" => $login, "password" => $hashedPassword, "email" => $email));
file_put_contents('log.txt', "Пользователь $login добавлен в базу", FILE_APPEND);// Логи
header('Location:../index.php');
exit();
?>