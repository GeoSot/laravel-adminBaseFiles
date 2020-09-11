<?php

namespace GeoSot\BaseAdmin\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

abstract class BaseSeeder extends Seeder
{
    protected $class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('');
        $this->truncateTables();
        $this->seedData();
        $this->finishSeedingMsg();
        $this->command->info('');
    }

    /**
     * Truncates all the model  tables
     *
     * @return    void
     */
    protected function truncateTables()
    {
        if (app()->environment(['production', 'live'])) {
            $this->command->info('Truncating is DISABLED on "' . app()->environment() . '" Environment');

            return;
        }
        $this->command->info('Truncating ' . (new $this->class ())->getTable() . ' Table');
        Schema::disableForeignKeyConstraints();
        $this->class::truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function seedData()
    {
        //
        //        foreach ($this->data() as $index => $el) {
        //            $elem = factory($this->class)->create([
        //                'title' => $el['title'],
        //                'slug'  => \Cviebrock\EloquentSluggable\Services\SlugService::createSlug($this->class, 'slug', $el['title'], ['unique' => true])
        //            ]);
        //
        //            $this->creatingDataMsg($el['title']);
        //        }
        //
        //
    }

    /**
     * this message shown in your terminal after running db:seed command
     *
     * @return    void
     */
    protected function finishSeedingMsg()
    {
        //this message shown in your terminal after running db:seed command
        $this->command->info(class_basename($this->class) . ' table seeded ');
    }

    /**
     * Truncates all the model  tables
     *
     * @return    array
     */
    protected function data()
    {
        return [];
    }

    /**
     * this message shown in your terminal after running db:seed command
     *
     * @param string $name
     *
     * @return    void
     */
    protected function creatingDataMsg(string $name)
    {
        $this->command->info('Creating ' . class_basename($this->class) . '  ' . ucfirst($name));
    }
}
