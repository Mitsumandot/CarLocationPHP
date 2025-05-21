<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/models/User.php';
$user = new User($db);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST["nom"] ?? '';
    $prenom = $_POST["prenom"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($user->checkMail($email)) {
        $message = "This email is already used.";
    } elseif ($user->createUser($nom, $prenom, $email, $passwordHash)) {
        $message = "User created!";
    } else {
        $message = "Error during inscription, please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
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

        form {
            background: white;
            padding: 20px 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            min-width: 280px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .message {
            margin-bottom: 10px;
            font-size: 14px;
            color: red;
            text-align: center;
        }

        .back-link {
            margin-top: 12px;
            display: block;
            text-align: center;
            font-size: 14px;
        }

        .back-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <form method="post">
        <?php if (!empty($message)) echo '<div class="message">' . htmlspecialchars($message) . '</div>'; ?>
        <label>Nom:</label><br>
        <input type="text" name="nom" required><br>
        <label>Prénom:</label><br>
        <input type="text" name="prenom" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Mot de passe:</label><br>
        <input type="password" name="password" required><br>
        <input type="submit" value="S'inscrire">
        <div class="back-link">
            <a href="login.php">Already have an account? Login</a>
        </div>
    </form>
</body>

</html>
