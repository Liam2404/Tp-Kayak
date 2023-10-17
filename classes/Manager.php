<?php 
class Manager {
    private $db;

    public function setDb(PDO $db){
        $this->db = $db;
    }

    public function __construct(PDO $db){
        $this->setDb($db);
    }
    
    // CRUD Read - Afficher toutes les destinations
    public function getAllDestinations() {
        $query = $this->db->query("SELECT * FROM destination");
        $destinations = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $destination = new Destination($row['id'], $row['location'], $row['price'], $row['picture'], $row['tour_operator_id']);
            $destinations[] = $destination;
        }
        return $destinations;
    }
    
    
    // CRUD Read - Récupérer tous les tour-opérateurs
    public function getAllTourOperators() {
        $query = $this->db->query("SELECT * FROM tour_operator");
        $tourOperators = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $tourOperator = new TourOperator($row['id'], $row['name'], $row['link'], $row['is_premium']);
            $tourOperators[] = $tourOperator;
        }
        return $tourOperators;
    }


    public function getTourOperatorForDestination($destinationId) {
        $query = $this->db->prepare("SELECT tour_operator.* FROM destination JOIN tour_operator ON destination.tour_operator_id = tour_operator.id WHERE destination.id = ?");
        $query->execute([$destinationId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            return new TourOperator($result['id'], $result['name'], $result['link'], $result['is_premium']);
        }
    
        return null; // Retourne null si le tour opérateur n'est pas trouvé
    }

    // CRUD Update - Mettre à jour un tour-opérateur
    public function updateTourOperator(TourOperator $tourOperator) {
        $query = $this->db->prepare("UPDATE tour_operator SET name = ?, link = ? WHERE id = ?");
        $query->execute([$tourOperator->getName(), $tourOperator->getLink(), $tourOperator->getId()]);
    }

    // CRUD Delete - Supprimer un tour-opérateur par ID
    public function deleteTourOperator($id) {
        $query = $this->db->prepare("DELETE FROM tour_operator WHERE id = ?");
        $query->execute([$id]);
    }


    public function getTourOperatorDetails($tourOperatorId) {
        $query = $this->db->prepare("
            SELECT
                tour_operator.*,
                AVG(score.value) AS average_score,
                destination.price AS destination_price,
                GROUP_CONCAT(DISTINCT author.name SEPARATOR ', ') AS authors,
                GROUP_CONCAT(DISTINCT review.message SEPARATOR '<br>') AS reviews
            FROM tour_operator
            LEFT JOIN score ON tour_operator.id = score.tour_operator_id
            LEFT JOIN destination ON tour_operator.id = destination.tour_operator_id
            LEFT JOIN review ON tour_operator.id = review.tour_operator_id
            LEFT JOIN author ON review.author_id = author.id
            WHERE tour_operator.id = :tour_operator_id
            GROUP BY tour_operator.id, destination_price
        ");
        $query->bindParam(':tour_operator_id', $tourOperatorId);
        $query->execute();
    
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function addScore($tourOperatorId, $authorId, $value) {
        // Vérifiez d'abord si l'utilisateur a déjà ajouté un score pour ce tour opérateur
        $existingScore = $this->getScoreByTourOperatorAndAuthor($tourOperatorId, $authorId);
    
        if ($existingScore) {
            if (is_array($existingScore)) {
                // Si $existingScore est un tableau, cela signifie que l'auteur a déjà laissé un avis, mais pas de note.
                // Vous pouvez gérer cette situation comme vous le souhaitez, par exemple, afficher un message d'erreur.
                echo "L'auteur a déjà laissé un avis pour ce tour opérateur.";
                exit;
            } else {
                // Si $existingScore est un objet Score, mettez à jour la valeur du score existant
                $query = $this->db->prepare("UPDATE score SET value = ? WHERE id = ?");
                $query->execute([$value, $existingScore->getId()]);
            }
        } else {
            // Sinon, ajoutez un nouveau score
            $query = $this->db->prepare("INSERT INTO score (tour_operator_id, author_id, value) VALUES (?, ?, ?)");
            $query->execute([$tourOperatorId, $authorId, $value]);
        }
    }

    public function addReviewAndScore($tourOperatorId, $authorName, $review, $score) {
        // Vérifiez d'abord si l'auteur existe déjà
        $authorId = $this->getAuthorIdByUsername($authorName);
    
        if ($authorId === null) {
            // Si l'auteur n'existe pas, insérez-le dans la table 'author'
            $query = $this->db->prepare("INSERT INTO author (name) VALUES (?)");
            $query->execute([$authorName]);
    
            // Obtenez l'ID de l'auteur nouvellement créé
            $authorId = $this->db->lastInsertId();
        }
    
        // Assurez-vous que $tourOperatorId est bien un entier
        if (!is_int($tourOperatorId)) {
            // Gérez la situation où tourOperatorId n'est pas un entier valide
            echo "tour_operator_id n'est pas un entier valide.";
            exit;
        }
    
        // Insérez ensuite l'avis dans la table 'review' avec l'ID de l'auteur
        $query = $this->db->prepare("INSERT INTO review (message, tour_operator_id, author_id) VALUES (?, ?, ?)");
        $query->execute([$review, $tourOperatorId, $authorId]);
    
        // Maintenant, ajoutez le score dans la table 'score'
        $this->addScore($tourOperatorId, $authorId, $score);
    }



    // ...

    public function getScoreByTourOperatorAndAuthor($tourOperatorId, $authorId) {
        $query = $this->db->prepare("SELECT * FROM score WHERE tour_operator_id = ? AND author_id = ?");
        $query->execute([$tourOperatorId, $authorId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getAuthorIdByUsername($username) {
        $query = $this->db->prepare("SELECT id FROM author WHERE name = ?");
        $query->execute([$username]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['id'];
        }

        return null; // Retourne null si l'auteur n'est pas trouvé
    }

    public function getTourOperatorIdForDestination($destinationId) {
        $query = $this->db->prepare("SELECT tour_operator_id FROM destination WHERE id = ?");
        $query->execute([$destinationId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            return $result['tour_operator_id'];
        }
    
        return null; // Retourne null si la destination n'est pas trouvée
    }

    public function authorHasReviewForTourOperator($authorName, $tourOperatorId)
{
    try {
        // Recherchez l'ID de l'auteur en fonction de son nom
        $query = "SELECT id FROM authors WHERE name = :authorName";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':authorName', $authorName, PDO::PARAM_STR);
        $stmt->execute();

        $authorId = $stmt->fetchColumn();

        if ($authorId === false) {
            // L'auteur n'existe pas, il n'a donc pas laissé de critique
            return false;
        }

        // Maintenant que nous avons l'ID de l'auteur, vérifions s'il a déjà laissé une critique pour le tour opérateur
        $query = "SELECT COUNT(*) FROM reviews WHERE author_id = :authorId AND tour_operator_id = :tourOperatorId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':authorId', $authorId, PDO::PARAM_INT);
        $stmt->bindParam(':tourOperatorId', $tourOperatorId, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count > 0);
    } catch (PDOException $e) {
        // Gérer les erreurs de base de données ici (journalisation, affichage d'un message d'erreur, etc.)
        return false;
    }
}

public function getReviewsForTourOperatorSortedByAuthorId($tourOperatorId) {
    $query = "SELECT r.message, a.name as author_name 
              FROM review r
              JOIN Author a ON r.author_id = a.id
              WHERE r.tour_operator_id = :tourOperatorId
              ORDER BY r.author_id";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':tourOperatorId', $tourOperatorId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}

