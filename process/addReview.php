<?php
require_once('../config/autoload.php');

$db = new PDO('mysql:host=127.0.0.1;dbname=kayak;charset=utf8', 'root');
$manager = new Manager($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['author_name'], $_POST['review'], $_POST['tour_operator_id'], $_POST['destination_id'])) {
        $authorName = $_POST['author_name'];
        $review = $_POST['review'];
        $tourOperatorId = $_POST['tour_operator_id'];
        $destinationId = $_POST['destination_id'];

        // Utilisez la méthode addReview pour enregistrer l'avis dans la base de données
        $manager->addReview($tourOperatorId, $authorName, $review);

        // Redirigez vers la même page pour éviter une soumission multiple du formulaire
        header("Location: ../tour_operator.php?destination_id=$destinationId&tour_operator_id=$tourOperatorId");
        exit;
    }
}