<?php
// config/db_confi.php
$url = getenv('MYSQL_URL') ?: getenv('DATABASE_URL');

if ($url) {
    $dbparts = parse_url($url);
    $host = $dbparts['host'];
    $user = $dbparts['user'];
    $pass = $dbparts['pass'];
    $db   = ltrim($dbparts['path'], '/');
    $port = $dbparts['port'];
} else {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db   = 'gestion_reservas';
    $port = '3306';
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}