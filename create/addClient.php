<?php
session_start();
if (!isset($_SESSION["nom"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Client.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $telephone = trim($_POST["telephone"]);
    $cin = trim($_POST["cin"]);
    $client = new Client($db);

    if ($client->cin($cin)) {
        $message = "Ce client existe déjà dans la base de données.";
    } else {
        if ($client->addClient($nom, $prenom, $telephone, $cin)) {
            $message = "Client ajouté avec succès !";
        } else {
            $message = "Erreur lors de l'ajout du client.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ajouter un client</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            padding: 6px;
            font-size: 1rem;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            padding: 8px;
            font-size: 1rem;
            border: none;
            border-radius: 3px;
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .message {
            font-weight: bold;
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <a href="../read/readClient.php">Back</a>

    <?php if ($message) : ?>
        <p class="message <?= strpos($message, 'succès') !== false ? 'success' : '' ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="post" novalidate>
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="telephone">Téléphone :</label>
        <input type="text" id="telephone" name="telephone" required>

        <label for="cin">CIN :</label>
        <input type="text" id="cin" name="cin" required>

        <input type="submit" value="Ajouter">
    </form>

</body>

</html>
