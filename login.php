<?php
require_once __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
$email = $_POST["email"];
$password = $_POST["password"];


$sql = "SELECT id, nom, prenom, mot_de_passe FROM utilisateur WHERE email = ?";
$request = $db->prepare($sql);
$request->execute([$email]);
$user = $request->fetch(PDO::FETCH_ASSOC);

if($user) {
    if(password_verify($password, $user["mot_de_passe"])){
        echo "Utilisateur connecté !";
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["nom"] = $user["nom"];
        $_SESSION["prenom"] = $user["prenom"];
        $_SESSION["mot_de_passe"] = $email;
        echo "Bonjour ".$_SESSION["nom"];
    }
    else {
        echo "Mot de passe incorrect.";
    }
}
else{
    echo "Email non trouvable";
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
    <form Method="post">
        Email :<input type="email" name="email"><br>
        Password :<input type="password" name="password"><br>
        <input type="submit" value="login">
    </form>
</body>
</html>