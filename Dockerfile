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

# Respecter le port imposé par la plateforme (Render/Railway) sinon 80
RUN printf '#!/bin/sh\nP="${PORT:-80}"\nsed -ri "s/^Listen .*/Listen ${P}/" /etc/apache2/ports.conf\nsed -ri "s/:80>/:${P}>/" /etc/apache2/sites-enabled/000-default.conf\nexec apache2-foreground\n' > /usr/local/bin/start.sh \
 && chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]
