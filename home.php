<?php 
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/models.php';
$user = new User($db);
if(isset($_POST['disconnect'])){
    $user->disconnect();
}
if(!isset($_SESSION["nom"])){
    header("Location: login.php");
    exit();
}
if(isset($_POST["action"])){
    header("Location: actions/" . $_POST["action"] . ".php");
    exit();
}
echo "Bonjour ".$_SESSION["nom"]." ".$_SESSION["prenom"];



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        Choisir une acion parmi les actions suivantes :
        <select name="action">
            <option value="addCar">Ajouter voiture</option>
            <option value="addRental">Ajouter location</option>
            <option value="addClient">Ajouter client</option>
        </select>
        <input type="submit" value="choisir">
    </form>
    <form method="post">
        <input type="submit" value="Se deconnecter" name="disconnect">
    </form>
</body>
</html>