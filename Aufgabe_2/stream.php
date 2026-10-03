<?php
set_time_limit(0); // Verhindert den Timeout nach 30 Sekunden
header('Content-Type: text/event-stream');  // Kein normales HTML, sondern endloser Event-Stream
header('Cache-Control: no-cache');

$start = time();
do {
    // Kontrollierter Abbruch nach 15 Sekunden für einen sauberen Re-Connect
    if ((time() - $start) > 15) { 
        die(); 
    }

    clearstatcache(); // Zwingt PHP, die Datei wirklich neu zu lesen
    $stand = @file_get_contents('zaehler.txt');
    
    echo ":" . str_repeat(" ", 1024) . "\n";
    $daten = [
        'zaehler' => $stand,
        'uhrzeit' => date('H:i:s')
    ];
    echo "data: " . json_encode($daten) . "\n\n";
    
    ob_flush();
    flush();
    sleep(1); 
} while(true);
?>