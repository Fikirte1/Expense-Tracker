<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Planning - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #3498db 100%);
            transition: all 0.3s ease;
            position: fixed;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: rgba(52, 152, 219, 0.3);
            border-left: 4px solid #3498db;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 12px 5px;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        /* Navbar Styles */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 1.5rem;
        }

        .navbar-brand {
            font-weight: 600;
            color: #2c3e50;
        }

        /* Toggle Button */
        .sidebar-toggle {
            background: none;
            border: none;
            color: #2c3e50;
            font-size: 1.2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .toggle-icon.rotated {
            transform: rotate(180deg);
        }

        /* Budget Specific Styles */
        .quick-add-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #dee2e6;
        }
        
        .suggestion-card {
            background: rgba(40, 167, 69, 0.05);
            border-left: 4px solid #28a745;
        }
        
        .alert-card {
            border-left: 4px solid #dc3545;
        }
        
        .budget-insight {
            background: rgba(23, 162, 184, 0.05);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .quick-action-btn {
            transition: all 0.3s ease;
        }
        
        .quick-action-btn:hover {
            transform: scale(1.05);
        }
        
        .budget-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-badge {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
        }

        /* Ethiopian Birr Currency Style */
        .currency-birr {
            font-family: Arial, sans-serif;
            font-weight: 600;
        }

        .input-group-text-birr {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
            font-weight: 600;
        }

        /* Budget Alert Styles */
        .budget-alert {
            border-left: 4px solid #dc3545;
            background: rgba(220, 53, 69, 0.05);
        }

        .budget-warning {
            border-left: 4px solid #ffc107;
            background: rgba(255, 193, 7, 0.05);
        }

        .budget-info {
            border-left: 4px solid #17a2b8;
            background: rgba(23, 162, 184, 0.05);
        }

        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Loading states */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-right-color: transparent;
            animation: spinner 0.75s linear infinite;
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.collapsed {
                margin-left: -70px;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?= $this->include('layouts/sidebar') ?>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Include Navbar -->
        <?= $this->include('layouts/navbar') ?>

        <!-- Page Content -->
        <div class="container-fluid py-4">
            <!-- Display Session Messages -->
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show animate-fade-in">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Header -->
            <div class="page-header animate-fade-in">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h2 fw-bold text-dark mb-2">
                            <i class="fas fa-chart-pie me-3 text-info"></i>Budget Planning
                        </h1>
                        <p class="text-muted mb-0 fs-5">Plan and track your monthly budgets effectively</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
                                <i class="fas fa-plus-circle me-2"></i>Add Budget
                            </button>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#duplicateBudgetModal">
                                <i class="fas fa-copy me-2"></i>Duplicate
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Alerts Section -->
            <div class="row mb-4 animate-fade-in">
                <div class="col-12">
                    <!-- Overspending Alert -->
                    <?php if (($total_remaining ?? 0) < 0): ?>
                    <div class="alert budget-alert alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x me-3 text-danger"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Budget Overspent!</h5>
                                <p class="mb-0">You have exceeded your budget by <strong class="currency-birr">Br <?= number_format(abs($total_remaining ?? 0), 2) ?></strong>. Consider adjusting your spending.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- High Utilization Alert -->
                    <?php if (($total_budget ?? 0) > 0 && (($total_spent ?? 0) / ($total_budget ?? 1)) > 0.8): ?>
                    <div class="alert budget-warning alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-2x me-3 text-warning"></i>
                            <div>
                                <h5 class="alert-heading mb-1">High Budget Utilization</h5>
                                <p class="mb-0">You've used <?= number_format((($total_spent ?? 0) / ($total_budget ?? 1)) * 100, 1) ?>% of your budget. Only <strong class="currency-birr">Br <?= number_format(($total_budget ?? 0) - ($total_spent ?? 0), 2) ?></strong> remaining.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Low Savings Alert -->
                    <?php if (($total_income ?? 0) > 0 && ((($total_income ?? 0) - ($total_spent ?? 0)) / ($total_income ?? 1)) < 0.1): ?>
                    <div class="alert budget-info alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Low Savings Rate</h5>
                                <p class="mb-0">Your savings rate is <?= number_format(((($total_income ?? 0) - ($total_spent ?? 0)) / ($total_income ?? 1)) * 100, 1) ?>%. Consider increasing your savings for better financial health.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Individual Category Alerts -->
                    <?php if (!empty($budgets)): ?>
                        <?php foreach($budgets as $budget): ?>
                            <?php if ($budget['percentage'] > 100): ?>
                            <div class="alert budget-alert alert-dismissible fade show">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-fire fa-2x me-3 text-danger"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Over Budget: <?= esc($budget['category_name']) ?></h5>
                                        <p class="mb-0">You've exceeded your <strong><?= esc($budget['category_name']) ?></strong> budget by <strong class="currency-birr">Br <?= number_format(abs($budget['remaining']), 2) ?></strong> (<?= number_format($budget['percentage'], 1) ?>% used).</p>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php elseif ($budget['percentage'] > 80): ?>
                            <div class="alert budget-warning alert-dismissible fade show">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock fa-2x me-3 text-warning"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Approaching Limit: <?= esc($budget['category_name']) ?></h5>
                                        <p class="mb-0">Your <strong><?= esc($budget['category_name']) ?></strong> budget is <?= number_format($budget['percentage'], 1) ?>% used. Only <strong class="currency-birr">Br <?= number_format($budget['remaining'], 2) ?></strong> remaining.</p>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- No Budget Alert -->
                    <?php if (empty($budgets)): ?>
                    <div class="alert budget-info alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lightbulb fa-2x me-3 text-info"></i>
                            <div>
                                <h5 class="alert-heading mb-1">No Budgets Set</h5>
                                <p class="mb-0">You haven't set any budgets for <?= date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)) ?>. Create your first budget to start tracking your expenses effectively.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions & Stats -->
            <div class="row mb-4 animate-fade-in">
                <div class="col-lg-8">
                    <div class="budget-summary">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <h4 class="fw-bold currency-birr">Br <?= number_format($total_income ?? 0, 2) ?></h4>
                                <small class="opacity-75">Total Income</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4 class="fw-bold currency-birr">Br <?= number_format($total_budget ?? 0, 2) ?></h4>
                                <small class="opacity-75">Total Budget</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4 class="fw-bold currency-birr">Br <?= number_format($total_spent ?? 0, 2) ?></h4>
                                <small class="opacity-75">Total Spent</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4 class="fw-bold currency-birr <?= ($total_remaining ?? 0) >= 0 ? 'text-success' : 'text-warning' ?>">
                                    Br <?= number_format(abs($total_remaining ?? 0), 2) ?>
                                </h4>
                                <small class="opacity-75"><?= ($total_remaining ?? 0) >= 0 ? 'Remaining' : 'Overspent' ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h6 class="card-title">Quick Actions</h6>
                            <div class="btn-group-vertical w-100">
                                <button class="btn btn-outline-primary quick-action-btn mb-2" onclick="showQuickAdd()">
                                    <i class="fas fa-bolt me-2"></i>Quick Add
                                </button>
                                <button class="btn btn-outline-success quick-action-btn" data-bs-toggle="modal" data-bs-target="#suggestionsModal">
                                    <i class="fas fa-lightbulb me-2"></i>Get Suggestions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Add Section -->
            <div class="row mb-4 animate-fade-in" id="quickAddSection" style="display: none;">
                <div class="col-12">
                    <div class="card quick-add-card">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-bolt me-2 text-warning"></i>Quick Add Budget
                            </h6>
                            <form id="quickAddForm" class="row g-3">
                                <?= csrf_field() ?>
                                <div class="col-md-4">
                                    <select class="form-select" id="quick_category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text input-group-text-birr">Br</span>
                                        <input type="number" class="form-control" id="quick_amount" name="amount" 
                                               step="0.01" min="0.01" required placeholder="Amount">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="month" class="form-control" id="quick_month_year" name="month_year" 
                                           value="<?= date('Y-m') ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success w-100" id="quickAddBtn">
                                        <i class="fas fa-check me-1"></i>Add
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Insights -->
            <div class="row mb-4 animate-fade-in">
                <div class="col-12">
                    <div class="budget-insight">
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted">Budget Utilization</small>
                                <h5 class="mb-0"><?= ($total_budget ?? 0) > 0 ? number_format((($total_spent ?? 0) / ($total_budget ?? 1)) * 100, 1) : 0 ?>%</h5>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Savings Rate</small>
                                <h5 class="mb-0"><?= ($total_income ?? 0) > 0 ? number_format(((($total_income ?? 0) - ($total_spent ?? 0)) / ($total_income ?? 1)) * 100, 1) : 0 ?>%</h5>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Categories Budgeted</small>
                                <h5 class="mb-0"><?= count($budgets ?? []) ?></h5>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Over Budget</small>
                                <h5 class="mb-0 text-danger">
                                    <?= count(array_filter($budgets ?? [], function($b) { return ($b['status'] ?? '') == 'over-budget'; })) ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4 animate-fade-in">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2 text-primary"></i>
                                Budget vs Actual Spending
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="budgetChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2 text-success"></i>
                                Budget Distribution
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="distributionChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget List -->
            <div class="row animate-fade-in">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2 text-info"></i>
                                Monthly Budgets - <?= date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)) ?>
                            </h5>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="month" style="width: auto;">
                                    <?php foreach($months as $key => $monthName): ?>
                                        <option value="<?= $key ?>" <?= $key == $current_month ? 'selected' : '' ?>>
                                            <?= $monthName ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-select form-select-sm" id="year" style="width: auto;">
                                    <?php foreach($years as $yearValue): ?>
                                        <option value="<?= $yearValue ?>" <?= $yearValue == $current_year ? 'selected' : '' ?>>
                                            <?= $yearValue ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($budgets)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-pie fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">No budgets found for <?= date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)) ?></h5>
                                    <p class="text-muted">Start by adding your first budget for this month</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
                                        <i class="fas fa-plus-circle me-2"></i>Add Your First Budget
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Budget Amount</th>
                                                <th>Spent</th>
                                                <th>Remaining</th>
                                                <th>Utilization</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($budgets as $budget): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="category-color me-2" 
                                                             style="width: 12px; height: 12px; background-color: <?= $budget['category_color'] ?? '#007bff' ?>; border-radius: 50%;"></div>
                                                        <span><?= esc($budget['category_name']) ?></span>
                                                        <?php if (!empty($budget['description'])): ?>
                                                            <i class="fas fa-info-circle ms-2 text-muted" title="<?= esc($budget['description']) ?>"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="currency-birr">Br <?= number_format($budget['amount'], 2) ?></td>
                                                <td class="currency-birr">Br <?= number_format($budget['spent'], 2) ?></td>
                                                <td class="currency-birr <?= $budget['remaining'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                    Br <?= number_format(abs($budget['remaining']), 2) ?>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar <?= $budget['percentage'] > 100 ? 'bg-danger' : ($budget['percentage'] > 80 ? 'bg-warning' : 'bg-success') ?>" 
                                                             role="progressbar" 
                                                             style="width: <?= min($budget['percentage'], 100) ?>%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted"><?= number_format($budget['percentage'], 1) ?>%</small>
                                                </td>
                                                <td>
                                                    <span class="badge stat-badge <?= $budget['status'] == 'over-budget' ? 'bg-danger' : ($budget['status'] == 'within-budget' ? 'bg-warning' : 'bg-success') ?>">
                                                        <?= ucfirst(str_replace('-', ' ', $budget['status'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-budget"
                                                            data-id="<?= $budget['id'] ?>"
                                                            data-amount="<?= $budget['amount'] ?>"
                                                            data-description="<?= esc($budget['description'] ?? '') ?>"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editBudgetModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-budget"
                                                            data-id="<?= $budget['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals Section -->
    <?= $this->include('budget/modals') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global functions
        function showQuickAdd() {
            const section = document.getElementById('quickAddSection');
            if (section) {
                section.style.display = section.style.display === 'none' ? 'block' : 'none';
            }
        }

        function hideQuickAdd() {
            const section = document.getElementById('quickAddSection');
            if (section) {
                section.style.display = 'none';
            }
        }

        function setLoading(button, isLoading) {
            if (isLoading) {
                button.disabled = true;
                button.classList.add('btn-loading');
            } else {
                button.disabled = false;
                button.classList.remove('btn-loading');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeSidebar();
            initializeCharts();
            initializeModals();
            initializeForms();
            initializeEventListeners();
        });

        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (!sidebar || !mainContent || !toggleBtn) return;

            const toggleIcon = toggleBtn.querySelector('.toggle-icon');
            let isCollapsed = false;

            // Check if state is saved in localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                collapseSidebar();
            }

            function collapseSidebar() {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                if (toggleIcon) toggleIcon.classList.add('rotated');
                isCollapsed = true;
                localStorage.setItem('sidebarCollapsed', 'true');
            }

            function expandSidebar() {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                if (toggleIcon) toggleIcon.classList.remove('rotated');
                isCollapsed = false;
                localStorage.setItem('sidebarCollapsed', 'false');
            }

            function toggleSidebar() {
                if (isCollapsed) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            }

            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSidebar();
            });

            // Mobile responsive behavior
            function handleResize() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                } else {
                    if (isCollapsed) {
                        collapseSidebar();
                    } else {
                        expandSidebar();
                    }
                }
            }

            handleResize();
            window.addEventListener('resize', handleResize);
        }

        function initializeCharts() {
            // Budget Chart
            const budgetCtx = document.getElementById('budgetChart');
            if (budgetCtx && <?= !empty($budgets) ? 'true' : 'false' ?>) {
                try {
                    const budgetChart = new Chart(budgetCtx, {
                        type: 'bar',
                        data: {
                            labels: [<?= !empty($budgets) ? implode(',', array_map(function($item) { return "'" . addslashes($item['category_name']) . "'"; }, $budgets)) : '' ?>],
                            datasets: [
                                {
                                    label: 'Budgeted',
                                    data: [<?= !empty($budgets) ? implode(',', array_map(function($item) { return $item['amount']; }, $budgets)) : '' ?>],
                                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Actual Spending',
                                    data: [<?= !empty($budgets) ? implode(',', array_map(function($item) { return $item['spent']; }, $budgets)) : '' ?>],
                                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Br ' + value.toLocaleString();
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += 'Br ' + context.parsed.y.toLocaleString();
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error initializing budget chart:', error);
                }
            }

            // Distribution Chart
            const distCtx = document.getElementById('distributionChart');
            if (distCtx && <?= !empty($budgets) ? 'true' : 'false' ?>) {
                try {
                    const distChart = new Chart(distCtx, {
                        type: 'doughnut',
                        data: {
                            labels: [<?= !empty($budgets) ? implode(',', array_map(function($item) { return "'" . addslashes($item['category_name']) . "'"; }, $budgets)) : '' ?>],
                            datasets: [{
                                data: [<?= !empty($budgets) ? implode(',', array_map(function($item) { return $item['amount']; }, $budgets)) : '' ?>],
                                backgroundColor: [
                                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: Br ${value.toLocaleString()} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error initializing distribution chart:', error);
                }
            }
        }

        function initializeModals() {
            // Edit Budget Modal
            const editButtons = document.querySelectorAll('.edit-budget');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const amount = this.getAttribute('data-amount');
                    const description = this.getAttribute('data-description');
                    
                    const editAmount = document.getElementById('edit_amount');
                    const editDescription = document.getElementById('edit_description');
                    const editForm = document.getElementById('editBudgetForm');
                    
                    if (editAmount && editDescription && editForm) {
                        editAmount.value = amount;
                        editDescription.value = description || '';
                        editForm.action = '<?= site_url('budget/update/') ?>' + id;
                    }
                });
            });

            // Use Suggestion buttons
            const useSuggestionButtons = document.querySelectorAll('.use-suggestion');
            useSuggestionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-category-id');
                    const amount = this.getAttribute('data-amount');
                    
                    const categorySelect = document.getElementById('category_id');
                    const amountInput = document.getElementById('amount');
                    
                    if (categorySelect && amountInput) {
                        categorySelect.value = categoryId;
                        amountInput.value = amount;
                        
                        // Close suggestions modal
                        const suggestionsModal = bootstrap.Modal.getInstance(document.getElementById('suggestionsModal'));
                        if (suggestionsModal) {
                            suggestionsModal.hide();
                        }
                        
                        // Open add budget modal
                        const addModalElement = document.getElementById('addBudgetModal');
                        if (addModalElement) {
                            const addModal = new bootstrap.Modal(addModalElement);
                            addModal.show();
                        }
                    }
                });
            });

            // Delete Budget buttons
            const deleteButtons = document.querySelectorAll('.delete-budget');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const budgetRow = this.closest('tr');
                    const categoryName = budgetRow.querySelector('td:first-child').textContent.trim();
                    
                    if (confirm(`Are you sure you want to delete the budget for ${categoryName}?`)) {
                        const deleteBtn = this;
                        setLoading(deleteBtn, true);
                        
                        fetch(`<?= site_url('budget/delete/') ?>${id}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="csrf_test_name"]').value
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showAlert('success', data.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                showAlert('danger', data.message || 'Failed to delete budget.');
                                setLoading(deleteBtn, false);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('danger', 'An error occurred while deleting the budget.');
                            setLoading(deleteBtn, false);
                        });
                    }
                });
            });
        }

        function initializeForms() {
            // Add Budget Form
            const addBudgetForm = document.getElementById('addBudgetForm');
            if (addBudgetForm) {
                addBudgetForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = document.getElementById('addBudgetBtn');
                    setLoading(submitBtn, true);
                    
                    const formData = new FormData(this);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            return response.text();
                        }
                    })
                    .then(data => {
                        setLoading(submitBtn, false);
                        if (data && typeof data === 'string' && data.includes('error')) {
                            showAlert('danger', 'Failed to add budget. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', 'An error occurred while adding the budget.');
                        setLoading(submitBtn, false);
                    });
                });
            }

            // Edit Budget Form
            const editBudgetForm = document.getElementById('editBudgetForm');
            if (editBudgetForm) {
                editBudgetForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = document.getElementById('editBudgetBtn');
                    setLoading(submitBtn, true);
                    
                    const formData = new FormData(this);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            return response.text();
                        }
                    })
                    .then(data => {
                        setLoading(submitBtn, false);
                        if (data && typeof data === 'string' && data.includes('error')) {
                            showAlert('danger', 'Failed to update budget. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', 'An error occurred while updating the budget.');
                        setLoading(submitBtn, false);
                    });
                });
            }

            // Quick Add Form
            const quickAddForm = document.getElementById('quickAddForm');
            if (quickAddForm) {
                quickAddForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = document.getElementById('quickAddBtn');
                    setLoading(submitBtn, true);
                    
                    const formData = new FormData(this);
                    
                    fetch('<?= site_url('budget/quick-add') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            quickAddForm.reset();
                            hideQuickAdd();
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showAlert('danger', data.message || 'Failed to add budget.');
                        }
                        setLoading(submitBtn, false);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', 'An error occurred while adding the budget.');
                        setLoading(submitBtn, false);
                    });
                });
            }

            // Duplicate Budgets Form
            const duplicateBudgetForm = document.getElementById('duplicateBudgetForm');
            if (duplicateBudgetForm) {
                duplicateBudgetForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = document.getElementById('duplicateBudgetBtn');
                    setLoading(submitBtn, true);
                    
                    const formData = new FormData(this);
                    
                    fetch('<?= site_url('budget/duplicate') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            const modal = bootstrap.Modal.getInstance(document.getElementById('duplicateBudgetModal'));
                            if (modal) {
                                modal.hide();
                            }
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            showAlert('danger', data.message || 'Failed to duplicate budgets.');
                        }
                        setLoading(submitBtn, false);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', 'An error occurred while duplicating budgets.');
                        setLoading(submitBtn, false);
                    });
                });
            }
        }

        function initializeEventListeners() {
            // Auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    try {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    } catch (error) {
                        console.log('Alert already dismissed');
                    }
                }, 8000); // Increased to 8 seconds for budget alerts
            });

            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });

            // Auto-refresh on filter change
            const monthSelect = document.getElementById('month');
            const yearSelect = document.getElementById('year');
            
            if (monthSelect) {
                monthSelect.addEventListener('change', updateBudgetView);
            }
            if (yearSelect) {
                yearSelect.addEventListener('change', updateBudgetView);
            }
        }

        function updateBudgetView() {
            const year = document.getElementById('year')?.value || '<?= date('Y') ?>';
            const month = document.getElementById('month')?.value || '<?= date('m') ?>';
            
            window.location.href = `<?= site_url('budget') ?>?year=${year}&month=${month}`;
        }

        function showAlert(type, message) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Find a safe place to insert the alert
            const container = document.querySelector('.container-fluid');
            const pageHeader = document.querySelector('.page-header');
            
            try {
                if (container && pageHeader && pageHeader.nextElementSibling) {
                    container.insertBefore(alertDiv, pageHeader.nextElementSibling);
                } else if (container) {
                    container.insertBefore(alertDiv, container.firstElementChild);
                } else {
                    document.body.insertBefore(alertDiv, document.body.firstElementChild);
                }
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        try {
                            const bsAlert = bootstrap.Alert.getInstance(alertDiv);
                            if (bsAlert) {
                                bsAlert.close();
                            } else {
                                alertDiv.remove();
                            }
                        } catch (e) {
                            alertDiv.remove();
                        }
                    }
                }, 5000);
                
                // Initialize Bootstrap alert
                try {
                    new bootstrap.Alert(alertDiv);
                } catch (error) {
                    console.log('Alert initialization skipped');
                }
                
            } catch (error) {
                console.error('Error showing alert:', error);
            }
        }
    </script>
</body>
</html>