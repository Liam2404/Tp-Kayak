<?php
$currentURL = $_SERVER['REQUEST_URI'];

if (substr($currentURL, -6) === '/admin') {
    header('Location: loginAdmin.php');
    exit();
}

include_once "header.php";

?>
<h2 class="text-white">Liste des Destinations</h2>

<?php
require_once('config/autoload.php');

$db = new PDO('mysql:host=127.0.0.1;dbname=kayak;charset=utf8', 'root');
$manager = new Manager($db);

// Afficher toutes les destinations
$destinations = $manager->getAllDestinations();
?>
<section class="background">
<div class="row">
    <?php foreach ($destinations as $destination) { ?>
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center"><?php echo $destination->getLocation(); ?></h5>
                    <img src="<?php echo $destination->getPicture(); ?>" class="cardImage"></img>
                    <p class="card-text mt-2 text-center">Prix : <?php echo $destination->getPrice(); ?> €</p>
                    <!-- Lien vers tour_operator.php avec les paramètres de destination_id -->
                    <a href="tour_operator.php?destination_id=<?php echo $destination->getId(); ?>" class="btn btn-primary mt-auto">Voir le Tour Opérateur</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
</div>
</section>

<?php
include_once "footer.php";
?>