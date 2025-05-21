<?php

session_start();
if (!isset($_SESSION["nom"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Voiture.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voiture = new Voiture($db);
    if ($voiture->plaque($_POST['plaque'])) {
        echo '<p class="message">Cette voiture existe déjà !</p>';
    } elseif ($voiture->addCar(
        $_POST['marque'],
        $_POST['modele'],
        $_POST['annee'],
        $_POST['plaque'],
        $_POST['prix']
    )) {
        echo '<p style="color:green; font-weight:bold;">Voiture ajoutée !</p>';
    } else {
        echo '<p class="message">Erreur lors de l\'ajout.</p>';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ajouter une voiture</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            gap: 15px;
            font-family: sans-serif;
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 300px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 1rem;
        }

        input[type="submit"] {
            padding: 7px;
            border: none;
            border-radius: 3px;
            background-color: #333;
            color: white;
            cursor: pointer;
            font-size: 1rem;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <a href="../read/readCar.php">Back</a>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($message)) {
            echo '<p class="message">' . htmlspecialchars($message) . '</p>';
        }
    }
    ?>
    <form method="post">
        <label for="marque">Marque :</label>
        <input type="text" id="marque" name="marque" required>

        <label for="modele">Modèle :</label>
        <input type="text" id="modele" name="modele" required>

        <label for="annee">Année :</label>
        <input type="number" id="annee" name="annee" min="1900" max="2100" required>

        <label for="plaque">Plaque :</label>
        <input type="text" id="plaque" name="plaque" required>

        <label for="prix">Prix/jour :</label>
        <input type="number" id="prix" name="prix" step="0.01" min="0" required>

        <input type="submit" value="Ajouter">
    </form>
</body>

</html>
