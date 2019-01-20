#!/bin/bash

docker-compose down 

docker-compose up -d --force-recreate

#docker run --rm -v $(pwd):/app composer install

docker-compose exec app php artisan key:generate

docker-compose exec app php artisan config:clear

>&2 echo "Waiting for MySql to start. Please wait....."

WORKING_DIR=$( dirname "${BASH_SOURCE[0]}" )
BUILD_DIR=$WORKING_DIR/vendor
if [ -d $BUILD_DIR ]; then
	sleep 1s
else
    >&2 echo "MySql might require some time to start as you are setting up App for the first time "
    sleep 240s
fi

>&2 echo "MySql started "
>&2 echo "Running all phpunit tests now...."

docker-compose exec app php artisan migrate

docker-compose exec app ./vendor/bin/phpunit --configuration phpunit.xml tests

>&2 echo "Your application back end is now ready to serve APIs"