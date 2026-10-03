# 1. Basis-Image: PHP 8.2 CLI (schlanker und optimiert für Skripte)
FROM php:8.2-cli

# 2. SQL-Erweiterungen installieren
RUN docker-php-ext-install pdo pdo_mysql

# 3. Arbeitsverzeichnis im Container erstellen und Code kopieren
WORKDIR /usr/src/app
COPY . .

# 4. Falls du Composer nutzt (für Ratchet nötig), musst du ihn hier installieren
RUN apt-get update && apt-get install -y unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install

# 5. Den WebSocket-Server starten
# Hier musst du den Namen deiner Startdatei angeben (z. B. server.php)
CMD ["php", "server.php"]