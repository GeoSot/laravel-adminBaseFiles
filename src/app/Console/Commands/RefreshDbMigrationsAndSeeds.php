<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class RefreshDbMigrationsAndSeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:dbRefresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh All the Migrations (truncate DB), continues with teh default seeding, amd cleans used Directories px:uploads folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tables_array = DB::select('SHOW TABLES');
        Schema::disableForeignKeyConstraints();
        if (!empty($tables_array)) {
            foreach (DB::select('SHOW TABLES') as $table) {
                $table_array = get_object_vars($table);
                Schema::dropIfExists($table_array[key($table_array)]);
            }
        }
        $commands = ['migrate', 'db:seed'];//artisan queue:restart  artisan queue:work
        foreach ($commands as $command) {
            $this->call($command, []);
            // $html .= '<pre>' . Artisan::output() . '</pre>';
        }
        Schema::enableForeignKeyConstraints();
        File::cleanDirectory(Storage::disk('uploads')->path(''));
        $this->info('New Data Are READY :), Return Home and keep trying ');
    }
}
