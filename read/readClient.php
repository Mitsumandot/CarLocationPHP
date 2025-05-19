<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models.php';
$client = new Client($db);
$clients = $client->getClients();
if(isset($_POST["Delete"])){
    $id = $_POST["id"];
    $client->deleteClient($id);
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
    <?php foreach($clients as $client) { ?>
        <div>
            <div>Nom: <?php echo $client["nom"] ?></div>
            <div>Prénom: <?php echo $client["prenom"] ?></div>
            <div>Téléphone: <?php echo $client["telephone"] ?></div>
            <div>CIN: <?php echo $client["cin"] ?></div>
            <div class="input">
                <form method="post">
                    <input type="hidden" name="id" value=<?php echo $client["id"]?>>
                    <input type="submit" value="Update" name="Update">
                </form>
                <form method="post" onsubmit="return confirm('Êtes‑vous sûr de vouloir supprimer ce client ?');">
                    <input type="hidden" name="id" value=<?php echo $client["id"]?>>
                    <input type="submit" value="Delete" name="Delete">
                </form>
            </div>
        </div>
    <?php }?>
</body>
</html>