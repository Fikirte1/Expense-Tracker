<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeAndColorToCategories extends Migration
{
    public function up()
    {
        // Add type column
        $this->forge->addColumn('categories', [
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['income', 'expense'],
                'default' => 'expense',
                'after' => 'name'
            ]
        ]);

        // Add color column
        $this->forge->addColumn('categories', [
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 7,
                'default' => '#007bff',
                'after' => 'type'
            ]
        ]);

        // Set default types and colors for existing categories
        $db = \Config\Database::connect();
        
        // Get all existing categories
        $categories = $db->table('categories')->get()->getResult();
        
        // Default colors for categories
        $colors = ['#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#6610f2'];
        
        foreach ($categories as $index => $category) {
            $updateData = [
                'type' => 'expense', // Default all existing categories to expense
                'color' => $colors[$index % count($colors)] // Assign colors cyclically
            ];
            
            $db->table('categories')
               ->where('id', $category->id)
               ->update($updateData);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('categories', 'color');
        $this->forge->dropColumn('categories', 'type');
    }
}