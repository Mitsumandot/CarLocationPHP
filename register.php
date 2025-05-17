<?php 
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/models.php';
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = $_POST["nom"] ?? '';
    $prenom = $_POST["prenom"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    if($user->checkMail($email)){
        echo "This email is already used.";
    }
    elseif($user->createUser($nom, $prenom, $email, $passwordHash)){
        echo "User created !";
    }
    else {
        echo "Error during inscription, please try again";
    }


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
        Nom: <input type="text" name="nom" required><br>
        Prenom: <input type="text" name="prenom" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>