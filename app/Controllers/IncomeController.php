<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\IncomeModel;
use App\Models\CategoryModel;

class IncomeController extends BaseController
{
    protected $incomeModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->incomeModel = new IncomeModel();
        $this->categoryModel = new CategoryModel();
        helper(['form', 'url']);
    }

 public function index()
{
    // Check if user is logged in
    if (!auth()->loggedIn()) {
        return redirect()->to('/login')->with('error', 'Please login to access income page.');
    }
    
    $userId = auth()->id();
    
    $incomes = $this->incomeModel
        ->select('income.*, categories.name as category_name')
        ->join('categories', 'categories.id = income.category_id', 'left')
        ->where('income.user_id', $userId)
        ->orderBy('income.income_date', 'DESC')
        ->findAll();

    // Calculate statistics
    $currentMonth = date('Y-m');
    $lastMonth = date('Y-m', strtotime('-1 month'));
    $currentWeekStart = date('Y-m-d', strtotime('monday this week'));
    $currentWeekEnd = date('Y-m-d', strtotime('sunday this week'));

    // Monthly Income
    $monthlyIncome = $this->incomeModel
        ->selectSum('amount')
        ->where('user_id', $userId)
        ->where("DATE_FORMAT(income_date, '%Y-%m') = ", $currentMonth)
        ->get()
        ->getRow()->amount ?? 0;

    // Last Month Income
    $lastMonthIncome = $this->incomeModel
        ->selectSum('amount')
        ->where('user_id', $userId)
        ->where("DATE_FORMAT(income_date, '%Y-%m') = ", $lastMonth)
        ->get()
        ->getRow()->amount ?? 0;

    // This Week Income
    $thisWeekIncome = $this->incomeModel
        ->selectSum('amount')
        ->where('user_id', $userId)
        ->where('income_date >=', $currentWeekStart)
        ->where('income_date <=', $currentWeekEnd)
        ->get()
        ->getRow()->amount ?? 0;

    // Total Income
    $totalIncome = $this->incomeModel
        ->selectSum('amount')
        ->where('user_id', $userId)
        ->get()
        ->getRow()->amount ?? 0;

    // Average Monthly Income - FIXED VERSION
    // Average Monthly Income - SIMPLIFIED VERSION
$db = db_connect();
$averageQuery = $db->query("
    SELECT AVG(monthly_total) as avg_monthly 
    FROM (
        SELECT SUM(amount) as monthly_total 
        FROM income 
        WHERE user_id = ? 
        GROUP BY YEAR(income_date), MONTH(income_date)
    ) as monthly_totals
", [$userId]);

$averageMonthlyIncome = $averageQuery->getRow()->avg_monthly ?? 0;
    // Get categories for dropdown
    $categories = $this->categoryModel
        ->where('user_id', $userId)
        ->orderBy('name', 'ASC')
        ->findAll();

    return view('income/index', [
        'incomes' => $incomes,
        'monthly_income' => $monthlyIncome,
        'last_month_income' => $lastMonthIncome,
        'this_week_income' => $thisWeekIncome,
        'total_income' => $totalIncome,
        'average_monthly_income' => $averageMonthlyIncome,
        'categories' => $categories
    ]);
}
    public function create()
    {
        $userId = auth()->id();
        
        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('income/create', ['categories' => $categories]);
    }

    public function store()
    {
        $userId = auth()->id();

        $validationRules = [
            'title' => 'required|max_length[100]',
            'amount' => 'required|decimal',
            'income_date' => 'required|valid_date',
            'category_id' => 'required|integer',
            'description' => 'max_length[500]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $data = [
            'user_id' => $userId,
            'category_id' => $this->request->getPost('category_id'),
            'title' => $this->request->getPost('title'),
            'amount' => $this->request->getPost('amount'),
            'income_date' => $this->request->getPost('income_date'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->incomeModel->save($data)) {
            return redirect()->to('/income')->with('success', 'Income added successfully!');
        } else {
            return redirect()->back()->with('errors', $this->incomeModel->errors())->withInput();
        }
    }




    // public function __construct()
    // {
    //     $this->incomeModel = new IncomeModel();
    //     $this->categoryModel = new CategoryModel();
    //     helper(['form', 'url']);
    // }

    // public function index()
    // {
    //     // Check if user is logged in
    //     if (!auth()->loggedIn()) {
    //         return redirect()->to('/login')->with('error', 'Please login to access income page.');
    //     }
        
    //     $userId = auth()->id();
        
    //     $incomes = $this->incomeModel
    //         ->select('income.*, categories.name as category_name')
    //         ->join('categories', 'categories.id = income.category_id', 'left')
    //         ->where('income.user_id', $userId)
    //         ->orderBy('income.income_date', 'DESC')
    //         ->findAll();

    //     // Calculate statistics
    //     $currentMonth = date('Y-m');
    //     $monthlyIncome = $this->incomeModel
    //         ->selectSum('amount')
    //         ->where('user_id', $userId)
    //         ->where("DATE_FORMAT(income_date, '%Y-%m') = ", $currentMonth)
    //         ->get()
    //         ->getRow()->amount ?? 0;

    //     $totalIncome = $this->incomeModel
    //         ->selectSum('amount')
    //         ->where('user_id', $userId)
    //         ->get()
    //         ->getRow()->amount ?? 0;

    //     return view('income/index', [
    //         'incomes' => $incomes,
    //         'monthly_income' => $monthlyIncome,
    //         'total_income' => $totalIncome
    //     ]);
    // }

    // ... rest of your methods with similar auth checks


    public function edit($id)
    {
        $userId = auth()->id();
        
        $income = $this->incomeModel
            ->where('user_id', $userId)
            ->find($id);

        if (!$income) {
            return redirect()->to('/income')->with('error', 'Income not found.');
        }

        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('income/edit', [
            'income' => $income,
            'categories' => $categories
        ]);
    }

    public function update($id)
    {
        $userId = auth()->id();
        
        $income = $this->incomeModel
            ->where('user_id', $userId)
            ->find($id);

        if (!$income) {
            return redirect()->to('/income')->with('error', 'Income not found.');
        }

        $validationRules = [
            'title' => 'required|max_length[100]',
            'amount' => 'required|decimal',
            'income_date' => 'required|valid_date',
            'category_id' => 'required|integer',
            'description' => 'max_length[500]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'title' => $this->request->getPost('title'),
            'amount' => $this->request->getPost('amount'),
            'income_date' => $this->request->getPost('income_date'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->incomeModel->update($id, $data)) {
            return redirect()->to('/income')->with('success', 'Income updated successfully!');
        } else {
            return redirect()->back()->with('errors', $this->incomeModel->errors())->withInput();
        }
    }

    public function delete($id)
    {
        $userId = auth()->id();
        
        $income = $this->incomeModel
            ->where('user_id', $userId)
            ->find($id);
        
        if ($income && $this->incomeModel->delete($id)) {
            return redirect()->to('/income')->with('success', 'Income deleted successfully!');
        } else {
            return redirect()->to('/income')->with('error', 'Income not found.');
        }
    }
}