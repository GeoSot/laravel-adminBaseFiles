# Laravel AdminBaseFiles
This Package Initiates a mini Cms System with Admin Area, translatable models etc


* [Installation](#installation)


### Installation

1. Install package

    ```bash
    composer require geo-sot/laravel-adminbasefiles
    ```
2. Initialize Package

    ```bash
     artisan baseAdmin:install
    ```


3. Publish assets and migrate

     ```bash
     php artisan vendor:publish --provider=GeoSot\BaseAdmin\ServiceProvider     
      ```
     
     After publishing files run:
     
     ```bash
        php artisan migrate
      ```

(and delete the File app\User.php)
