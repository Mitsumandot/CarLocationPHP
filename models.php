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

    public function getCars(){
        $db = $this->pdo;
        $sql = "SELECT marque, modèle, id FROM voiture";
        $request = $db->prepare($sql);
        $request->execute();
        $cars = $request->fetchAll(PDO::FETCH_ASSOC);
        return $cars;
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

    public function getClients(){
        $db = $this->pdo;
        $sql = "SELECT prenom, nom, id FROM client";
        $request = $db->prepare($sql);
        $request->execute();
        $clients = $request->fetchAll(PDO::FETCH_ASSOC);
        return $clients;

    }
}

class Location {
    private $pdo;

    function __construct($db){
        $this->pdo = $db;
    }


    public function client($id){
        $db = $this->pdo;

        $sql = "SELECT COUNT(*) FROM location WHERE client_id = ?";
        $request = $db->prepare($sql);
        $request->execute([$id]);

        return $request->fetchColumn() > 0;
    }
    public function car($id){
        $db = $this->pdo;

        $sql = "SELECT COUNT(*) FROM location WHERE voiture_id = ?";
        $request = $db->prepare($sql);
        $request->execute([$id]);


        return $request->fetchColumn() > 0;
    }

    public function checkClientDate($id, $dateDebut, $dateFin){
        $sql = "SELECT COUNT(*) FROM location WHERE client_id = ? 
        AND NOT ( date_fin <= ? OR date_debut >= ?)";
        $db = $this->pdo;
        $request = $db->prepare($sql);
        $request->execute([$id, $dateDebut, $dateFin]);
        return $request->fetchColumn() > 0;


    }

    public function checkCarDate($id, $dateDebut, $dateFin){
        $sql = "SELECT COUNT(*) FROM location WHERE voiture_id = ? 
        AND NOT ( date_fin <= ? OR date_debut >= ?)";
        $db = $this->pdo;
        $request = $db->prepare($sql);
        $request->execute([$id, $dateDebut, $dateFin]);
        return $request->fetchColumn() > 0;
        
    }

    public function addLocation($voiture_id, $client_id, $dateDebut, $dateFin){
        $db = $this->pdo;
        $sql = "INSERT INTO location (voiture_id, client_id, date_debut, date_fin) VALUES (?, ?, ?, ?)";
        $request = $db->prepare($sql);
        if($this->car($voiture_id)){
            if($this->checkCarDate($voiture_id, $dateDebut, $dateFin)){
                echo "This car is already rented during this date !";
                return;
            }
        }
        try{
            $request->execute([$voiture_id, $client_id, $dateDebut, $dateFin]);
            echo "Location added to the data base";
        }
        catch(PDOException $e){
            echo "Erreur".$e->getMessage();
        }
        
        
    }
}








?>