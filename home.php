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
if(isset($_POST["create"])){
    header("Location: create/" . $_POST["create"] . ".php");
    exit();
}
echo "Bonjour ".$_SESSION["nom"]." ".$_SESSION["prenom"];

if(isset($_POST["read"])){
    header("Location: read/". $_POST["read"] . ".php");
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
    <form method="post">
        Choisir une acion parmi les actions suivantes :
        <select name="create">
            <option value="addCar">Ajouter voiture</option>
            <option value="addRental">Ajouter location</option>
            <option value="addClient">Ajouter client</option>
        </select>
        <input type="submit" value="choisir">
    </form>
    <form Method="post">
        Choisir quoi afficher:
        <select name="read">
            <option value="readCar">Afficher les voitures</option>
            <option value="readClient">Afficher les clients</option>
            <option value="readRental">Afficher les locations</option>
        </select>
        <input type="submit" value="choisir">
    </form>
    <form method="post">
        <input type="submit" value="Se deconnecter" name="disconnect">
    </form>
</body>
</html>