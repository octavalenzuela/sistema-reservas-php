<?php
// config/db_config.php

$url_str = getenv('MYSQL_URL');

if ($url_str) {
    $url_str = str_replace(['${{', '}}'], '', $url_str);
    
    $url = parse_url($url_str);
    $host = $url['host'];
    $user = $url['user'];
    $pass = $url['pass'];
    $db   = ltrim($url['path'], '/');
    $port = $url['port'] ?: '3306';
} else {
    // Localhost (XAMPP)
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
    die("Error de conexión (Host: $host): " . $e->getMessage());
}