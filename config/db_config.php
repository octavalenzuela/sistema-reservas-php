<?php
// config/db_config.php
$host = getenv('MYSQLHOST'); 
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: '3306';

if (!$host) {
    $host = '127.0.0.1';
    $user = 'root';
    $pass = '';
    $db   = 'gestion_reservas';
    $port = '3306';
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Error de conexión (Host intentado: $host): " . $e->getMessage());
}