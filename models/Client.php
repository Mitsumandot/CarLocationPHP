<?php

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/Location.php';


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

    public function updateClient($nom, $prenom, $telephone, $cin, $id)
    {
        $db = $this->pdo;
        $sql = "UPDATE client 
        SET nom = ?, prenom = ?, telephone = ?, cin = ?
        WHERE id = ?";
        $request = $db->prepare($sql);
        try {
            $request->execute([$nom, $prenom, $telephone, $cin, $id]);
            echo "Client mis à jour";
        } catch (PDOException $e) {
            $cin = $e->errorInfo[1];
            if ($cin == 1062) {
                echo "Un autre utilisateur a déjà ce CIN";
            } else {
                echo "Erreur : " . $e->getMessage();
            }
        }
    }
}
