<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetModel extends Model
{
    protected $table            = 'budgets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id', 
        'category_id', 
        'amount', 
        'month_year', 
        'description'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'category_id' => 'required|integer',
        'amount' => 'required|decimal',
        'month_year' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'category_id' => [
            'required' => 'Please select a category.'
        ],
        'amount' => [
            'required' => 'Budget amount is required.',
            'decimal' => 'Please enter a valid amount.'
        ],
        'month_year' => [
            'required' => 'Please select a month and year.',
            'valid_date' => 'Please enter a valid date.'
        ]
    ];

    /**
     * Get budgets with category information
     */
    // public function getBudgetsWithCategories($userId, $year, $month)
    // {
    //     return $this->select('budgets.*, categories.name as category_name, categories.color as category_color')
    //                 ->join('categories', 'categories.id = budgets.category_id')
    //                 ->where('budgets.user_id', $userId)
    //                 ->where('YEAR(budgets.month_year)', $year)
    //                 ->where('MONTH(budgets.month_year)', $month)
    //                 ->orderBy('categories.name', 'ASC')
    //                 ->findAll();
    // }
public function getBudgetsWithCategories($userId, $year, $month)
{
    log_message('debug', 'Model: Fetching budgets for user ' . $userId . ', year ' . $year . ', month ' . $month);
    
    $result = $this->select('budgets.*, categories.name as category_name, categories.color as category_color')
                ->join('categories', 'categories.id = budgets.category_id')
                ->where('budgets.user_id', $userId)
                ->where('YEAR(budgets.month_year)', $year)
                ->where('MONTH(budgets.month_year)', $month)
                ->orderBy('categories.name', 'ASC')
                ->findAll();
    
    log_message('debug', 'Model: Found ' . count($result) . ' budgets');
    return $result;
}
    /**
     * Check if budget exists for category and month
     */
    public function budgetExists($userId, $categoryId, $monthYear)
    {
        return $this->where('user_id', $userId)
                    ->where('category_id', $categoryId)
                    ->where('month_year', $monthYear)
                    ->first();
    }

    /**
     * Get total budget amount for period
     */
    public function getTotalBudget($userId, $year, $month)
    {
        $result = $this->selectSum('amount')
                      ->where('user_id', $userId)
                      ->where('YEAR(month_year)', $year)
                      ->where('MONTH(month_year)', $month)
                      ->first();
        
        return $result['amount'] ?? 0;
    }

    /**
     * Get budgets for duplicate functionality
     */
    public function getBudgetsForMonth($userId, $monthYear)
    {
        return $this->where('user_id', $userId)
                    ->where('month_year', $monthYear)
                    ->findAll();
    }
}