<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailVerificationToUsersTable extends Migration
{
    public function up()
    {
        // 1. Modifikasi ENUM status untuk menyertakan 'pending'
        $this->forge->modifyColumn('users', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'pending'],
                'default'    => 'pending',
            ],
        ]);

        // 2. Tambahkan kolom verifikasi email & masa kedaluwarsa 3 hari
        $this->forge->addColumn('users', [
            'verification_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'after'      => 'status',
            ],
            'verification_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'verification_code',
            ],
            'email_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'verification_expires_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['verification_code', 'verification_expires_at', 'email_verified_at']);
        $this->forge->modifyColumn('users', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
            ],
        ]);
    }
}
