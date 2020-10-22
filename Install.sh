echo "Checking for PHP version ..."
if [ ! $(which php) ]; then
    echo "ERROR: PHP could not be found on your system."; exit 1
fi

echo "Checking for composer version ..."
if [ ! $(which composer) ]; then
    echo "ERROR: composer could not be found on your system."; exit 1
fi

composer install
cp .env.example .env
echo "Please input database name ..."
read DATABASE_NAME
echo "Please input database username ..."
read DATABASE_USERNAME
echo "Please input database password ..."
read DATABASE_PASSWD
echo "Please input administor email ..."
read ADMIN_EMAIL
echo "Please input administor password ..."
read ADMIN_PASSWD

sed -i 's/DB_DATABASE=/DB_DATABASE={$DATABASE_NAME}' .env
sed -i 's/DB_USERNAME=/DB_USERNAME={$DATABASE_USERNAME}' .env
sed -i 's/DB_PASSWORD=/DB_PASSWORD={$DATABASE_PASSWD}' .env
sed -i 's/ADMIN_EMAIL=/ADMIN_EMAIL={$ADMIN_EMAIL}' .env
sed -i 's/ADMIN_PASSWORD=/ADMIN_PASSWORD={$ADMIN_PASSWD}' .env

php artisan migrate
time=$(date "+%Y-%m-%d %H:%M:%S")
mysql -u {$DATABASE_USERNAME} -p {$DATABASE_PASSWD}
use {$DATABASE_NAME};
insert into Users (name,username,email,password,created_at,updated_at) values ('admin','admin',{$ADMIN_EMAIL},{$ADMIN_PASSWD},{$time},{$time});
exit;

php artisan cache:clear
composer clear-cache
composer dump-autoload --optimize
php artisan clear-compiled
php artisan optimize --force
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
chmod -R 777 storage
