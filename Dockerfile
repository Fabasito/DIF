# Master DIF — image PHP/Apache prête à déployer (Render, Railway, VPS…).
# L'admin écrit sur le disque du conteneur : les modifications persistent
# pendant la vie du conteneur (et durablement si un disque est monté, voir
# DIF_DATA_DIR dans HEBERGEMENT.md).
FROM php:8.2-apache

# .htaccess (réécritures, protections) + en-têtes
RUN a2enmod rewrite headers \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

COPY . /var/www/html/

# Dossiers inscriptibles par le serveur web
RUN chmod -R 775 /var/www/html/content /var/www/html/logs /var/www/html/assets/uploads 2>/dev/null || true \
 && chown -R www-data:www-data /var/www/html 2>/dev/null || true

# Script de démarrage : port dynamique + disque persistant inscriptible par Apache
COPY docker-start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]
