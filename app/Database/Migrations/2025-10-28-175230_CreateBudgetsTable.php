<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBudgetsTable extends Migration
{
    public function up()
    {
         $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'category_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'month_year' => ['type' => 'DATE'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('budgets');
    }

    public function down()
    {
        $this->forge->dropTable('budgets');
    }
}
