<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/models/User.php';
session_start();
if (isset($_SESSION["nom"])) {
    header("Location: home.php");
    echo "Hello";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $user = new User($db);

    $user->login($email, $password);
    if (isset($_POST["email"])) {
        $_POST["email"] = NULL;
        $_POST["password"] = NULL;
        header("Location: home.php");
        exit();
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form Method="post">
        Email :<input type="email" name="email"><br>
        Password :<input type="password" name="password"><br>
        <input type="submit" value="login">
    </form>
</body>

</html>