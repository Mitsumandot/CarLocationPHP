<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models.php';
$locationClass = new Location($db);
$locations = $locationClass->getLocations();
$car = new Voiture($db);
$client = new Client($db);
if(isset($_POST["Delete"])){
    $id = $_POST["id"];
    $locationClass->deleteLocation($id);
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
    <?php foreach($locations as $location) { ?>
        <div>
            <div>Voiture : <?php echo $car->getCarName($location["voiture_id"]) ?></div>
            <div>Client : <?php echo $client->getClientName($location["client_id"]) ?></div>
            <div>Date de début de location : <?php echo $location["date_debut"] ?></div>
            <div>Date de fin de location : <?php echo $location["date_fin"] ?></div>
            <div>Prix total de la location: <?php echo $locationClass->getTotalPrix($location["id"])?></div>
            <div class="input">
                <form method="post">
                    <input type="hidden" name="id" value=<?php echo $location["id"]?>>
                    <input type="submit" value="Update" name="Update">
                </form>
                <form method="post" onsubmit="return confirm('Êtes‑vous sûr de vouloir supprimer cette voiture ?');">
                    <input type="hidden" name="id" value=<?php echo $location["id"]?>>
                    <input type="submit" value="Delete" name="Delete">
                </form>
            </div>
        </div>
    <?php }?>
</body>
</html>