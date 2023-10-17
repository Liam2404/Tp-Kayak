<?php
require_once "../classes/Admin.php";
$admin = new Admin();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $existingAdmin = $admin->getAllAdministrator($username);

    if (!$existingAdmin) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $admin->addAdministrator($username, $passwordHash);

        header("location: ../admin.php");
    } else {
        echo "Cet administrateur existe déjà.";
    }
}


