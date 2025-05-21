<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Voiture.php';

session_start();
if (!isset($_SESSION["nom"])) {
    header("Location: ../login.php");
    exit();
}

$car = new Voiture($db);
if (isset($_POST["Delete"])) {
    $id = $_POST["id"];
    $car->deleteCar($id);
    unset($_POST["Delete"]);
}
if (isset($_POST["Save"])) {
    $marque = $_POST["marque"];
    $modele = $_POST["modele"];
    $annee = $_POST["annee"];
    $plaque = $_POST["plaque"];
    $prixJour = $_POST["prix_jour"];
    $id = $_POST["id"];
    $car->updateCar($marque, $modele, $annee, $plaque, $prixJour, $id);
}
$cars = $car->getCars();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voitures</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
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

        .car-card {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .car-card div {
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
    <a href="../create/addCar.php">+ Ajouter une nouvelle voiture</a>

    <?php foreach ($cars as $car) { ?>
        <?php if (!isset($_POST[$car["id"]])) { ?>
            <div class="car-card">
                <div><strong>Marque :</strong> <?= htmlspecialchars($car["marque"]) ?></div>
                <div><strong>Modèle :</strong> <?= htmlspecialchars($car["modèle"]) ?></div>
                <div><strong>Année :</strong> <?= htmlspecialchars($car["annee"]) ?></div>
                <div><strong>Plaque :</strong> <?= htmlspecialchars($car["plaque"]) ?></div>
                <div><strong>Prix par jour :</strong> <?= htmlspecialchars($car["prix_jour"]) ?>€</div>
                <div class="input">
                    <form method="post">
                        <input type="hidden" name="<?= $car["id"] ?>" value="<?= $car["id"] ?>">
                        <input type="submit" value="Modifier" name="Update">
                    </form>
                    <form method="post" onsubmit="return confirm('Êtes‑vous sûr de vouloir supprimer cette voiture ?');">
                        <input type="hidden" name="id" value="<?= $car["id"] ?>">
                        <input type="submit" value="Supprimer" name="Delete">
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="car-card">
                <form method="post">
                    <input type="text" name="marque" value="<?= htmlspecialchars($car["marque"]) ?>" placeholder="Marque">
                    <input type="text" name="modele" value="<?= htmlspecialchars($car["modèle"]) ?>" placeholder="Modèle">
                    <input type="text" name="annee" value="<?= htmlspecialchars($car["annee"]) ?>" placeholder="Année">
                    <input type="text" name="plaque" value="<?= htmlspecialchars($car["plaque"]) ?>" placeholder="Plaque">
                    <input type="text" name="prix_jour" value="<?= htmlspecialchars($car["prix_jour"]) ?>" placeholder="Prix/jour">
                    <input type="hidden" name="id" value="<?= $car["id"] ?>">
                    <input type="submit" value="Enregistrer" name="Save">
                </form>
            </div>
        <?php unset($_POST["Update"]);
        } ?>
    <?php } ?>
</body>

</html>
