<?php

namespace GeoSot\BaseAdmin\Database\Seeders;

use App\Models\Users\User;
use Illuminate\Support\Arr;

class UsersSeeder extends BaseSeeder
{
    protected $class = User::class;

    /**
     * Run the database seederss.
     */
    public function seedData()
    {
        $static = new $this->class();

        foreach ($this->data() as $index => $userData) {
            $user = $static::create(Arr::except($userData, 'roles'));
            $user->attachRoles($userData['roles']);
            $this->creatingDataMsg($userData['first_name'].' '.$userData['last_name']);
        }
    }

    /**
     * @return array
     */
    protected function data()
    {
        return [
            'admin' => [
                'email'      => 'admin@example.com',
                'password'   => bcrypt('123456'),
                'first_name' => 'Admin',
                'last_name'  => 'AdminLast',
                'roles'      => ['god', 'user'],
            ],
        ];
    }
}
