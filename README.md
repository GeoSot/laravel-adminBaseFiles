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
      

4.  When published, the config/settings.php config file contains:

  ```php

return [

	/* ------------------------------------------------------------------------------------------------
	|  Model settings
	| ------------------------------------------------------------------------------------------------
	*/
	'dbTable' => 'settings',
	'model' => 'App/Setting',

	/*
	|--------------------------------------------------------------------------
	| Routes group config
	|--------------------------------------------------------------------------
	|
	| The default group settings for the Paclage routes.
	|
	*/
	'route' => [
		'prefix' => '',
		'name' => 'settings',
		'middleware' => ['web'],
	],


	/* ------------------------------------------------------------------------------------------------
	 |  Cache settings (minutes)
	 | ------------------------------------------------------------------------------------------------
	 */
	'cache' => [
		'enable' => false,
		'time' => 15,//  cache time in minutes
	],


	'controller' => [
		/* ------------------------------------------------------------------------------------------------
		 | Set Responsible Controller For the Crud Process
		 | ------------------------------------------------------------------------------------------------
		 */
		'name' => 'GeoSot\Settings\Controllers\SettingController',
		/* ------------------------------------------------------------------------------------------------
	 |  Add MiddleWare on Controller Actions
	 | ------------------------------------------------------------------------------------------------
	 */
		'middleware' => [
			'index' => '',
			'store' => 'auth',
			'show' => '',
			'edit' => '',
			'update' => ['web', 'auth'],
			'delete' => '',
		]
	],

	/* ------------------------------------------------------------------------------------------------
	 | Set Views options
	 | ------------------------------------------------------------------------------------------------
	 */
	'blade' => [
		'layout' => 'settings::layout',
		'titleSection' => 'pageTitle',
		'mainContentSection' => 'content',
		'pushStyles' => 'styles',
		'pushScripts' => 'scripts',
		'pushModals' => 'modals'
	],


	/* ------------------------------------------------------------------------------------------------
	 |  Allow Deletions
	 | ------------------------------------------------------------------------------------------------
	 */
	'allowDelete' => true,


	/* ------------------------------------------------------------------------------------------------
	 |  Set the available Types of Settings.
	 |  By Default ['string', 'integer', 'date',  'collection', 'regex']
	 | ------------------------------------------------------------------------------------------------
	 */
	'availableTypes' => ['string', 'integer', 'collection', 'regex', 'date',],


	/* ------------------------------------------------------------------------------------------------
	 |  Set the available Types of Settings.
	 |  By Default ['string', 'integer', 'date', 'time'm 'collection', 'regex']
	 | ------------------------------------------------------------------------------------------------
	 */
	'parsingFormats' => [
		'date' => 'd/m/Y',
	],

	'collectionDelimiter' => '||',
];

  ```


###Usage

After package is installed the following methods are available for usage 
    
   
   ```php 
    // Check key existance  (returns Boolean)
    Settings::has('key');
    //or use Helper
    settings()->has('key');
    
     
    // Set new settings. It returns Boolean if settings was saved or not
    Settings::set('key','value','type');
    //Helper
    settings(['key','value','type']);
    settings()->set('key','value','type');
    
    // Get setting values
    Settings::get('key','returnValueInCaseOfNull');
    //Helper
    settings('key','returnValueInCaseOfNull')
    settings()->get('key','returnValueInCaseOfNull')
        
    
    //Use groups of settings separated by '.' example: group.key and get them as collections
    Settings::getGroup('group','returnValueInCaseOfNull');
    //Helper
    settings()->getGroup('key','returnValueInCaseOfNull')
    
    
    //Flush key from Cache
    Settings::flushKey('key');
    //Helper
    settings()->flushKey('key');
    
    //Delete key
    Settings::deleteKey('key');
    //Helper
    settings()->deleteKey('key');
   ````
    
