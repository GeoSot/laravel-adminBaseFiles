<?php

namespace GeoSot\BaseAdmin\Seeds;
class UsersSeeder extends BaseSeeder
{
    protected $class = \App\Models\Users\User::class;

    /**
     * Run the database seeds.
     */
    public function seedData()
    {
        $static = new $this->class();

        foreach ($this->data() as $index => $userData) {
            $user = $static->create([
                'email'       => $userData['email'],
                'password'    => $userData['password'],
                'first_name'  => $userData['first_name'],
                'last_name'   => $userData['last_name'],
                'modified_by' => 1,
            ]);
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
