#!/bin/sh
set -e

# Port imposé par la plateforme (Render/Railway), sinon 80.
P="${PORT:-80}"
sed -ri "s/^Listen .*/Listen ${P}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${P}>/" /etc/apache2/sites-enabled/000-default.conf

# Disque persistant : rendre inscriptible par le serveur web (Apache tourne en www-data).
if [ -n "$DIF_DATA_DIR" ]; then
  mkdir -p "$DIF_DATA_DIR"
  chown -R www-data:www-data "$DIF_DATA_DIR" 2>/dev/null || true
fi

exec apache2-foreground
