set -e
if [ ! -f .env ]; then
    cp .env.example .env
fi
php artisan key:generate --force
php artisan migrate --seed --force
php artisan storage:link --force 2>/dev/null || true
exec "$@"
