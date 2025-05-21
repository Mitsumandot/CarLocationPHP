<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/models/User.php';

$user = new User($db);
if (isset($_POST['disconnect'])) {
    $user->disconnect();
}
if (!isset($_SESSION["nom"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 20px 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            min-width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 16px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        li {
            margin: 10px 0;
        }

        a.link-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        a.link-button:hover {
            background-color: #0056b3;
        }

        input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><?= "Bonjour " . htmlspecialchars($_SESSION["nom"]) . " " . htmlspecialchars($_SESSION["prenom"]) ?></h2>
        <p>Que voulez-vous faire ?</p>
        <ul>
            <li><a class="link-button" href="read/readCar.php">Afficher les voitures</a></li>
            <li><a class="link-button" href="read/readClient.php">Afficher les clients</a></li>
            <li><a class="link-button" href="read/readRental.php">Afficher les locations</a></li>
        </ul>

        <form method="post">
            <input type="submit" value="Se déconnecter" name="disconnect">
        </form>
    </div>
</body>

</html>
