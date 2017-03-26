
## Instalation

Make sure tat the following folder exists:
```
<instalation dir>/bootstrap/cache
```


Install the NPM plugins from the root of your project
```
npm install
```


Install all the required libraries from Composer
```
composer install
```


Generate the Auto-load file
```
composer dump-autoload -o
```


Create your `.env` file on the root of your project and fill with the required values
You can copy the `.env.example` and rename it to `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<database name>
DB_USERNAME=<database username>
DB_PASSWORD=<database password>
DB_SOCKET=<full path to the socket if required>
```

Generate an Artisan Key
```
php artisan key:generate
```


Generate all the tables of the Project
```
php artisan migrate
```


If you want to seed the DB with data run the following command
```
php artisan db:seed
```


## Music api Endpoints

| Methood | Endpoint                        | Description                        | Returns                                   |
|---------|---------------------------------|------------------------------------|-------------------------------------------|
| GET     | /api/v1/albums                  | Get all albums                     | Array of Albums objects                   |
| POST    | /api/v1/albums                  | Create a new Album                 | Album object                              |
| GET     | /api/v1/albums/{album}          | Get a Album                        | Album object, with Artist info and Genres |
| PATCH   | /api/v1/albums/{album}          | Update an Album                    | Album object, with Artist info and Genres |
| DELETE  | /api/v1/albums/{album}          | Delete an Album                    | Confirmation array                        |
| GET     | /api/v1/albums/{album}/tracks   | Get all tracks for a certain album | Album object, with array of Track objects |
| GET     | /api/v1/artists                 | Get all Artists                    | Array of Artist objects                   |
| POST    | /api/v1/artists                 | Create a new Artist                | Artist object                             |
| GET     | /api/v1/artists/{artist}        | Get a Artist                       | Artist object                             |
| PATCH   | /api/v1/artists/{artist}        | Update Artist                      | Artist object                             |
| DELETE  | /api/v1/artists/{artist}        | Delete an Artist                   | Confirmation array                        |
| GET     | /api/v1/artists/{artist}/albums | Get all albums by an Artist        | Artist object with array of albums        |
| GET     | /api/v1/artists/{artist}/tracks | Get all tracks by an Artist        | Artist object with array of tracks        |
| GET     | /api/v1/tracks                  | Get all tracks                     | Array of Tracks objects                   |
| POST    | /api/v1/tracks                  | Create a new Track                 | Track object                              |
| GET     | /api/v1/tracks/{track}          | Get a Track                        | Track object                              |
| PATCH   | /api/v1/tracks/{track}          | Update Track                       | Track object                              |
| DELETE  | /api/v1/tracks/{track}          | Delete a track                     | Confirmation array                        |

<p align="center">
Built with
</p>
<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>