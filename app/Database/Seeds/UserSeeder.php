<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            [
                'username'   => 'admin',
                'email'      => 'admin@example.com',
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'name'       => 'Administrator',
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username'   => 'inactive_user',
                'email'      => 'inactive@example.com',
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'name'       => 'Inactive User',
                'status'     => 'inactive',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert or ignore if already seeded
        foreach ($data as $user) {
            $existing = $this->db->table('users')->where('username', $user['username'])->get()->getRow();
            if (! $existing) {
                $this->db->table('users')->insert($user);
            }
        }
    }
}
