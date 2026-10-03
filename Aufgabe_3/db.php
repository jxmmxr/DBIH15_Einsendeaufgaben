<?php
$host = '127.0.0.1'; // Von localhost auf 127.0.0.1 geändert
$db   = 'live_projekt';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=8889;dbname=$db;charset=$charset"; 
try {
     $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
     die("Fehler: " . $e->getMessage());
}