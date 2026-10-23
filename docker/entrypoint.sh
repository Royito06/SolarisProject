#!/bin/sh
set -e
cd /app

if [ ! -d vendor ]; then
  echo "[*] Instalando dependencias (composer install)..."
  composer install --ignore-platform-reqs --no-interaction
fi

if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    printf '%s\n' \
      'APP_NAME=ConcursoFitMat' 'APP_ENV=local' 'APP_KEY=' 'APP_DEBUG=true' \
      'APP_URL=http://127.0.0.1:8000' '' \
      'DB_CONNECTION=mysql' 'DB_HOST=db' 'DB_PORT=3306' \
      'DB_DATABASE=concursofm' 'DB_USERNAME=concursofm' 'DB_PASSWORD=btHRD729' > .env
  fi
fi

if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

php artisan migrate --force || echo "[!] La migracion fallo; revisa el error de arriba."
if [ ! -f storage/.seeded ]; then
  php artisan db:seed --force && touch storage/.seeded || echo "[!] El seeder fallo; revisa el error de arriba."
fi

echo "=============================================="
echo "  Listo: http://127.0.0.1:8000"
echo "=============================================="
exec php artisan serve --host=0.0.0.0 --port=8000
