<?php
// config/db_config.php
$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$db   = getenv('MYSQLDATABASE') ?: 'gestion_reservas';
$port = getenv('MYSQLPORT') ?: '3306';
$url = getenv('MYSQL_URL');
if ($url) {
    $dbparts = parse_url($url);
    if (isset($dbparts['host'])) {
        $host = $dbparts['host'];
        $user = $dbparts['user'];
        $pass = $dbparts['pass'];
        $db   = ltrim($dbparts['path'], '/');
        $port = $dbparts['port'];
    }
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}