<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/Client.php';

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
        if ($dateFin < $dateDebut) {
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
            return true;
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

    public function getTotalPrix($id)
    {
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

    public function checkCarDateNotCurrent($car_id, $dateDebut, $dateFin, $id)
    {
        $sql = "SELECT COUNT(*) FROM location WHERE voiture_id = ? 
        AND NOT ( date_fin <= ? OR date_debut >= ?) AND id != ?";
        $db = $this->pdo;
        $request = $db->prepare($sql);
        $request->execute([$car_id, $dateDebut, $dateFin, $id]);
        return $request->fetchColumn() > 0;
    }

    public function updateLocation($client_id, $voiture_id, $dateDebut, $dateFin, $id)
    {
        $db = $this->pdo;
        $sql = "UPDATE location
        SET client_id = ?, voiture_id = ?, date_debut = ?, date_fin = ?
        WHERE id = ?";
        $request = $db->prepare($sql);
        if ($dateFin < $dateDebut) {
            echo "La date de départ ne peut être supérieur à la date de fin";
        }
        if ($this->car($voiture_id)) {
            if ($this->checkCarDateNotCurrent($voiture_id, $dateDebut, $dateFin, $id)) {
                echo "Cette voiture est déjà louée durant cette date";
                return;
            }
        }
        try {
            $request->execute([$client_id, $voiture_id, $dateDebut, $dateFin, $id]);
            echo "Location modifiée";
        } catch (PDOException $e) {
            echo "Erreur" . $e->getMessage();
        }
    }
}
