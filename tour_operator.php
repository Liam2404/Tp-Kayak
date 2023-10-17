<?php
include_once "header.php";
require_once('config/autoload.php');

$db = new PDO('mysql:host=127.0.0.1;dbname=kayak;charset=utf8', 'root');
$manager = new Manager($db);

// Vérifiez si les paramètres GET sont définis et sont des entiers
$destinationId = isset($_GET['destination_id']) ? intval($_GET['destination_id']) : null;

// Utilisez $destinationId pour récupérer le tour_operator_id
$tourOperatorId = $manager->getTourOperatorIdForDestination($destinationId);

// Gérez l'erreur si $tourOperatorId n'est pas défini
if ($tourOperatorId === null) {
    echo "Erreur : tour_operator_id non trouvé pour cette destination.";
    exit;
}

// Initialisez la variable $authorHasReview à false par défaut
$authorHasReview = false;

// Utilisez $tourOperatorId lorsque vous soumettez le formulaire pour ajouter une critique
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['author_name'], $_POST['review'], $_POST['score'])) {
        $authorName = $_POST['author_name'];
        $review = $_POST['review'];
        $score = $_POST['score'];

        // Vérifiez d'abord si l'auteur a déjà laissé une critique
        $authorHasReview = $manager->authorHasReviewForTourOperator($authorName, $tourOperatorId);

        if (!$authorHasReview) {
            // L'auteur n'a pas encore laissé de critique, vous pouvez ajouter la nouvelle critique ici
            $manager->addReviewAndScore($tourOperatorId, $authorName, $review, $score);

            // Redirigez vers la même page avec destination_id et tour_operator_id
            header("Location: tour_operator.php?destination_id=$destinationId&tour_operator_id=$tourOperatorId");
            exit;
        }
    }
}
?>
<section class="background">
    <h2 class="text-white">Tours Opérateurs Disponibles :</h2>
    <div class="container-fluid">
        <?php
        if ($destinationId !== null) {
            $tourOperator = $manager->getTourOperatorForDestination($destinationId);

            if ($tourOperator) {
                $details = $manager->getTourOperatorDetails($tourOperator->getId());
                $averageScore = round($details['average_score']);
            }}
                ?>
                <div class="row">
                    <div class="col-4 text-center">
                        <h3 class="text-primary pt-3">
                            <?php echo $tourOperator->getName(); ?>
                        </h3>
                        <p>
                            <?php if ($tourOperator->isPremium()): ?>
                                Site Web : <a href="<?php echo $tourOperator->getLink(); ?>"
                                    target="_blank"><?php echo $tourOperator->getLink(); ?></a>
                            <?php endif; ?>
                        </p>

                        <p>Note d'avis globale :
                            <?php echo $averageScore; ?>
                        </p>
                        <p>Prix de la destination :
                            <?php echo $details['destination_price']; ?> €
                        </p>
                    </div>
                </div>
                <?php
                if (!empty($details['authors']) && !empty($details['reviews'])) {
                    $authorsArray = explode(', ', $details['authors']);
                    $reviewsArray = explode('<br>', $details['reviews']);

                    if (!empty($authorsArray) && !empty($reviewsArray)) {
                        ?>
                        <section class="review">
                            <h4 class="text-center">Avis des utilisateurs</h4>
                            <ul>
                                <?php
                                for ($i = 0; $i < count($authorsArray) && $i < count($reviewsArray); $i++) {
                                    ?>
                                    <li>
                                        <?php echo "{$authorsArray[$i]} : {$reviewsArray[$i]}"; ?>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </section>
                        <?php
                    } else {
                        echo "Aucun avis disponible pour ce tour opérateur.";
                    }
                } else {
                    echo "Aucun avis disponible pour ce tour opérateur.";
                }
                ?>

                <form method="post"
                    action="tour_operator.php?destination_id=<?php echo $destinationId; ?>&tour_operator_id=<?php echo $tourOperatorId; ?>">
                    <div class="form-group">
                        <label for="author_name">Nom de l'auteur :</label>
                        <input type="text" class="form-control" id="author_name" name="author_name" required>
                    </div>
                    <div class="form-group">
                        <label for="review">Votre avis :</label>
                        <textarea class="form-control" id="review" name="review" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="score">Votre note :</label>
                        <select class="form-control" id="score" name="score" required>
                            <option value="1">1 - Très mauvais</option>
                            <option value="2">2 - Mauvais</option>
                            <option value="3">3 - Moyen</option>
                            <option value="4">4 - Bon</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <!-- Ajoutez un champ caché pour tour_operator_id -->
                    <input type="hidden" name="tour_operator_id" value="<?php echo $tourOperatorId; ?>">
                    <!-- Utilisez également un champ caché pour destination_id -->
                    <input type="hidden" name="destination_id" value="<?php echo $destinationId; ?>">
                    <button type="submit" class="btn btn-primary" <?php if ($authorHasReview): ?>onclick="afficherErreur()"
                        <?php endif; ?>>Soumettre l'avis</button>
                </form>
            </div>
    </div>
</section>

<?php
include_once "footer.php";
?>

<script>
    function afficherErreur() {
        if (afficherBoiteErreur) {
            alert("L'auteur a déjà laissé un avis pour ce tour opérateur.");
        }
    }
</script>