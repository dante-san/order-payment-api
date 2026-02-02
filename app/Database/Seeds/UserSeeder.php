<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Smruti Das'],
            ['name' => 'Anupama Sarangi'],
            ['name' => 'Akankhya Maharana'],
        ];

        foreach ($data as $user) {
            $this->db->table('users')->insert($user);
        }
    }
}
