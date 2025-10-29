<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Add allowed fields
    protected $allowedFields    = ['user_id', 'name'];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation (optional)
    protected $validationRules = [
        'name' => 'required|max_length[100]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required'   => 'Category name is required.',
            'max_length' => 'Category name cannot exceed 100 characters.'
        ]
    ];

    // Callbacks (if needed)
    protected $allowCallbacks = true;
}
