<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = new PDO("mysql:host=127.0.0.1;dbname=kayak;charset=utf8", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $location = $_POST["location"];
        $price = $_POST["price"];
        $tour_operator_id = $_POST["tour_operator_id"];

        $sql = "INSERT INTO destination (location, price, tour_operator_id) VALUES (:location, :price, :tour_operator_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":location", $location);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":tour_operator_id", $tour_operator_id);
        $stmt->execute();

        header("Location: ../admin.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de la destination : " . $e->getMessage();
    }
}
?>
