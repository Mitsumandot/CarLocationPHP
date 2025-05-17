<?php 
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = $_POST["nom"] ?? '';
    $prenom = $_POST["prenom"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    
    
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
    
    $request = $db->prepare($sql);
    
    try {
        $request->execute([$nom, $prenom, $email, $passwordhash]);
        echo "Inscription réussite !";
    }
    catch(PDOException $e){
        if($e->getCode() == 2300){
            echo "Ce nom d'utilisateur existe déjà !";
        }
        else {
            echo "Erreur:".$e->getMessage();
        }
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