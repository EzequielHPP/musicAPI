#!/usr/bin/env bash
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

if [ \("$1" == ""\) -o \("$2" == ""\) -o \("$3" == ""\) ]; then

    echo "${RED}Some parameters are missing${NC}"

    if [ -z "$1" ]
    then
        echo "${RED}    |_ (1st parameter) No DB name parameter sent${NC}"
    fi

    if [ -z "$2" ]
    then
        echo "${RED}    |_ (2nd parameter) No DB user parameter sent${NC}"
    fi

    if [ -z "$3" ]
    then
        echo "${RED}    |_ (3rd parameter) No DB password parameter sent${NC}"
    fi

else

    echo "${GREEN}Checking if directory '/bootstrap/cache' exists${NC}"
    if [ \(-d "$(pwd)/bootstrap/cache"\) ];
    then
        echo "${GREEN}Directory already exists!${NC}"
    else
        echo "${RED}Directory doesn't exist.${NC}"
        echo "${GREEN}   Creating folder${NC}"
        cd $(pwd)/bootstrap/
        mkdir cache
        cd ../
    fi

    echo "${GREEN}Getting NPM plugins${NC}"
    npm install

    echo "${GREEN}Composer actions${NC}"
    composer install
    composer dump-autoload

    if [ \(-f "$(pwd)/.env"\) ];
    then
        echo "${GREEN}.env file already exists!${NC}"
    else
        echo "${GREEN}Generating .env${NC}"
        touch .env

        echo "${GREEN}Populating .env file${NC}"

        echo "APP_ENV=local" >> .env
        echo "APP_KEY=" >> .env
        echo "APP_DEBUG=true" >> .env
        echo "APP_LOG_LEVEL=debug" >> .env
        echo "APP_URL=http://localhost" >> .env
        echo "" >> .env
        echo "DB_CONNECTION=mysql" >> .env
        echo "DB_HOST=127.0.0.1" >> .env
        echo "DB_PORT=3306" >> .env
        echo "DB_DATABASE=$1" >> .env
        echo "DB_USERNAME=$2" >> .env
        echo "DB_PASSWORD=$3" >> .env
        echo "" >> .env
        echo "BROADCAST_DRIVER=log" >> .env
        echo "CACHE_DRIVER=file" >> .env
        echo "SESSION_DRIVER=file" >> .env
        echo "QUEUE_DRIVER=sync" >> .env
        echo "" >> .env
        echo "REDIS_HOST=127.0.0.1" >> .env
        echo "REDIS_PASSWORD=null" >> .env
        echo "REDIS_PORT=6379" >> .env
        echo "" >> .env
        echo "MAIL_DRIVER=smtp" >> .env
        echo "MAIL_HOST=smtp.mailtrap.io" >> .env
        echo "MAIL_PORT=2525" >> .env
        echo "MAIL_USERNAME=null" >> .env
        echo "MAIL_PASSWORD=null" >> .env
        echo "MAIL_ENCRYPTION=null" >> .env
        echo "" >> .env
        echo "PUSHER_APP_ID=" >> .env
        echo "PUSHER_APP_KEY=" >> .env
        echo "PUSHER_APP_SECRET=" >> .env
    fi

    echo "${GREEN}Generating artisan key${NC}"
    php artisan key:generate

    echo "${GREEN}Running migrations${NC}"
    php artisan migrate

    echo "${GREEN}Finished${NC}"

fi