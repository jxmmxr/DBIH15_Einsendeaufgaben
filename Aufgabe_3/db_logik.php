<?php
$query = "SELECT name, ip_adresse, zeitstempel FROM live_zugriffe ORDER BY zeitstempel DESC LIMIT 10";
$stmt = $pdo->prepare($query);
$stmt->execute();
$ergebnisse = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>