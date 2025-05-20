<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/models/User.php';

$user = new User($db);
if (isset($_POST['disconnect'])) {
    $user->disconnect();
}
if (!isset($_SESSION["nom"])) {
    header("Location: login.php");
    exit();
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
    <h2><?= "Bonjour " . $_SESSION["nom"] . " " . $_SESSION["prenom"] ?></h2>

    <p>Que voulez-vous faire ?</p>
    <ul>
        <li><a href="read/readCar.php">Afficher les voitures</a></li>
        <li><a href="read/readClient.php">Afficher les clients</a></li>
        <li><a href="read/readRental.php">Afficher les locations</a></li>
    </ul>

    <form method="post">
        <input type="submit" value="Se deconnecter" name="disconnect">
    </form>
</body>

</html>