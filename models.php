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

    public function getCars()
    {
        $db = $this->pdo;
        $sql = "SELECT marque, modèle, id, annee, plaque, prix_jour FROM voiture";
        $request = $db->prepare($sql);
        $request->execute();
        $cars = $request->fetchAll(PDO::FETCH_ASSOC);
        return $cars;
    }

    public function checkFuturLocation($id)
    {
        $db = $this->pdo;
        $sql = "SELECT COUNT(*) FROM location WHERE voiture_id = ? AND date_fin > CURRENT_DATE()";
        $request = $db->prepare($sql);
        $request->execute([$id]);
        return $request->fetchColumn() > 0;

    }

    public function deleteCar($id)
    {
        if ($this->checkFuturLocation($id)) {
            echo "This car has futur locations, you cant delete it !";
            return;
        }
        try {
            $db = $this->pdo;
            $sql = "DELETE FROM voiture WHERE id = ?";
            $request = $db->prepare($sql);
            $request->execute([$id]);
            echo "La voiture a bien été supprimé";
        } catch (PDOException $e) {
            echo "Erreur:" . $e->getMessage();
        }

    }

    public function getCarName($id)
    {
        $db = $this->pdo;
        $sql = "SELECT marque, modèle, plaque FROM voiture WHERE id = ?";
        $request = $db->prepare($sql);
        $request->execute([$id]);
        $car = $request->fetch(PDO::FETCH_ASSOC);
        if ($car) {
            return $car["marque"] . " " . $car["modèle"] . " " . $car["plaque"];
        } else {
            return "(Voiture supprimée)";
        }
    }

    public function updateCar($marque, $modele, $annee, $plaque, $prixJour, $id){
        $db =$this->pdo;
        $sql = "UPDATE voiture 
        SET marque = ?, modèle = ?, annee = ?, plaque = ?, prix_jour = ?
        WHERE id = ?";
        $request = $db->prepare($sql);
        try{
            $request->execute([$marque, $modele, $annee, $plaque, $prixJour, $id]);
            echo "Voiture mis à jour";
        }
        catch(PDOException $e){
            echo "Erreur : " . $e->getMessage();
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

    public function getClients()
    {
        $db = $this->pdo;
        $sql = "SELECT prenom, nom, id, telephone, cin FROM client";
        $request = $db->prepare($sql);
        $request->execute();
        $clients = $request->fetchAll(PDO::FETCH_ASSOC);
        return $clients;

    }

    public function checkFuturLocation($id)
    {
        $db = $this->pdo;
        $sql = "SELECT COUNT(*) FROM location WHERE client_id = ? AND date_fin > CURRENT_DATE()";
        $request = $db->prepare($sql);
        $request->execute([$id]);
        return $request->fetchColumn() > 0;

    }

    public function deleteClient($id)
    {
        if ($this->checkFuturLocation($id)) {
            echo "Ce client a de futur locations, tu ne peux pas le supprimer !";
            return;
        }
        try {
            $db = $this->pdo;
            $sql = "DELETE FROM client WHERE id = ?";
            $request = $db->prepare($sql);
            $request->execute([$id]);
            echo "le client a bien été supprimé";
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }

    }

    public function getClientName($id)
    {
        $db = $this->pdo;
        $sql = "SELECT nom, prenom FROM client WHERE id = ?";
        $request = $db->prepare($sql);
        $request->execute([$id]);
        $client = $request->fetch(PDO::FETCH_ASSOC);
        if ($client) {
            return $client["nom"] . " " . $client["prenom"];
        } else {
            return "(Client supprimée)";
        }
    }

}

class Location
{
    private $pdo;

    function __construct($db)
    {
        $this->pdo = $db;
    }


    public function client($id)
    {
        $db = $this->pdo;

        $sql = "SELECT COUNT(*) FROM location WHERE client_id = ?";
        $request = $db->prepare($sql);
        $request->execute([$id]);

        return $request->fetchColumn() > 0;
    }
    public function car($id)
    {
        $db = $this->pdo;

        $sql = "SELECT COUNT(*) FROM location WHERE voiture_id = ?";
        $request = $db->prepare($sql);
        $request->execute([$id]);


        return $request->fetchColumn() > 0;
    }

    public function checkClientDate($id, $dateDebut, $dateFin)
    {
        $sql = "SELECT COUNT(*) FROM location WHERE client_id = ? 
        AND NOT ( date_fin <= ? OR date_debut >= ?)";
        $db = $this->pdo;
        $request = $db->prepare($sql);
        $request->execute([$id, $dateDebut, $dateFin]);
        return $request->fetchColumn() > 0;


    }

    public function checkCarDate($id, $dateDebut, $dateFin)
    {
        $sql = "SELECT COUNT(*) FROM location WHERE voiture_id = ? 
        AND NOT ( date_fin <= ? OR date_debut >= ?)";
        $db = $this->pdo;
        $request = $db->prepare($sql);
        $request->execute([$id, $dateDebut, $dateFin]);
        return $request->fetchColumn() > 0;

    }

    public function addLocation($voiture_id, $client_id, $dateDebut, $dateFin)
    {
        $db = $this->pdo;
        $sql = "INSERT INTO location (voiture_id, client_id, date_debut, date_fin) VALUES (?, ?, ?, ?)";
        $request = $db->prepare($sql);
        if($dateFin < $dateDebut){
            echo "La date de départ ne peut être supérieur à la date de fin";
        }
        if ($this->car($voiture_id)) {
            if ($this->checkCarDate($voiture_id, $dateDebut, $dateFin)) {
                echo "Cette voiture est déjà louée durant cette date";
                return;
            }
        }
        try {
            $request->execute([$voiture_id, $client_id, $dateDebut, $dateFin]);
            echo "Location ajoutée à la base de donné";
        } catch (PDOException $e) {
            echo "Erreur" . $e->getMessage();
        }


    }

    public function getLocations()
    {
        $db = $this->pdo;
        $sql = "SELECT voiture_id, client_id, date_debut, date_fin, id FROM location";
        $request = $db->prepare($sql);
        $request->execute();
        $locations = $request->fetchAll(PDO::FETCH_ASSOC);
        return $locations;
    }

    public function deleteLocation($id)
    {
        try {
            $db = $this->pdo;
            $sql = "DELETE FROM location WHERE id = ?";
            $request = $db->prepare($sql);
            $request->execute([$id]);
            echo "la location a bien été supprimée";
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }

    }

    public function getTotalPrix($id){
        $db = $this->pdo;
        $sql = "SELECT date_debut, date_fin, voiture_id FROM location WHERE id = ?";
        $request = $db->prepare($sql);
        $request->execute([$id]);
        $location = $request->fetch(PDO::FETCH_ASSOC);
        $dateDebut = new DateTime($location["date_debut"]);
        $dateFin = new DateTime($location["date_fin"]);
        $voiture_id = $location["voiture_id"];
        $days = $dateDebut->diff($dateFin)->days;
        $db = $this->pdo;
        $sql = "SELECT prix_jour FROM voiture WHERE id = ?";
        $request = $db->prepare($sql);
        $request->execute([$voiture_id]);
        $voiture = $request->fetch(PDO::FETCH_ASSOC);
        $prixJour = $voiture["prix_jour"];
        return $days * $prixJour . " €";

    }

    
}








?>