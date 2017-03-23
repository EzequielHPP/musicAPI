echo -e "\e[92mChecking if directory exists"
echo -e "\033[0m"
if [ -d "$(pwd)/bootstrap/cache" ];
then
    echo -e "\e[92mDirectory already exists!
    echo -e "\033[0m"
else
    cd $(pwd)/bootstrap/
    mkdir cache
    cd ../
fi

echo -e "\e[92mGetting NPM plugins"
echo -e "\033[0m"
npm install

echo -e "\e[92mComposer Actions"
echo -e "\033[0m"
composer install
composer dump-autoload

echo -e "\e[92mGenerating Artisan key"
echo -e "\033[0m"
php artisan key:generate

echo -e "\e[92mFinished"
echo -e "\033[0m"