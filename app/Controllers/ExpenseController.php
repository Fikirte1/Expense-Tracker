<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpenseModel;
use App\Models\CategoryModel;

class ExpenseController extends BaseController
{
    protected $expenseModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->expenseModel = new ExpenseModel();
        $this->categoryModel = new CategoryModel();
        helper(['form', 'url']);
    }

    // ==================== FILTER METHODS ====================
    private function applyFilters(&$query)
    {
        $filters = [];
        
        // Category filter
        if ($categoryId = $this->request->getGet('category')) {
            $query->where('expenses.category_id', $categoryId);
            $filters['category'] = $categoryId;
        }
        
        // Date range filter
        if ($startDate = $this->request->getGet('start_date')) {
            $query->where('expenses.expense_date >=', $startDate);
            $filters['start_date'] = $startDate;
        }
        
        if ($endDate = $this->request->getGet('end_date')) {
            $query->where('expenses.expense_date <=', $endDate);
            $filters['end_date'] = $endDate;
        }
        
        // Amount range filter
        if ($minAmount = $this->request->getGet('min_amount')) {
            $query->where('expenses.amount >=', $minAmount);
            $filters['min_amount'] = $minAmount;
        }
        
        if ($maxAmount = $this->request->getGet('max_amount')) {
            $query->where('expenses.amount <=', $maxAmount);
            $filters['max_amount'] = $maxAmount;
        }
        
        // Search by title
        if ($search = $this->request->getGet('search')) {
            $query->like('expenses.title', $search);
            $filters['search'] = $search;
        }
        
        return $filters;
    }

    private function calculateStatistics($userId, $filters)
    {
        // Monthly total (always for current month, ignoring filters)
        $currentMonth = date('Y-m');
        $monthlyTotal = $this->expenseModel
            ->selectSum('amount')
            ->where('user_id', $userId)
            ->where("DATE_FORMAT(expense_date, '%Y-%m') = ", $currentMonth)
            ->get()
            ->getRow()->amount ?? 0;

        // Category count
        $categoryCount = $this->categoryModel
            ->where('user_id', $userId)
            ->countAllResults();

        // Total expenses (all time)
        $totalExpenses = $this->expenseModel
            ->selectSum('amount')
            ->where('user_id', $userId)
            ->get()
            ->getRow()->amount ?? 0;

        // Filtered total (with same filters as main query)
        $filteredQuery = $this->expenseModel
            ->selectSum('amount')
            ->where('user_id', $userId);

        $this->applyFiltersToQuery($filteredQuery, $filters);
        
        $filteredTotal = $filteredQuery->get()->getRow()->amount ?? 0;

        return [
            'monthly_total' => $monthlyTotal,
            'category_count' => $categoryCount,
            'total_expenses' => $totalExpenses,
            'filtered_total' => $filteredTotal
        ];
    }

    private function applyFiltersToQuery(&$query, $filters)
    {
        foreach ($filters as $key => $value) {
            switch ($key) {
                case 'category':
                    $query->where('category_id', $value);
                    break;
                case 'start_date':
                    $query->where('expense_date >=', $value);
                    break;
                case 'end_date':
                    $query->where('expense_date <=', $value);
                    break;
                case 'min_amount':
                    $query->where('amount >=', $value);
                    break;
                case 'max_amount':
                    $query->where('amount <=', $value);
                    break;
                case 'search':
                    $query->like('title', $value);
                    break;
            }
        }
    }

    // ==================== MAIN CRUD METHODS ====================
    public function index()
    {
        $userId = auth()->id();
        
        // Start building the query
        $expenseQuery = $this->expenseModel
            ->select('expenses.*, categories.name as category_name')
            ->join('categories', 'categories.id = expenses.category_id', 'left')
            ->where('expenses.user_id', $userId);

        // Apply filters if they exist
        $filters = $this->applyFilters($expenseQuery);
        
        // Get the filtered expenses
        $expenses = $expenseQuery
            ->orderBy('expenses.expense_date', 'DESC')
            ->orderBy('expenses.created_at', 'DESC')
            ->findAll();

        // Calculate statistics with same filters
        $stats = $this->calculateStatistics($userId, $filters);

        // Get categories for filter dropdown
        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('expenses/index', [
            'expenses' => $expenses,
            'categories' => $categories,
            'filters' => $filters,
            'monthly_total' => $stats['monthly_total'],
            'category_count' => $stats['category_count'],
            'total_expenses' => $stats['total_expenses'],
            'filtered_total' => $stats['filtered_total']
        ]);
    }

    public function create()
    {
        $userId = auth()->id();
        
        // Ensure categories exist
        $this->ensureCategoriesExist($userId);
        
        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('expenses/create', ['categories' => $categories]);
    }

    public function store()
    {
        $userId = auth()->id();

        // Validate input - receipt is OPTIONAL
        $validationRules = [
            'title' => 'required|max_length[100]',
            'amount' => 'required|decimal',
            'expense_date' => 'required|valid_date',
            'category_id' => 'required|integer'
            // NO receipt validation rules - completely optional
        ];

        $validationMessages = [
            'title' => [
                'required' => 'Expense title is required.',
                'max_length' => 'Title cannot exceed 100 characters.'
            ],
            'amount' => [
                'required' => 'Amount is required.',
                'decimal' => 'Amount must be a valid number.'
            ],
            'expense_date' => [
                'required' => 'Expense date is required.',
                'valid_date' => 'Enter a valid date.'
            ],
            'category_id' => [
                'required' => 'Category is required.',
                'integer' => 'Category ID must be a number.'
            ]
            // NO receipt validation messages
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $receiptFileName = null;
        $userDirectory = WRITEPATH . 'uploads/receipts/' . $userId;
        
        // Handle file upload ONLY if file exists and is valid
        $receiptFile = $this->request->getFile('receipt');
        
        // Check if file was uploaded and is valid
        if ($receiptFile && $receiptFile->isValid() && !$receiptFile->hasMoved()) {
            // Validate file type and size only if file is provided
            $fileValidation = $this->validate([
                'receipt' => 'max_size[receipt,5120]|ext_in[receipt,jpg,jpeg,png,pdf,gif]'
            ], [
                'receipt' => [
                    'max_size' => 'Receipt file size should not exceed 5MB.',
                    'ext_in' => 'Only JPG, JPEG, PNG, PDF, and GIF files are allowed for receipts.'
                ]
            ]);

            if (!$fileValidation) {
                return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
            }

            // Create user-specific directory if it doesn't exist
            if (!is_dir($userDirectory)) {
                mkdir($userDirectory, 0755, true);
            }
            
            // Generate unique filename
            $newName = $receiptFile->getRandomName();
            
            // Move file to uploads directory
            if ($receiptFile->move($userDirectory, $newName)) {
                $receiptFileName = $newName;
            }
        }

        $data = [
            'user_id' => $userId,
            'category_id' => $this->request->getPost('category_id'),
            'title' => $this->request->getPost('title'),
            'amount' => $this->request->getPost('amount'),
            'expense_date' => $this->request->getPost('expense_date'),
            'receipt' => $receiptFileName
        ];

        if ($this->expenseModel->save($data)) {
            return redirect()->to('/expenses')->with('success', 'Expense added successfully!');
        } else {
            // Delete uploaded file if saving failed
            if ($receiptFileName && file_exists($userDirectory . '/' . $receiptFileName)) {
                @unlink($userDirectory . '/' . $receiptFileName);
            }
            return redirect()->back()->with('errors', $this->expenseModel->errors())->withInput();
        }
    }

    public function edit($id)
    {
        $userId = auth()->id();
        
        $expense = $this->expenseModel
            ->where('user_id', $userId)
            ->find($id);

        if (!$expense) {
            return redirect()->to('/expenses')->with('error', 'Expense not found.');
        }

        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('expenses/edit', [
            'expense' => $expense,
            'categories' => $categories
        ]);
    }

    public function update($id)
    {
        $userId = auth()->id();
        
        $expense = $this->expenseModel
            ->where('user_id', $userId)
            ->find($id);

        if (!$expense) {
            return redirect()->to('/expenses')->with('error', 'Expense not found.');
        }

        // Validate input - receipt is OPTIONAL
        $validationRules = [
            'title' => 'required|max_length[100]',
            'amount' => 'required|decimal',
            'expense_date' => 'required|valid_date',
            'category_id' => 'required|integer'
            // NO receipt validation rules - completely optional
        ];

        $validationMessages = [
            'title' => [
                'required' => 'Expense title is required.',
                'max_length' => 'Title cannot exceed 100 characters.'
            ],
            'amount' => [
                'required' => 'Amount is required.',
                'decimal' => 'Amount must be a valid number.'
            ],
            'expense_date' => [
                'required' => 'Expense date is required.',
                'valid_date' => 'Enter a valid date.'
            ],
            'category_id' => [
                'required' => 'Category is required.',
                'integer' => 'Category ID must be a number.'
            ]
            // NO receipt validation messages
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $receiptFileName = $expense['receipt']; // Keep existing receipt
        $userDirectory = WRITEPATH . 'uploads/receipts/' . $userId;
        
        // Handle new file upload ONLY if file exists and is valid
        $receiptFile = $this->request->getFile('receipt');
        
        if ($receiptFile && $receiptFile->isValid() && !$receiptFile->hasMoved()) {
            // Validate file type and size only if file is provided
            $fileValidation = $this->validate([
                'receipt' => 'max_size[receipt,5120]|ext_in[receipt,jpg,jpeg,png,pdf,gif]'
            ], [
                'receipt' => [
                    'max_size' => 'Receipt file size should not exceed 5MB.',
                    'ext_in' => 'Only JPG, JPEG, PNG, PDF, and GIF files are allowed for receipts.'
                ]
            ]);

            if (!$fileValidation) {
                return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
            }

            // Create user-specific directory if it doesn't exist
            if (!is_dir($userDirectory)) {
                mkdir($userDirectory, 0755, true);
            }
            
            // Generate unique filename
            $newName = $receiptFile->getRandomName();
            
            // Move file to uploads directory
            if ($receiptFile->move($userDirectory, $newName)) {
                // Delete old receipt file if exists
                if ($expense['receipt'] && file_exists($userDirectory . '/' . $expense['receipt'])) {
                    @unlink($userDirectory . '/' . $expense['receipt']);
                }
                
                $receiptFileName = $newName;
            }
        }

        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'title' => $this->request->getPost('title'),
            'amount' => $this->request->getPost('amount'),
            'expense_date' => $this->request->getPost('expense_date'),
            'receipt' => $receiptFileName
        ];

        if ($this->expenseModel->update($id, $data)) {
            return redirect()->to('/expenses')->with('success', 'Expense updated successfully!');
        } else {
            // Delete new uploaded file if update failed
            if ($receiptFileName !== $expense['receipt'] && file_exists($userDirectory . '/' . $receiptFileName)) {
                @unlink($userDirectory . '/' . $receiptFileName);
            }
            return redirect()->back()->with('errors', $this->expenseModel->errors())->withInput();
        }
    }

    public function delete($id)
    {
        $userId = auth()->id();
        
        $expense = $this->expenseModel
            ->where('user_id', $userId)
            ->find($id);
        
        if ($expense && $this->expenseModel->delete($id)) {
            // Delete receipt file if exists
            if ($expense['receipt']) {
                $receiptPath = WRITEPATH . 'uploads/receipts/' . $userId . '/' . $expense['receipt'];
                if (file_exists($receiptPath)) {
                    @unlink($receiptPath);
                }
            }
            return redirect()->to('/expenses')->with('success', 'Expense deleted successfully!');
        } else {
            return redirect()->to('/expenses')->with('error', 'Expense not found or you do not have permission to delete it.');
        }
    }

    public function view($id)
    {
        $userId = auth()->id();
        
        $expense = $this->expenseModel
            ->select('expenses.*, categories.name as category_name')
            ->join('categories', 'categories.id = expenses.category_id', 'left')
            ->where('expenses.user_id', $userId)
            ->where('expenses.id', $id)
            ->first();

        if (!$expense) {
            return redirect()->to('/expenses')->with('error', 'Expense not found.');
        }

        return view('expenses/view', ['expense' => $expense]);
    }

    private function ensureCategoriesExist($userId)
    {
        $categories = $this->categoryModel->where('user_id', $userId)->findAll();
        
        if (empty($categories)) {
            $defaultCategories = [
                'Food & Dining',
                'Transportation',
                'Shopping',
                'Entertainment',
                'Bills & Utilities',
                'Healthcare',
                'Travel',
                'Education',
                'Other'
            ];
            
            foreach ($defaultCategories as $categoryName) {
                $this->categoryModel->save([
                    'user_id' => $userId,
                    'name' => $categoryName
                ]);
            }
        }
    }

    // ==================== RECEIPT METHODS ====================
    public function downloadReceipt($id)
    {
        $userId = auth()->id();
        
        $expense = $this->expenseModel
            ->where('user_id', $userId)
            ->find($id);

        if (!$expense || !$expense['receipt']) {
            return redirect()->to('/expenses')->with('error', 'Receipt not found.');
        }

        $filePath = WRITEPATH . 'uploads/receipts/' . $userId . '/' . $expense['receipt'];
        
        if (!file_exists($filePath)) {
            return redirect()->to('/expenses')->with('error', 'Receipt file not found.');
        }

        return $this->response->download($filePath, null);
    }

    public function viewReceipt($id)
    {
        $userId = auth()->id();
        
        $expense = $this->expenseModel
            ->where('user_id', $userId)
            ->find($id);

        if (!$expense || !$expense['receipt']) {
            return redirect()->to('/expenses')->with('error', 'Receipt not found.');
        }

        $filePath = WRITEPATH . 'uploads/receipts/' . $userId . '/' . $expense['receipt'];
        
        if (!file_exists($filePath)) {
            return redirect()->to('/expenses')->with('error', 'Receipt file not found.');
        }

        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf'
        ];

        $mimeType = $mimeTypes[strtolower($fileExtension)] ?? 'application/octet-stream';

        return $this->response
            ->setContentType($mimeType)
            ->setBody(file_get_contents($filePath));
    }

    // ==================== PUBLIC FILTER HELPER METHODS ====================
    
    /**
     * Get human-readable filter labels for display
     */
    public function getFilterLabel($key, $value, $categories)
    {
        switch ($key) {
            case 'category':
                foreach ($categories as $category) {
                    if ($category['id'] == $value) {
                        return 'Category: ' . $category['name'];
                    }
                }
                return null;
            case 'start_date':
                return 'From: ' . date('M j, Y', strtotime($value));
            case 'end_date':
                return 'To: ' . date('M j, Y', strtotime($value));
           // In getFilterLabel method, update these lines:
case 'min_amount':
    return 'Min: ETB ' . number_format($value, 2);
case 'max_amount':
    return 'Max: ETB ' . number_format($value, 2);
            case 'search':
                return 'Search: "' . $value . '"';
            default:
                return null;
        }
    }

    /**
     * Generate URL to remove a specific filter while keeping others
     */
    public function removeFilterUrl($filterToRemove)
    {
        $currentParams = $this->request->getGet();
        unset($currentParams[$filterToRemove]);
        
        $queryString = http_build_query($currentParams);
        return site_url('expenses') . ($queryString ? '?' . $queryString : '');
    }
    /**
 * Apply quick date filters (Last 7 days, This month, etc.)
 */
public function quickFilter($type)
{
    $userId = auth()->id();
    $today = date('Y-m-d');
    
    switch ($type) {
        case 'last_7_days':
            $startDate = date('Y-m-d', strtotime('-7 days'));
            $params = ['start_date' => $startDate, 'end_date' => $today];
            break;
            
        case 'last_30_days':
            $startDate = date('Y-m-d', strtotime('-30 days'));
            $params = ['start_date' => $startDate, 'end_date' => $today];
            break;
            
        case 'this_month':
            $startDate = date('Y-m-01');
            $params = ['start_date' => $startDate, 'end_date' => $today];
            break;
            
        case 'last_month':
            $startDate = date('Y-m-01', strtotime('-1 month'));
            $endDate = date('Y-m-t', strtotime('-1 month'));
            $params = ['start_date' => $startDate, 'end_date' => $endDate];
            break;
            
        default:
            return redirect()->to('/expenses');
    }
    
    $queryString = http_build_query($params);
    return redirect()->to('/expenses?' . $queryString);
}

/**
 * Clear all filters
 */
public function clearFilters()
{
    return redirect()->to('/expenses');
}

}
