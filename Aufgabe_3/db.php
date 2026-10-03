<?php
$host = 'mysql-einsendeaufgaben-aufgabe-3-dbih15-einsendeaufgaben-aufgab.d.aivencloud.com';
$db   = 'defaultdb';
$user = 'avnadmin';
$pass = getenv('DB_PASSWORD');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=23273;dbname=$db;charset=$charset"; 
try {
     $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
     die("Fehler: " . $e->getMessage());
}