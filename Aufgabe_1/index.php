<?php
session_start();
// 1. Logik beim Klicken des Buttons (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hochzaehlen'])) {
    if (!isset($_SESSION['zaehler'])) {
        $_SESSION['zaehler'] = 0;
    }
    $_SESSION['zaehler']++;

    // Wir setzen eine Markierung, dass wir gerade erst hochgezählt haben
    $_SESSION['darf_einmal_bleiben'] = true;

    // Wir leiten auf uns selbst um, damit aus POST ein GET wird (verhindert das F5-Problem)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zeitaktualisieren'])) {
    if (!isset($_SESSION['uhrzeit'])) {
        $_SESSION['uhrzeit'] = date('H:i:s');
    }
    $_SESSION['uhrzeit'] = date('H:i:s');

    // Wir leiten auf uns selbst um, damit aus POST ein GET wird (verhindert das F5-Problem)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 2. Logik beim Anzeigen der Seite (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Wenn die Markierung NICHT da ist, bedeutet das: Der Nutzer hat F5 gedrückt
    // oder die Seite manuell aufgerufen. -> RESET
    if (!isset($_SESSION['darf_einmal_bleiben']) || $_SESSION['darf_einmal_bleiben'] === false) {
        $_SESSION['zaehler'] = 0;
    }

    // Wir verbrauchen die Markierung sofort wieder für den nächsten Aufruf
    $_SESSION['darf_einmal_bleiben'] = false;
}

$stand = isset($_SESSION['zaehler']) ? $_SESSION['zaehler'] : 0;
$zeit = isset($_SESSION['uhrzeit']) ? $_SESSION['uhrzeit'] : '00:00:00';
?>
<HTML>
    <head>
        <meta charset="utf-8" />
        <title>Clock & Counter</title>
    </head>
    <body>
        <h1>Mein Zähler: <?php echo $_SESSION['zaehler']; ?></h1>
        <p>Aktuelle Uhrzeit (UTC): <?php echo $_SESSION['uhrzeit']; ?></p>
        
        <form method="post">
            <button type="submit" name="hochzaehlen">Inkrementieren</button>
        </form>
        <form method="post">
            <button type="submit" name="zeitaktualisieren">Aktualisieren</button>
        </form>
    </body>
</HTML>