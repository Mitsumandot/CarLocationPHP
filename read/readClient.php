<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/Client.php';
$client = new Client($db);
$clients = $client->getClients();
if (isset($_POST["Delete"])) {
    $id = $_POST["id"];
    $client->deleteClient($id);
    unset($_POST["Delete"]);
}

if(isset($_POST["Save"])){
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $telephone = $_POST["telephone"];
    $cin = $_POST["cin"];
    $id = $_POST["id"];
    $client->updateClient($nom, $prenom, $telephone, $cin, $id);
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
    <?php foreach ($clients as $client) { ?>
        <?php if (!isset($_POST[$client["id"]])) { ?>
            <div>
                <div>Nom: <?php echo $client["nom"] ?></div>
                <div>Prénom: <?php echo $client["prenom"] ?></div>
                <div>Téléphone: <?php echo $client["telephone"] ?></div>
                <div>CIN: <?php echo $client["cin"] ?></div>
                <div class="input">
                    <form method="post">
                        <input type="hidden" name=<?php echo $client["id"] ?> value=<?php echo $client["id"] ?>>
                        <input type="submit" value="Update" name="Update">
                    </form>
                    <form method="post" onsubmit="return confirm('Êtes‑vous sûr de vouloir supprimer ce client ?');">
                        <input type="hidden" name="id" value=<?php echo $client["id"] ?>>
                        <input type="submit" value="Delete" name="Delete">
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <form method="post">
                Nom: <input type="text" name="nom" value="<?php echo $client["nom"]?>"><br>
                Prenom: <input type="text" name="prenom" value="<?php echo $client["prenom"]?>"><br>
                Téléphone: <input type="text" name="telephone" value="<?php echo $client["telephone"]?>"><br>
                cin: <input type="text" name="cin" value="<?php echo $client["cin"]?>"><br>
                <input type="hidden" name="id" value=<?php echo $client["id"] ?>>
                <input type="submit" value="Save" name="Save"><br>
            </form>


        <?php 
        unset($_POST["Update"]);
        } ?>
    <?php } ?>
</body>

</html>