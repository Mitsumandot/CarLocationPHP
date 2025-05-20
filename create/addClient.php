<?php

session_start();
if (!isset($_SESSION["nom"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Client.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $telephone = $_POST["telephone"];
    $cin = $_POST["cin"];
    $client = new Client($db);
    if ($client->cin($cin)) {
        echo "Client already exists in the database";
    } else {
        $client->addClient($nom, $prenom, $telephone, $cin);
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
    <form method="post">
        Nom: <input type="text" name="nom"><br>
        Prenom: <input type="text" name="prenom"><br>
        Téléphone: <input type="text" name="telephone"><br>
        cin: <input type="text" name="cin"><br>
        <input type="submit" value="Ajouter"><br>
    </form>
</body>

</html>