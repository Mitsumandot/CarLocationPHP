<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Voiture.php';

$car = new Voiture($db);
$cars = $car->getCars();
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        body>div {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .input {
            display: flex;
            gap: 5px;
        }
    </style>
</head>

<body>
    <p><a href="../create/addCar.php">Ajouter une nouvelle voiture</a></p>

    <?php foreach ($cars as $car) { ?>
        <?php if (!isset($_POST[$car["id"]])) { ?>
            <div>
                <div>Marque : <?php echo $car["marque"] ?></div>
                <div>Modèle : <?php echo $car["modèle"] ?></div>
                <div>Année : <?php echo $car["annee"] ?></div>
                <div>Plaque : <?php echo $car["plaque"] ?></div>
                <div>Prix par jour: <?php echo $car["prix_jour"] ?></div>
                <div class="input">
                    <form method="post">
                        <input type="hidden" name=<?php echo $car["id"] ?> value=<?php echo $car["id"] ?>>
                        <input type="submit" value="Update" name="Update">
                    </form>
                    <form method="post" onsubmit="return confirm('Êtes‑vous sûr de vouloir supprimer cette voiture ?');">
                        <input type="hidden" name="id" value=<?php echo $car["id"] ?>>
                        <input type="submit" value="Delete" name="Delete">
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div>
                <form method="post">
                    Marque :<input type="text" name="marque" value="<?php echo $car["marque"] ?>"><br>
                    Modèle :<input type="text" name="modele" value="<?php echo $car["modèle"] ?>"><br>
                    Année :<input type="text" name="annee" value="<?php echo $car["annee"] ?>"><br>
                    Plaque :<input type="text" name="plaque" value="<?php echo $car["plaque"] ?>"><br>
                    Prix par jour :<input type="text" name="prix_jour" value="<?php echo $car["prix_jour"] ?>"><br>
                    <input type="hidden" name="id" value=<?php echo $car["id"] ?>>
                    <input type="submit" value="Save" name="Save">
                </form>
            </div>
        <?php
            unset($_POST["Update"]);
        } ?>

    <?php } ?>
</body>

</html>