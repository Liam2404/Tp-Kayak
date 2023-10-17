<?php
require_once "../classes/Admin.php";

$admin = new Admin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["login_username"];
    $password = $_POST["login_password"];

    $adminData = $admin->getAllAdministrator($username);

    if ($adminData && password_verify($password, $adminData["password"])) {
        header("location: ../admin.php");
        exit; 
    } else {
        echo "<script language='javascript'>";
        echo "alert('WRONG INFORMATION')";
        echo "</script>";
    }
}

