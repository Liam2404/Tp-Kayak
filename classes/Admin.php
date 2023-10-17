<?php

class Admin {
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=127.0.0.1;dbname=kayak;charset=utf8", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    }

    public function updateDestination($id, $location, $price, $tour_operator_id) {
        try {
            $sql = "UPDATE destination SET location = :location, price = :price, tour_operator_id = :tour_operator_id WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":location", $location, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price, PDO::PARAM_INT);
            $stmt->bindParam(":tour_operator_id", $tour_operator_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur de mise à jour : " . $e->getMessage();
        }
    }

    public function deleteDestination($id) {
        try {
            $sql = "DELETE FROM destination WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur de suppression : " . $e->getMessage();
        }
    }

    public function getAllDestinations() {
        try {
            $sql = "SELECT * FROM destination";
            $result = $this->conn->query($sql);

            if ($result->rowCount() > 0) {
                return $result->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [];
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des destinations : " . $e->getMessage();
            return [];
        }
    }

    public function closeConnection() {
        $this->conn = null;
    }

    public function getAllTourOperators() {
        try {
            $sql = "SELECT id, name, link, is_premium FROM tour_operator";
            $result = $this->conn->query($sql);
    
            if ($result->rowCount() > 0) {
                return $result->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [];
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des opérateurs touristiques : " . $e->getMessage();
            return [];
        }
    }

    public function setTourOperatorPremiumStatus($tour_operator_id, $is_premium) {
        try {
            $sql = "UPDATE tour_operator SET is_premium = :is_premium WHERE id = :tour_operator_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":is_premium", $is_premium, PDO::PARAM_BOOL);
            $stmt->bindParam(":tour_operator_id", $tour_operator_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du statut premium : " . $e->getMessage();
        }
    }
    
    public function isTourOperatorPremium($tour_operator_id) {
        try {
            $sql = "SELECT is_premium FROM tour_operator WHERE id = :tour_operator_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":tour_operator_id", $tour_operator_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['is_premium'] == 1; 
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération du statut premium : " . $e->getMessage();
            return false; 
        }
    }

    public function addPremiumStatus($tour_operator_id) {
        try {
            $sql = "UPDATE tour_operator SET is_premium = 1 WHERE id = :tour_operator_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":tour_operator_id", $tour_operator_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du statut premium : " . $e->getMessage();
        }
    }
    
    public function removePremiumStatus($tour_operator_id) {
        try {
            $sql = "UPDATE tour_operator SET is_premium = 0 WHERE id = :tour_operator_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":tour_operator_id", $tour_operator_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du statut premium : " . $e->getMessage();
        }
    }
    
    public function addAdministrator($username, $passwordHash) {
        try {
            $sql = "INSERT INTO administrator (username, password) VALUES (:username, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $passwordHash, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'administrateur : " . $e->getMessage();
        }
    }

    public function getAllAdministrator($username)
    {
        $req = $this->conn->prepare("SELECT * FROM administrator WHERE username = :username LIMIT 1 ");
        $req->bindParam(":username", $username);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>
