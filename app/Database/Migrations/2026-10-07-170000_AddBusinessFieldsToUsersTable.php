<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBusinessFieldsToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'name',
            ],
            'business_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'phone',
            ],
            'business_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'business_name',
            ],
            'business_address' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'business_type',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['phone', 'business_name', 'business_type', 'business_address']);
    }
}
