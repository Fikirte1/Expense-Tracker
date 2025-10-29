<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table            = 'expenses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Add allowed fields
    protected $allowedFields = [
        'user_id', 
        'category_id', 
        'title', 
        'amount', 
        'expense_date', 
        'receipt'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'title'       => 'required|max_length[100]',
        'amount'      => 'required|decimal',
        'expense_date'=> 'required|valid_date',
        'category_id' => 'required|integer',
        'receipt'     => 'permit_empty|max_size[receipt,2048]|ext_in[receipt,jpg,jpeg,png,pdf,gif]',

    ];

    protected $validationMessages = [
        'title' => [
            'required'   => 'Expense title is required.',
            'max_length' => 'Title cannot exceed 100 characters.'
        ],
        'amount' => [
            'required' => 'Amount is required.',
            'decimal'  => 'Amount must be a valid number.'
        ],
        'expense_date' => [
            'required'   => 'Expense date is required.',
            'valid_date' => 'Enter a valid date.'
        ],
        'category_id' => [
            'required' => 'Category is required.',
            'integer'  => 'Category ID must be a number.'
        ],
        'receipt' => [
            'max_size' => 'Receipt file size should not exceed 2MB.',
            'ext_in'   => 'Only JPG, JPEG, PNG, PDF, and GIF files are allowed for receipts.'
        ]
    ];

    protected $allowCallbacks = true;
}
