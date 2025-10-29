<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BudgetModel;
use App\Models\CategoryModel;
use App\Models\ExpenseModel;
use App\Models\IncomeModel;

class BudgetController extends BaseController
{
    protected $budgetModel;
    protected $categoryModel;
    protected $expenseModel;
    protected $incomeModel;

    public function __construct()
    {
        $this->budgetModel = new BudgetModel();
        $this->categoryModel = new CategoryModel();
        $this->expenseModel = new ExpenseModel();
        $this->incomeModel = new IncomeModel();
        helper(['form', 'url', 'number']);
    }
public function index()
{
    if (!auth()->loggedIn()) {
        return redirect()->to('/login')->with('error', 'Please login to access budget planning.');
    }

    $userId = auth()->id();
    
    // Get current month and year from query parameters or use current
    $year = $this->request->getGet('year') ?: date('Y');
    $month = $this->request->getGet('month') ?: date('m');

    // Fetch budgets from database
    $budgets = $this->budgetModel->getBudgetsWithCategories($userId, $year, $month);

    // Get all expense categories for dropdown
    $categories = $this->categoryModel
        ->where('user_id', $userId)
        ->where('type', 'expense')
        ->orderBy('name', 'ASC')
        ->findAll();

    // Calculate total budget and spent amounts
    $totalBudget = 0;
    $totalSpent = 0;
    
    foreach ($budgets as &$budget) {
        // Get actual spending for this category in the selected month
        $spent = $this->expenseModel
            ->selectSum('amount')
            ->where('user_id', $userId)
            ->where('category_id', $budget['category_id'])
            ->where('YEAR(expense_date)', $year)
            ->where('MONTH(expense_date)', $month)
            ->get()
            ->getRow()->amount ?? 0;

        $budget['spent'] = $spent;
        $budget['remaining'] = $budget['amount'] - $spent;
        $budget['percentage'] = $budget['amount'] > 0 ? min(100, ($spent / $budget['amount']) * 100) : 0;
        $budget['status'] = $this->getBudgetStatus($spent, $budget['amount']);

        $totalBudget += $budget['amount'];
        $totalSpent += $spent;
    }

    // Get total income for the month
    $totalIncome = $this->incomeModel
        ->selectSum('amount')
        ->where('user_id', $userId)
        ->where('YEAR(income_date)', $year)
        ->where('MONTH(income_date)', $month)
        ->get()
        ->getRow()->amount ?? 0;

    // Get budget suggestions
    $budgetSuggestions = $this->getBudgetSuggestions($userId, $year, $month);

    // Get budget alerts
    $budgetAlerts = $this->getBudgetAlerts($budgets);

    return view('budget/index', [
        'budgets' => $budgets,
        'categories' => $categories,
        'total_budget' => $totalBudget,
        'total_spent' => $totalSpent,
        'total_remaining' => $totalBudget - $totalSpent,
        'total_income' => $totalIncome,
        'budget_suggestions' => $budgetSuggestions,
        'budget_alerts' => $budgetAlerts,
        'current_year' => $year,
        'current_month' => $month,
        'months' => $this->getMonths(),
        'years' => $this->getYears()
    ]);
}
public function store()
{
    if (!auth()->loggedIn()) {
        return redirect()->to('/login');
    }

    $userId = auth()->id();

    $validationRules = [
        'category_id' => 'required|integer',
        'amount' => 'required|decimal|greater_than[0]',
        'month_year' => 'required|valid_date'
    ];

    if (!$this->validate($validationRules)) {
        return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
    }

    $categoryId = $this->request->getPost('category_id');
    $monthYear = $this->request->getPost('month_year');
    
    // FIX: Convert YYYY-MM to YYYY-MM-DD format (first day of month)
    $monthYearFormatted = $monthYear . '-01';

    // Check if budget already exists
    $existingBudget = $this->budgetModel->budgetExists($userId, $categoryId, $monthYearFormatted);

    if ($existingBudget) {
        return redirect()->back()->with('error', 'Budget already exists for this category and month.')->withInput();
    }

    $data = [
        'user_id' => $userId,
        'category_id' => $categoryId,
        'amount' => $this->request->getPost('amount'),
        'month_year' => $monthYearFormatted, // Use formatted date
        'description' => $this->request->getPost('description')
    ];

    if ($this->budgetModel->save($data)) {
        return redirect()->to('/budget')->with('success', 'Budget added successfully!');
    } else {
        return redirect()->back()->with('errors', $this->budgetModel->errors())->withInput();
    }
}

    public function update($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();
        
        // Verify the budget belongs to the user
        $budget = $this->budgetModel
            ->where('user_id', $userId)
            ->find($id);

        if (!$budget) {
            return redirect()->to('/budget')->with('error', 'Budget not found.');
        }

        $validationRules = [
            'amount' => 'required|decimal|greater_than[0]',
            'description' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $data = [
            'amount' => $this->request->getPost('amount'),
            'description' => $this->request->getPost('description')
        ];

        log_message('debug', 'Updating budget ID: ' . $id . ' with data: ' . print_r($data, true));

        try {
            if ($this->budgetModel->update($id, $data)) {
                log_message('debug', 'Budget updated successfully');
                return redirect()->to('/budget')->with('success', 'Budget updated successfully!');
            } else {
                $modelErrors = $this->budgetModel->errors();
                log_message('debug', 'Model update errors: ' . print_r($modelErrors, true));
                return redirect()->back()->with('errors', $modelErrors)->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in budget update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = auth()->id();
        
        // Verify the budget belongs to the user
        $budget = $this->budgetModel
            ->where('user_id', $userId)
            ->find($id);

        if (!$budget) {
            return $this->response->setJSON(['success' => false, 'message' => 'Budget not found.']);
        }

        log_message('debug', 'Deleting budget ID: ' . $id);

        try {
            if ($this->budgetModel->delete($id)) {
                log_message('debug', 'Budget deleted successfully');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Budget deleted successfully!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete budget.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in budget delete: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

   public function quickAdd()
{
    if (!auth()->loggedIn()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
    }

    $userId = auth()->id();
    $categoryId = $this->request->getPost('category_id');
    $amount = $this->request->getPost('amount');
    $monthYear = $this->request->getPost('month_year') ?: date('Y-m');
    
    // FIX: Convert to proper date format
    $monthYearFormatted = $monthYear . '-01';

    // Check if budget already exists
    $existingBudget = $this->budgetModel->budgetExists($userId, $categoryId, $monthYearFormatted);

    if ($existingBudget) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Budget already exists for this category and month.'
        ]);
    }

    $data = [
        'user_id' => $userId,
        'category_id' => $categoryId,
        'amount' => $amount,
        'month_year' => $monthYearFormatted
    ];

    if ($this->budgetModel->save($data)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Budget added successfully!'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to add budget.'
        ]);
    }
}

   public function duplicateBudget()
{
    if (!auth()->loggedIn()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
    }

    $userId = auth()->id();
    $fromMonth = $this->request->getPost('from_month') . '-01'; // Add day
    $toMonth = $this->request->getPost('to_month') . '-01'; // Add day

    // Rest of the method remains the same...
    $sourceBudgets = $this->budgetModel->getBudgetsForMonth($userId, $fromMonth);

    if (empty($sourceBudgets)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No budgets found for the selected month.'
        ]);
    }

    $duplicated = 0;
    foreach ($sourceBudgets as $budget) {
        // Check if budget already exists for target month
        $existing = $this->budgetModel->budgetExists($userId, $budget['category_id'], $toMonth);

        if (!$existing) {
            $newBudget = [
                'user_id' => $userId,
                'category_id' => $budget['category_id'],
                'amount' => $budget['amount'],
                'month_year' => $toMonth,
                'description' => $budget['description']
            ];
            
            if ($this->budgetModel->save($newBudget)) {
                $duplicated++;
            }
        }
    }

    return $this->response->setJSON([
        'success' => true,
        'message' => "Successfully duplicated {$duplicated} budgets to {$toMonth}."
    ]);
}

    public function getBudgetStats()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $userId = auth()->id();
        $year = $this->request->getGet('year') ?: date('Y');
        $month = $this->request->getGet('month') ?: date('m');

        $stats = $this->calculateBudgetStats($userId, $year, $month);

        return $this->response->setJSON($stats);
    }

    private function getBudgetStatus($spent, $budget)
    {
        if ($budget == 0) return 'no-budget';
        
        $percentage = ($spent / $budget) * 100;
        
        if ($percentage <= 75) return 'under-budget';
        if ($percentage <= 100) return 'within-budget';
        return 'over-budget';
    }

    private function getBudgetSuggestions($userId, $year, $month)
    {
        $suggestions = [];
        
        // Get categories without budgets for this month
        $categoriesWithoutBudget = $this->categoryModel
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->whereNotIn('id', function($builder) use ($userId, $year, $month) {
                return $builder->select('category_id')
                    ->from('budgets')
                    ->where('user_id', $userId)
                    ->where('YEAR(month_year)', $year)
                    ->where('MONTH(month_year)', $month);
            })
            ->findAll();

        foreach ($categoriesWithoutBudget as $category) {
            // Calculate average spending for this category from previous months
            $avgSpending = $this->expenseModel
                ->selectAvg('amount')
                ->where('user_id', $userId)
                ->where('category_id', $category['id'])
                ->where("DATE_FORMAT(expense_date, '%Y-%m') < ", $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT))
                ->get()
                ->getRow()->amount ?? 0;

            if ($avgSpending > 0) {
                $suggestions[] = [
                    'category_id' => $category['id'],
                    'category_name' => $category['name'],
                    'suggested_amount' => round($avgSpending * 1.1, 2), // 10% buffer
                    'reason' => 'Based on your average spending'
                ];
            } else {
                // If no spending history, suggest a default amount
                $suggestions[] = [
                    'category_id' => $category['id'],
                    'category_name' => $category['name'],
                    'suggested_amount' => 1000.00, // Default suggestion
                    'reason' => 'Recommended starting budget'
                ];
            }
        }

        return $suggestions;
    }

    private function getBudgetAlerts($budgets)
    {
        $alerts = [];

        foreach ($budgets as $budget) {
            if ($budget['status'] == 'over-budget') {
                $alerts[] = [
                    'type' => 'danger',
                    'message' => "{$budget['category_name']} is over budget by ₹" . 
                                number_format(abs($budget['remaining']), 2),
                    'category' => $budget['category_name']
                ];
            } elseif ($budget['percentage'] >= 90) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "{$budget['category_name']} is approaching budget limit (" . number_format($budget['percentage'], 1) . "%)",
                    'category' => $budget['category_name']
                ];
            }
        }

        return $alerts;
    }

    private function calculateBudgetStats($userId, $year, $month)
    {
        $totalBudget = $this->budgetModel->getTotalBudget($userId, $year, $month);

        $totalSpent = $this->expenseModel
            ->selectSum('amount')
            ->where('user_id', $userId)
            ->where('YEAR(expense_date)', $year)
            ->where('MONTH(expense_date)', $month)
            ->get()
            ->getRow()->amount ?? 0;

        $totalIncome = $this->incomeModel
            ->selectSum('amount')
            ->where('user_id', $userId)
            ->where('YEAR(income_date)', $year)
            ->where('MONTH(income_date)', $month)
            ->get()
            ->getRow()->amount ?? 0;

        // Count over-budget categories
        $overBudgetCount = 0;
        $budgets = $this->budgetModel->getBudgetsWithCategories($userId, $year, $month);
        
        foreach ($budgets as $budget) {
            $spent = $this->expenseModel
                ->selectSum('amount')
                ->where('user_id', $userId)
                ->where('category_id', $budget['category_id'])
                ->where('YEAR(expense_date)', $year)
                ->where('MONTH(expense_date)', $month)
                ->get()
                ->getRow()->amount ?? 0;

            if ($spent > $budget['amount']) {
                $overBudgetCount++;
            }
        }

        return [
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'total_income' => $totalIncome,
            'over_budget_count' => $overBudgetCount,
            'savings_rate' => $totalIncome > 0 ? (($totalIncome - $totalSpent) / $totalIncome) * 100 : 0
        ];
    }

    private function getMonths()
    {
        return [
            '01' => 'January', '02' => 'February', '03' => 'March',
            '04' => 'April', '05' => 'May', '06' => 'June',
            '07' => 'July', '08' => 'August', '09' => 'September',
            '10' => 'October', '11' => 'November', '12' => 'December'
        ];
    }

    private function getYears()
    {
        $currentYear = date('Y');
        $years = [];
        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }
}