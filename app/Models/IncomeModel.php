<?php

namespace App\Models;

use CodeIgniter\Model;

class IncomeModel extends Model
{
    protected $table            = 'income';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id', 
        'category_id', 
        'title', 
        'amount', 
        'income_date', 
        'description'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title' => 'required|max_length[100]',
        'amount' => 'required|decimal',
        'income_date' => 'required|valid_date',
        'category_id' => 'required|integer',
        'description' => 'max_length[500]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Income title is required.',
            'max_length' => 'Title cannot exceed 100 characters.'
        ],
        'amount' => [
            'required' => 'Amount is required.',
            'decimal' => 'Amount must be a valid number.'
        ],
        'income_date' => [
            'required' => 'Income date is required.',
            'valid_date' => 'Enter a valid date.'
        ],
        'category_id' => [
            'required' => 'Category is required.',
            'integer' => 'Category ID must be a number.'
        ]
    ];
}