<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Client.php';

session_start();
if (!isset($_SESSION["nom"])) {
    header("Location: ../login.php");
    exit();
}

$client = new Client($db);

if (isset($_POST["Delete"])) {
    $id = $_POST["id"];
    $client->deleteClient($id);
    unset($_POST["Delete"]);
}

if (isset($_POST["Save"])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $telephone = $_POST["telephone"];
    $cin = $_POST["cin"];
    $id = $_POST["id"];
    $client->updateClient($nom, $prenom, $telephone, $cin, $id);
}

$clients = $client->getClients();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .client-card {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .client-card div {
            margin: 4px 0;
        }

        .input {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        input[type="text"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            margin: 3px 0;
        }

        input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        form {
            margin: 0;
        }
    </style>
</head>

<body>
    <a href="../home.php">Back</a>

    <a href="../create/addClient.php">+ Ajouter un nouveau client</a>

    <?php foreach ($clients as $client) { ?>
        <?php if (!isset($_POST[$client["id"]])) { ?>
            <div class="client-card">
                <div><strong>Nom :</strong> <?= htmlspecialchars($client["nom"]) ?></div>
                <div><strong>Prénom :</strong> <?= htmlspecialchars($client["prenom"]) ?></div>
                <div><strong>Téléphone :</strong> <?= htmlspecialchars($client["telephone"]) ?></div>
                <div><strong>CIN :</strong> <?= htmlspecialchars($client["cin"]) ?></div>
                <div class="input">
                    <form method="post">
                        <input type="hidden" name="<?= $client["id"] ?>" value="<?= $client["id"] ?>">
                        <input type="submit" value="Modifier" name="Update">
                    </form>
                    <form method="post" onsubmit="return confirm('Êtes‑vous sûr de vouloir supprimer ce client ?');">
                        <input type="hidden" name="id" value="<?= $client["id"] ?>">
                        <input type="submit" value="Supprimer" name="Delete">
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="client-card">
                <form method="post">
                    <input type="text" name="nom" value="<?= htmlspecialchars($client["nom"]) ?>" placeholder="Nom">
                    <input type="text" name="prenom" value="<?= htmlspecialchars($client["prenom"]) ?>" placeholder="Prénom">
                    <input type="text" name="telephone" value="<?= htmlspecialchars($client["telephone"]) ?>" placeholder="Téléphone">
                    <input type="text" name="cin" value="<?= htmlspecialchars($client["cin"]) ?>" placeholder="CIN">
                    <input type="hidden" name="id" value="<?= $client["id"] ?>">
                    <input type="submit" value="Enregistrer" name="Save">
                </form>
            </div>
        <?php unset($_POST["Update"]);
        } ?>
    <?php } ?>

</body>

</html>
