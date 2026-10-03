# 1. Basis-Image mit Apache und PHP
FROM php:8.2-apache

# 2. Den Inhalt deines Ordners (z.B. Aufgabe1) in das Webverzeichnis kopieren
COPY . /var/www/html/

# 3. Den Apache-Port auf die Variable von Render umstellen
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf