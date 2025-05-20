<?php 
require_once __DIR__ . '/../db.php';

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
            $plaque = $e->errorInfo[1];
            if($plaque == 1062){
                echo "Une autre voiture a déjà cette plaque";
            }
            else{
                echo "Erreur : " . $e->getMessage();
            }
        }
        
    }
}