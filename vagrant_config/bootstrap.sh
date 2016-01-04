#!/usr/bin/env bash
function e {
    echo "####################################"
    echo $1
    echo "####################################"
}

e "Installing composer packages"
cd /var/www
composer install
cd /var/www/react
e "Installing npm packages"
npm install

e "Watching react files"
npm run watch &

e "Executing migrations"
. /vagrant/dev-scripts/schema_and_data_migration.sh dev


e "Updating jackpot and results"
php /var/www/apps/cli.php jackpot updatePrevious
php /var/www/apps/cli.php result update
php /var/www/apps/cli.php jackpot update