<?php
require_once __DIR__ . '/db.php';

class User
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function checkMail($email)
    {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = ?";
        $request = $this->pdo->prepare($sql);
        $request->execute([$email]);
        return $request->fetchColumn() > 0;
    }
    public function createUser($nom, $prenom, $email, $mdp)
    {
        $db = $this->pdo;
        $sql = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
        $request = $db->prepare($sql);
        try {
            $request->execute([$nom, $prenom, $email, $mdp]);
            echo "Inscription réussite !";
        } catch (PDOException $e) {
            if ($e->getCode() == 2300) {
                echo "Ce nom d'utilisateur existe déjà !";
            } else {
                echo "Erreur:" . $e->getMessage();
            }
        }
    }
    public function login($email, $password)
    {
        $sql = "SELECT id, nom, prenom, mot_de_passe FROM utilisateur WHERE email = ?";
        $request = $this->pdo->prepare($sql);
        $request->execute([$email]);
        $user = $request->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user["mot_de_passe"])) {
                echo "Utilisateur connecté !";
                session_start();
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["nom"] = $user["nom"];
                $_SESSION["prenom"] = $user["prenom"];
                $_SESSION["email"] = $email;
                echo "Bonjour " . $_SESSION["nom"];
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Email non trouvable";
        }
    }
    public function disconnect()
    {
        session_start();
        $_SESSION = [];
        session_destroy();

    }
}

class Voiture
{
    private $pdo;

    function __construct($db)
    {
        $this->pdo = $db;
    }

    public function plaque($plaque)
    {
        $db = $this->pdo;
        $sql = "SELECT COUNT(*) FROM voiture WHERE plaque = ?";
        $request = $db->prepare($sql);
        $request->execute([$plaque]);
        return $request->fetchColumn() > 0;

    }

    public function addCar($marque, $modele, $annee, $plaque, $prix)
    {
        $db = $this->pdo;
        $sql = "INSERT INTO voiture (marque, modèle, annee, plaque, prix_jour) VALUES (?, ?, ?, ?, ?)";
        $request = $db->prepare($sql);
        try {
            $request->execute([$marque, $modele, $annee, $plaque, $prix]);
            echo "Voiture ajoutée";
        } catch (PDOException $e) {
            echo "Erreur" . $e->getMessage();
        }
    }
}

class Client
{
    private $pdo;

    function __construct($db)
    {
        $this->pdo = $db;
    }

    public function cin($cin)
    {
        $db = $this->pdo;
        $sql = "SELECT COUNT(*) FROM client WHERE cin = ?";
        $request = $db->prepare($sql);
        $request->execute([$cin]);
        return $request->fetchColumn() > 0;
    }

    public function addClient($nom, $prenom, $telephone, $cin)
    {
        $db = $this->pdo;
        $sql = "INSERT INTO client (nom, prenom, telephone, cin) VALUES (?, ?, ?, ?)";
        $request = $db->prepare($sql);
        try {
            $request->execute([$nom, $prenom, $telephone, $cin]);
            echo "Client add to the database";
        } catch (PDOException $e) {
            echo "Erreur" . $e->getMessage();
        }
    }
}








?>