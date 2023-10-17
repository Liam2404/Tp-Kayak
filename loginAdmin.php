<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'Administrateur et Connexion</title>
</head>
<body>
<a href="adminCreation.php">
        <button>Créer un admin</button>
    </a>
    <h1>Connexion d'Administrateur</h1>
    <form action="process/adminLogin.php" method="post">
        <label for="login_username">Nom d'utilisateur :</label>
        <input type="text" id="login_username" name="login_username" required><br><br>

        <label for="login_password">Mot de passe :</label>
        <input type="password" id="login_password" name="login_password" required><br><br>

        <input type="submit" value="Se connecter">
    </form>
</body>
</html>
