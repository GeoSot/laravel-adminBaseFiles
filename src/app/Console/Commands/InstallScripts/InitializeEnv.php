<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;

use GeoSot\EnvEditor\EnvEditor;
use Illuminate\Support\Facades\DB;

class InitializeEnv extends BaseInstallCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:install:initializeEnv';
    protected $hidden = true;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    protected $envValuesToSet = [
        'APP_NAME'          => 'Application Name',
        'APP_URL'           => 'Application Url (Include http(s)://)',
        'DB_CONNECTION'     => 'Database Connection',
        'DB_HOST'           => 'Database Host',
        'DB_PORT'           => 'Database Port',
        'DB_DATABASE'       => 'Database Name',
        'DB_USERNAME'       => 'Database UserName',
        'DB_PASSWORD'       => 'Database Password',
        'MAIL_HOST'         => 'Mail Host',
        'MAIL_PORT'         => 'Mail Port',
        'MAIL_USERNAME'     => 'Mail UserName',
        'MAIL_PASSWORD'     => 'Mail Password',
        'MAIL_FROM_ADDRESS' => 'Mail Address',
        'MAIL_FROM_NAME'    => 'Mail "From Name"',
    ];


    /**
     *  Execute the console command.
     *
     * @throws \GeoSot\EnvEditor\Exceptions\EnvException
     */
    public function handle()
    {
        if (!$this->canExecute()) {
            return false;
        }

        if (!$this->confirm('Do you wish to set .env keys?')) {
            return false;
        }
        $env = new EnvEditor();

        foreach ($this->envValuesToSet as $key => $text) {
            $value = $this->ask('Set the ' . $text, env($key));
            $group = explode('_', $key, 2)[0];
            $env->keyExists($key) ? $env->editKey($key, $value) : $env->addKey($key, $value, ['group' => $group]);

        }
        $this->databaseConnectionIsValid();
        return true;
    }

    /**
     * Is the database connection valid?
     * @return bool
     */
    protected function databaseConnectionIsValid()
    {
        $dbName = DB::connection()->getDatabaseName();
        try {
            DB::connection()->reconnect(env('DB_DATABASE'));
            $this->info("Connected successfully to Database: " . $dbName);

            return true;

        } catch (\Exception $e) {
            $this->error("Check Database Settings. App couldn't connect to Database: " . $dbName);

            return false;
        }
    }
}
