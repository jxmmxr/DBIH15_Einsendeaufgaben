<HTML>
    <head>
        <meta charset="utf-8" />
        <title>Clock & Counter</title>
        <script type="text/javascript">
            var source = new EventSource('stream.php');
            source.onmessage = function(event) {
                var obj = JSON.parse(event.data);
                document.getElementById('zaehlerstand').textContent = obj.zaehler;
                document.getElementById('uhrzeit').textContent = obj.uhrzeit;
            };
            function hochzaehlen() {
                fetch('count.php'); 
            }
        </script>
    </head>
    <body>
        <h1>Mein Zähler: <span id="zaehlerstand"><?php echo file_get_contents('zaehler.txt'); ?></span></h1>
        <p>Aktuelle Uhrzeit (UTC): <span id="uhrzeit"><?php echo date('H:i:s'); ?></span></p>
        <button type="button" onclick="hochzaehlen()">Inkrementieren & Aktualisieren</button>
    </body>
</HTML>