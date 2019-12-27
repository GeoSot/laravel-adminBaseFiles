# Laravel AdminBaseFiles
This Package Initiates a mini Cms System with Admin Area, translatable models etc



* [Installation](#installation)
* [Usage](#usage)

### Installation

1. Install package

    ```bash
    composer require geo-sot/laravel-adminbasefiles
    ```
2. Initialize Package

    ```bash
     artisan baseAdmin:install
    ```
    Runs the Following Artisan Commands
    
    > - publishFiles
    > - initializeEnv
    > - publishPackageMigrations
    > - publishEnvEditorConfig
    > - publishLocalizationConfig
    > - makePassportKeys
    > - editConfigFiles
    > - publishAssets                                                                                                                          >
                                                                                                                          >



3. Publish assets and migrate

     ```bash
     php artisan vendor:publish --provider=GeoSot\BaseAdmin\ServiceProvider     
      ```
      
      This will publish all files:
      * config -> settings.php
      * views -> resources/views/vendor/geo-sot/settings/..
      * lang -> resources/lang/settings/..
      * migration -> /database/migrations/2017_01_02_214845_settings_table.php
             
     Or publish specific tags

    ```bash
     //Publish specific tag
     php artisan vendor:publish --tag=config
     php artisan vendor:publish --tag=translations
     php artisan vendor:publish --tag=migrations
     php artisan vendor:publish --tag=views
     
     //Publish specific Tag from this Vendor
     php artisan vendor:publish --provider=GeoSot\Settings\ServiceProvider --tag=config  
 
     ```
     
     After publishing files run:
     
     ```bash
     php artisan migrate
      ```
 
