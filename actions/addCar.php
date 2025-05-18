<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $marque = $_POST["marque"];
    $modele = $_POST["modele"];
    $annee = $_POST["annee"];
    $plaque = $_POST["plaque"];
    $prix = $_POST["prix"];
    $voiture = new Voiture($db);
    if($voiture->plaque($plaque)){
        echo "We already have this car !";
    }
    elseif($voiture->addCar($marque, $modele, $annee, 
    $plaque, $prix)){
        echo "Car added! ";
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
        Marque: <input type="text" name="marque"><br>
        Modèle: <input type="text" name="modele"><br>
        Année: <input type="number" name="annee"><br>
        plaque: <input type="number" name="plaque"><br>
        prix/jour: <input type="number" name="prix"><br>
        <input type="submit" value="Ajouter"><br>
    </form>
</body>
</html>