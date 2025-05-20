<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../models/Voiture.php';
$client = new Client($db);
$clients = $client->getClients();

$car = new Voiture($db);
$voitures = $car->getCars();

$location = new Location($db);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $client_id = $_POST['Client'];
    $voiture_id = $_POST['voiture'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    
    $location->addLocation($voiture_id, $client_id, $date_debut, $date_fin);
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
        Client:
        <select name="Client">
            <?php foreach($clients as $client){?>
                <option value=<?php echo $client["id"]?>><?php echo $client["nom"]. " " . $client["prenom"]?></option>
            <?php } ?>
        </select><br>
        Voiture:
        <select name="voiture"> 
            <?php foreach($voitures as $voiture){?>
                <option value=<?php echo $voiture["id"]?>><?php echo $voiture["marque"]. " ". $voiture["modèle"] ?></option>

            
            <?php } ?>
            
        </select><br>
        Date de début de location:
        <input type="date" name="date_debut"><br>
        Date de fin de location:
        <input type="date" name="date_fin"><br>
        <input type="submit" value="Ajouter">
    </form>
</body>
</html>