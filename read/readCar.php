<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models.php';
$car = new Voiture($db);
$cars = $car->getCars();
if(isset($_POST["Delete"])){
    $id = $_POST["id"];
    $car->deleteCar($id);
    unset($_POST["Delete"]);
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
            display:flex;
            flex-direction:column;
            gap:20px;
        }
        body > div {
            display:flex;
            flex-direction:column;
            gap:5px;
        }
        .input {
            display:flex;
            gap:5px;
        }
    </style>
</head>
<body>
    <?php foreach($cars as $car) { ?>
        <div>
            <div>Marque : <?php echo $car["marque"] ?></div>
            <div>Modèle : <?php echo $car["modèle"] ?></div>
            <div>Année : <?php echo $car["annee"] ?></div>
            <div>Plaque : <?php echo $car["plaque"] ?></div>
            <div>Prix par jour: <?php echo $car["prix_jour"] ?></div>
            <div class="input">
                <form method="post">
                    <input type="hidden" name="id" value=<?php echo $car["id"]?>>
                    <input type="submit" value="Update" name="Update">
                </form>
                <form method="post" onsubmit="return confirm('Êtes‑vous sûr de vouloir supprimer cette voiture ?');">
                    <input type="hidden" name="id" value=<?php echo $car["id"]?>>
                    <input type="submit" value="Delete" name="Delete">
                </form>
            </div>
        </div>
    <?php }?>
</body>
</html>