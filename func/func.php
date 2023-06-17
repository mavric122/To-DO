<?php


function isUserLoggedIn($cookies):
{
    $data = json_decode($cookies, true);

    $login = $data["login"];
    $cookieToken = $data["token"];

    $sql = $pdo->prepare("SELECT user_token FROM user WHERE login = :login");
    $sql->execute(array("login" => $login));
    $tokenUserBD = $sql->fetch(PDO::FETCH_ASSOC);

    if ($cookieToken == $tokenUserBD["user_token"]) {
        return true;
    } else {
        return false;
    }
}
