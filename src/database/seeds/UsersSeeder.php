<?php

namespace GeoSot\BaseAdmin\Seeds;

use App\Models\Users\User;
use Illuminate\Support\Arr;

class UsersSeeder extends BaseSeeder
{
    protected $class = User::class;

    /**
     * Run the database seeds.
     */
    public function seedData()
    {
        $static = new $this->class();

        foreach ($this->data() as $index => $userData) {

            $user = $static::create(Arr::except($userData, 'roles'));
            $user->attachRoles($userData['roles']);
            $this->creatingDataMsg($userData['first_name'] . ' ' . $userData['last_name']);
        }

    }


    /**
     * @return array
     */
    protected function data()
    {
        return [
            'geo' => [
                'email'      => 'george_sotis@yahoo.gr',
                'password'   => bcrypt('123456'),
                'first_name' => 'George',
                'last_name'  => 'Sot',
                'roles'      => ['god', 'user'],
            ],
        ];
    }
}
