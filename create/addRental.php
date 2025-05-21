<?php

session_start();
if (!isset($_SESSION["nom"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../models/Voiture.php';

$location = new Location($db);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['Client'];
    $voiture_id = $_POST['voiture'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    if ($location->addLocation($voiture_id, $client_id, $date_debut, $date_fin)) {
        $message = "Location ajoutée avec succès !";
    } else {
        $message = "Erreur lors de l'ajout de la location.";
    }
}

$client = new Client($db);
$clients = $client->getClients();

$car = new Voiture($db);
$voitures = $car->getCars();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ajouter une location</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 480px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 6px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        select,
        input[type="date"] {
            padding: 8px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .message {
            font-weight: bold;
            margin-bottom: 15px;
            color: green;
        }
    </style>
</head>

<body>
    <a href="../read/readRental.php">Back</a>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    
    <form method="post" novalidate>
        <label for="Client">Client :</label>
        <select name="Client" id="Client" required>
            <?php foreach ($clients as $client) { ?>
                <option value="<?= $client["id"] ?>">
                    <?= htmlspecialchars($client["nom"] . " " . $client["prenom"]) ?>
                </option>
            <?php } ?>
        </select>

        <label for="voiture">Voiture :</label>
        <select name="voiture" id="voiture" required>
            <?php foreach ($voitures as $voiture) { ?>
                <option value="<?= $voiture["id"] ?>">
                    <?= htmlspecialchars($voiture["marque"] . " " . $voiture["modèle"]) ?>
                </option>
            <?php } ?>
        </select>

        <label for="date_debut">Date de début de location :</label>
        <input type="date" name="date_debut" id="date_debut" required>

        <label for="date_fin">Date de fin de location :</label>
        <input type="date" name="date_fin" id="date_fin" required>

        <input type="submit" value="Ajouter">
    </form>

</body>

</html>
