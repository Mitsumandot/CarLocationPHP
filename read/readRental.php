<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../models/Voiture.php';
$locationClass = new Location($db);
$locations = $locationClass->getLocations();
$car = new Voiture($db);
$client = new Client($db);
$clients = $client->getClients();
$voitures = $car->getCars();
if (isset($_POST["Delete"])) {
    $id = $_POST["id"];
    $locationClass->deleteLocation($id);
    unset($_POST["Delete"]);
}

if (isset($_POST["Save"])) {
    $client_id = $_POST["Client"];
    $voiture_id = $_POST["voiture"];
    $dateDebut = $_POST["date_debut"];
    $dateFin = $_POST["date_fin"];
    $id = $_POST["id"];
    $locationClass->updateLocation(
        $client_id,
        $voiture_id,
        $dateDebut,
        $dateFin,
        $id
    );
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
    <?php foreach ($locations as $location) { ?>
        <?php if (!isset($_POST[$location["id"]])) { ?>
            <div>
                <div>Voiture : <?php echo $car->getCarName($location["voiture_id"]) ?></div>
                <div>Client : <?php echo $client->getClientName($location["client_id"]) ?></div>
                <div>Date de début de location : <?php echo $location["date_debut"] ?></div>
                <div>Date de fin de location : <?php echo $location["date_fin"] ?></div>
                <div>Prix total de la location: <?php echo $locationClass->getTotalPrix($location["id"]) ?></div>
                <div class="input">
                    <form method="post">
                        <input type="hidden" name=<?php echo $location["id"] ?> value=<?php echo $location["id"] ?>>
                        <input type="submit" value="Update" name="Update">
                    </form>
                    <form method="post" onsubmit="return confirm('Êtes‑vous sûr de vouloir supprimer cette voiture ?');">
                        <input type="hidden" name="id" value=<?php echo $location["id"] ?>>
                        <input type="submit" value="Delete" name="Delete">
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <form method="post">
                Voiture:
                <select name="voiture">
                    <?php foreach ($voitures as $voiture) { ?>
                        <option value=<?php echo $voiture["id"] ?> <?php if ($voiture['id'] == $location['voiture_id']) echo 'selected'; ?>>
                            <?php echo $voiture["marque"] . " " . $voiture["modèle"] . " " . $voiture["plaque"] ?>
                        </option>

                    <?php } ?>
                </select><br>
                Client:
                <select name="Client">
                    <?php foreach ($clients as $clientForm) { ?>
                        <option value=<?php echo $clientForm["id"] ?> <?php if ($clientForm['id'] == $location['client_id']) echo 'selected'; ?>>
                            <?php echo $clientForm["nom"] . " " . $clientForm["prenom"] ?>
                        </option>
                    <?php } ?>
                </select><br>
                Date de début de location:
                <input type="date" name="date_debut" value=<?php echo $location["date_debut"] ?>><br>
                Date de fin de location:
                <input type="date" name="date_fin" value=<?php echo $location["date_fin"] ?>><br>
                <input type="hidden" name="id" value=<?php echo $location["id"] ?>>
                <input type="submit" value="Save" name="Save">
            </form>

        <?php unset($_POST["Update"]);
        } ?>
    <?php } ?>
</body>

</html>