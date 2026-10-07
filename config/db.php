<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "school_system";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
