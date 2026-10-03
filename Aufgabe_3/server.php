<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/db.php';

class LiveAbfrage implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage; // Ein spezieller Speicher für Objekte
    }

    public function onOpen(ConnectionInterface $conn) {
        global $pdo;

        $this->clients->attach($conn);

        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryArray);
        $name = $queryArray['name'] ?? 'Unbekannter Besucher';

        // 1. Die HTTP-Anfrage aus der Verbindung holen
        $httpRequest = $conn->httpRequest;
        
        // 2. Den Header 'X-Forwarded-For' auslesen
        $header = $httpRequest->getHeader('X-Forwarded-For');
        
        // 3. Wenn der Header da ist, nimm die erste IP, sonst die direkte Adresse
        if (!empty($header)) {
            $rawIp = $header[0]; // Das ist oft eine Liste, wir nehmen den ersten Eintrag
        } else {
            $rawIp = $conn->remoteAddress;
        }

        $ipArray = explode(',', $rawIp);
        $rohe_ip = trim($ipArray[0]);
        //$rohe_ip = $conn->remoteAddress; // z.B. "127.0.0.1"
        $zeitstempel = date('Y-m-d H:i:s'); 

        if (str_contains($rohe_ip, '.')){
            // Wir zerlegen die IP an den Punkten in ein Array
            $teile = explode('.', $rohe_ip);

            // Wenn es eine gültige IPv4 / IPv6 ist (4 Teile), ersetzen wir den letzten Teil
            if (count($teile) === 4) {
                $teile[3] = 'xxx';
            } 
            // Wir fügen alles wieder mit Punkten dazwischen zusammen
            $maskierte_ip = implode('.', $teile); // Ergebnis: "127.0.0.xxx"
        } else {
            // Wir zerlegen die IP an den Doppelpunkten in ein Array
            $teile = explode(':', $rohe_ip);

            if (count($teile) > 1) {
                $teile[count($teile) - 1] = 'xxxx';
            }
            // Wir fügen alles wieder mit Doppelpunkten dazwischen zusammen
            $maskierte_ip = implode(':', $teile); // Ergebnis: "127.0.0.xxx"
        }

        // 2. Den neuen Zugriff in die Datenbank schreiben
        $sql = "INSERT INTO live_zugriffe (name, ip_adresse, zeitstempel) VALUES (:name, :ip, :zeitstempel)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'ip' => $maskierte_ip, 'zeitstempel' => $zeitstempel]);

        // 3. Jetzt die (aktualisierten) letzten 10 Einträge holen
        require 'db_logik.php';
        
        // 4. Und abschicken
        $conn->send(json_encode($ergebnisse));
        
        echo "Neuer Zugriff von $ip gespeichert und Liste gesendet.\n";

        require 'db_logik.php';
        // Wir senden die Daten als JSON an den neuen Client
        //$conn->send(json_encode($ergebnisse));
        foreach ($this->clients as $client) {   // Wir senden die Daten als JSON an alle Clients
            $client->send(json_encode($ergebnisse));
        }
        echo "Ein neuer Client ist verbunden und hat Daten erhalten.\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {}
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
$port = (int)(getenv('PORT') ?: 8080);
$server = IoServer::factory(
    new HttpServer(new WsServer(new LiveAbfrage())),
    $port
);

echo "Server gestartet auf Port 8080...\n";
$server->run();
?>