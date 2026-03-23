<?php
// db_config.php
$host = 'localhost';
$db   = 'gestion_reservas';
$user = 'root'; 
$pass = '';     
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
   
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}