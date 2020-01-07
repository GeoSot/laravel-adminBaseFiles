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
    > - publishViews                                                                                                                          >
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
 
4. Changes to Auth Controllers

> LoginController
```php
 
    use AuthenticatesUsers, LoginFormTrait;
  
    public function showLoginForm()
    {
        $form = $this->getForm();

        return view('baseAdmin::auth.login', compact('form'));
    }
  
```
> RegisterController
```php
 
 use RegistersUsers, RegisterFormTrait;

  
    public function showRegistrationForm()
    {
        $form = $this->getForm();

        return view('auth.register', compact('form'));
    }
  
```

> ResetPasswordController
```php
 
 use ResetsPasswords, ResetPasswordFormTrait;

    public function showResetForm(Request $request, $token = null)
    {

        $form = $this->getForm($token, $request->input('email'));

        return view('auth.passwords.reset', compact('form', 'token', 'email'));
    }
  
```

> ForgotPasswordController
```php
 
    use SendsPasswordResetEmails,ForgotPasswordFormTrait;
    
    public function showLinkRequestForm()
    {
        $form = $this->getForm();

        return view('baseAdmin::auth.passwords.email', compact('form'));
    }
  
```
> VerificationController


> RouteServiceProvider 

 Put/Change the following Constant
```php
   public const HOME = '/;
```


> Change 'config\auth'

```php

 providers->user->model = App\Models\Users\User::class

 guards->api->driver =passport   

```
(and delete the File app\User.php)
