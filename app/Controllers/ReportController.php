<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\IncomeModel;
use App\Models\ExpenseModel;
use App\Models\CategoryModel;
use App\Models\BudgetModel;

class ReportController extends BaseController
{
    protected $incomeModel;
    protected $expenseModel;
    protected $categoryModel;
    protected $budgetModel;

    public function __construct()
    {
        $this->incomeModel = new IncomeModel();
        $this->expenseModel = new ExpenseModel();
        $this->categoryModel = new CategoryModel();
        $this->budgetModel = new BudgetModel();
        helper(['form', 'url', 'number']);
    }

    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to access reports.');
        }

        $userId = auth()->id();
        
        // Get date range from request or default to current month
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');
        $reportType = $this->request->getGet('report_type') ?: 'monthly';

        // Get financial data
        $reportData = $this->generateReportData($userId, $startDate, $endDate, $reportType);

        return view('reports/index', [
            'report_data' => $reportData,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'report_type' => $reportType
        ]);
    }

    public function export()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();
        $format = $this->request->getGet('format') ?: 'pdf';
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');

        $reportData = $this->generateReportData($userId, $startDate, $endDate);

        if ($format === 'csv') {
            return $this->exportCSV($reportData, $startDate, $endDate);
        } else {
            return $this->exportPDF($reportData, $startDate, $endDate);
        }
    }

    private function generateReportData($userId, $startDate, $endDate, $reportType = 'monthly')
    {
        $data = [];

        // Total Income
        $data['total_income'] = $this->incomeModel
            ->selectSum('amount')
            ->where('user_id', $userId)
            ->where('income_date >=', $startDate)
            ->where('income_date <=', $endDate)
            ->get()
            ->getRow()->amount ?? 0;

        // Total Expenses
        $data['total_expenses'] = $this->expenseModel
            ->selectSum('amount')
            ->where('user_id', $userId)
            ->where('expense_date >=', $startDate)
            ->where('expense_date <=', $endDate)
            ->get()
            ->getRow()->amount ?? 0;

        // Net Savings
        $data['net_savings'] = $data['total_income'] - $data['total_expenses'];

        // Income by Category
        $data['income_by_category'] = $this->incomeModel
            ->select('categories.name as category_name, SUM(income.amount) as total_amount')
            ->join('categories', 'categories.id = income.category_id')
            ->where('income.user_id', $userId)
            ->where('income.income_date >=', $startDate)
            ->where('income.income_date <=', $endDate)
            ->groupBy('categories.name')
            ->orderBy('total_amount', 'DESC')
            ->findAll();

        // Expenses by Category
        $data['expenses_by_category'] = $this->expenseModel
            ->select('categories.name as category_name, SUM(expenses.amount) as total_amount')
            ->join('categories', 'categories.id = expenses.category_id')
            ->where('expenses.user_id', $userId)
            ->where('expenses.expense_date >=', $startDate)
            ->where('expenses.expense_date <=', $endDate)
            ->groupBy('categories.name')
            ->orderBy('total_amount', 'DESC')
            ->findAll();

        // Recent Transactions
        $data['recent_income'] = $this->incomeModel
            ->select('income.*, categories.name as category_name')
            ->join('categories', 'categories.id = income.category_id')
            ->where('income.user_id', $userId)
            ->where('income.income_date >=', $startDate)
            ->where('income.income_date <=', $endDate)
            ->orderBy('income.income_date', 'DESC')
            ->limit(10)
            ->findAll();

        $data['recent_expenses'] = $this->expenseModel
            ->select('expenses.*, categories.name as category_name')
            ->join('categories', 'categories.id = expenses.category_id')
            ->where('expenses.user_id', $userId)
            ->where('expenses.expense_date >=', $startDate)
            ->where('expenses.expense_date <=', $endDate)
            ->orderBy('expenses.expense_date', 'DESC')
            ->limit(10)
            ->findAll();

        // Budget Analysis - FIXED: Initialize with empty array if no budgets
        try {
            $data['budget_analysis'] = $this->budgetModel
                ->select('budgets.*, categories.name as category_name,
                         COALESCE(SUM(expenses.amount), 0) as spent_amount,
                         budgets.amount - COALESCE(SUM(expenses.amount), 0) as remaining_amount')
                ->join('categories', 'categories.id = budgets.category_id', 'left')
                ->join('expenses', 'expenses.category_id = budgets.category_id AND expenses.expense_date BETWEEN budgets.start_date AND budgets.end_date', 'left')
                ->where('budgets.user_id', $userId)
                ->where('budgets.start_date <=', $endDate)
                ->where('budgets.end_date >=', $startDate)
                ->groupBy('budgets.id, categories.name, budgets.amount')
                ->findAll();
        } catch (\Exception $e) {
            // If budget model doesn't exist or there's an error, set empty array
            $data['budget_analysis'] = [];
        }

        // Monthly Trends
        $data['monthly_trends'] = $this->getMonthlyTrends($userId);

        return $data;
    }

    private function getMonthlyTrends($userId)
    {
        $monthly_trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month_start = date('Y-m-01', strtotime("-$i months"));
            $month_end = date('Y-m-t', strtotime("-$i months"));
            $month_name = date('M Y', strtotime("-$i months"));

            $month_income = $this->incomeModel
                ->selectSum('amount')
                ->where('user_id', $userId)
                ->where('income_date >=', $month_start)
                ->where('income_date <=', $month_end)
                ->get()
                ->getRow()->amount ?? 0;

            $month_expenses = $this->expenseModel
                ->selectSum('amount')
                ->where('user_id', $userId)
                ->where('expense_date >=', $month_start)
                ->where('expense_date <=', $month_end)
                ->get()
                ->getRow()->amount ?? 0;

            $monthly_trends[] = [
                'month' => $month_name,
                'income' => $month_income,
                'expenses' => $month_expenses,
                'savings' => $month_income - $month_expenses
            ];
        }

        return $monthly_trends;
    }

    private function exportCSV($reportData, $startDate, $endDate)
    {
        $filename = "financial_report_{$startDate}_to_{$endDate}.csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['Financial Report', $startDate . ' to ' . $endDate]);
        fputcsv($output, []); // Empty line
        
        // Summary
        fputcsv($output, ['SUMMARY']);
        fputcsv($output, ['Total Income', number_format($reportData['total_income'], 2)]);
        fputcsv($output, ['Total Expenses', number_format($reportData['total_expenses'], 2)]);
        fputcsv($output, ['Net Savings', number_format($reportData['net_savings'], 2)]);
        fputcsv($output, []); // Empty line
        
        // Income by Category
        fputcsv($output, ['INCOME BY CATEGORY']);
        foreach ($reportData['income_by_category'] as $item) {
            fputcsv($output, [$item['category_name'], number_format($item['total_amount'], 2)]);
        }
        fputcsv($output, []); // Empty line
        
        // Expenses by Category
        fputcsv($output, ['EXPENSES BY CATEGORY']);
        foreach ($reportData['expenses_by_category'] as $item) {
            fputcsv($output, [$item['category_name'], number_format($item['total_amount'], 2)]);
        }
        
        fclose($output);
        exit;
    }

    private function exportPDF($reportData, $startDate, $endDate)
    {
        // For PDF export, you would typically use a library like Dompdf
        // This is a simplified version - you might need to install Dompdf
        
        return redirect()->back()->with('info', 'PDF export feature coming soon!');
    }
}