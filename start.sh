#!/bin/bash

docker-compose down 

docker-compose up -d --force-recreate

WORKING_DIR=$( dirname "${BASH_SOURCE[0]}" )
BUILD_DIR=$WORKING_DIR/vendor
if [ -d $BUILD_DIR ]; then
	WAIT=false

else
    WAIT=true
    
fi

>&2 echo "Please wait....."
>&2 echo ""
if [ "$WAIT" == "true" ]; then
    >&2 echo "Process might require some time to start as you are setting up App for the first time "
    docker-compose exec app composer install
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan config:clear
    >&2 echo "Waiting for MySql to start."
    sleep 60s
    >&2 echo "MySql started "
    docker-compose exec app php artisan migrate

else
    docker-compose exec app php artisan config:clear
    sleep 1s
    
fi
>&2 echo ""
>&2 echo "Running all phpunit tests now...."
>&2 echo ""
docker-compose exec app ./vendor/bin/phpunit --configuration phpunit.xml tests
>&2 echo ""
>&2 echo "Your application now ready to serve APIs at Port 8080"