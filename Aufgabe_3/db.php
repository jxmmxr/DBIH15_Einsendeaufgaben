<?php
$host = 'mysql-einsendeaufgaben-aufgabe-3-dbih15-einsendeaufgaben-aufgab.d.aivencloud.com';
$db   = 'defaultdb';
$user = 'avnadmin';
$pass = getenv('DB_PASSWORD');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=23273;dbname=$db;charset=$charset"; 
/*try {
     $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
     die("Fehler: " . $e->getMessage());
}*/
$versuch = 0;
$pdo = null;

while ($pdo === null) {
    try {
        $versuch++;
        // Versuche die Verbindung
        $pdo = new PDO($dsn, $user, $pass, [
          \PDO::ATTR_TIMEOUT => 5, // Wartet nur 5 Sekunden
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
          ]);
        echo "Verbindung zur Datenbank nach $versuch Versuch(en) erfolgreich!\n";
    } catch (\PDOException $e) {
        echo "Datenbank noch nicht bereit (Versuch $versuch). Warte 3 Sekunden...\n";
        
        // WICHTIG: Kurze Pause, um den Server nicht zu überlasten
        sleep(3); 
        
        // Optional: Nach X Versuchen doch abbrechen
        if ($versuch >= 20) {
            die("Datenbank nach 20 Versuchen nicht erreichbar. Abbruch.");
        }
    }
}
// Test: Ist die Variable jetzt befüllt?
if ($pdo instanceof PDO) {
    echo "DEBUG: PDO-Objekt ist bereit für den Export.\n";
}