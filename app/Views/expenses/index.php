<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Expenses - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            --danger-gradient: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            --info-gradient: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        /* Glassmorphism Effects */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            color: white;
            min-height: 140px;
            border: none;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .stat-card .card-body {
            position: relative;
            z-index: 2;
        }

        .total-expenses-card { background: var(--danger-gradient); }
        .transactions-card { background: var(--info-gradient); }
        .monthly-card { background: var(--primary-gradient); }
        .categories-card { background: var(--warning-gradient); }

        /* Enhanced Tables */
        .table {
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
        }

        /* Expense Cards */
        .expense-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
            position: relative;
        }

        .expense-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .clickable-row {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .clickable-row:hover {
            background: rgba(102, 126, 234, 0.08) !important;
        }

        /* Category Colors */
        .category-color-food { border-left-color: #28a745 !important; }
        .category-color-transport { border-left-color: #17a2b8 !important; }
        .category-color-shopping { border-left-color: #ffc107 !important; }
        .category-color-entertainment { border-left-color: #e83e8c !important; }
        .category-color-bills { border-left-color: #6f42c1 !important; }
        .category-color-healthcare { border-left-color: #fd7e14 !important; }
        .category-color-travel { border-left-color: #20c997 !important; }
        .category-color-education { border-left-color: #6610f2 !important; }
        .category-color-other { border-left-color: #6c757d !important; }

        /* Buttons */
        .btn {
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            padding: 12px 24px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Icons */
        .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            transition: all 0.3s ease;
        }

        .stat-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .expenses-icon { background: rgba(220, 53, 69, 0.2); color: #dc3545; }
        .transactions-icon { background: rgba(23, 162, 184, 0.2); color: #17a2b8; }
        .monthly-icon { background: rgba(102, 126, 234, 0.2); color: #667eea; }
        .categories-icon { background: rgba(255, 193, 7, 0.2); color: #ffc107; }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Badges */
        .badge {
            border-radius: 8px;
            padding: 6px 12px;
            font-weight: 500;
        }

        .amount-badge {
            font-size: 1.1em;
            padding: 8px 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .icon-wrapper {
                width: 50px;
                height: 50px;
            }
            
            .btn {
                padding: 10px 20px;
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
            <!-- Header Section -->
            <div class="page-header glass-card animate-fade-in mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h2 fw-bold text-dark mb-2">
                            <i class="fas fa-receipt me-3 text-danger"></i>Expense Management
                        </h1>
                        <p class="text-muted mb-0 fs-5">Track and manage your expenses efficiently</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group shadow">
                            <a href="<?= site_url('categories') ?>" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-tags me-2"></i>Manage Categories
                            </a>
                            <a href="<?= site_url('expenses/create') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Add Expense
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <div class="animate-fade-in">
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show glass-card" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3 fs-4"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Success!</h6>
                                <?= session('success') ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show glass-card" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Error!</h6>
                                <?= session('error') ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-5 animate-fade-in">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card total-expenses-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper expenses-icon">
                                    <i class="fas fa-receipt fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Total Expenses</h6>
                                    <h3 class="text-white fw-bold mb-0">
                                        ETB <?= number_format($total_expenses ?? 0, 2) ?>
                                    </h3>
                                    <small class="text-white-50">All Time</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card transactions-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper transactions-icon">
                                    <i class="fas fa-list fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Total Transactions</h6>
                                    <h3 class="text-white fw-bold mb-0"><?= count($expenses) ?></h3>
                                    <small class="text-white-50">Records</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card monthly-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper monthly-icon">
                                    <i class="fas fa-calendar fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">This Month</h6>
                                    <h3 class="text-white fw-bold mb-0">
                                        ETB <?= number_format($monthly_total ?? 0, 2) ?>
                                    </h3>
                                    <small class="text-white-50">Current Period</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card categories-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper categories-icon">
                                    <i class="fas fa-tags fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Categories</h6>
                                    <h3 class="text-white fw-bold mb-0"><?= $category_count ?? 0 ?></h3>
                                    <small class="text-white-50">Active</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="row mb-4 animate-fade-in">
                <div class="col-12">
                    <div class="card glass-card">
                        <div class="card-header bg-transparent border-0 py-4">
                            <h5 class="card-title mb-0 text-dark fw-bold">
                                <i class="fas fa-filter me-2 text-primary"></i>Filter Expenses
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="<?= site_url('expenses') ?>" id="filterForm">
                                <div class="row g-4">
                                    <!-- Search -->
                                    <div class="col-lg-3 col-md-6">
                                        <label for="search" class="form-label fw-semibold">Search</label>
                                        <input type="text" 
                                               class="form-control form-control-lg" 
                                               id="search" 
                                               name="search" 
                                               value="<?= esc($filters['search'] ?? '') ?>" 
                                               placeholder="Search expenses...">
                                    </div>

                                    <!-- Category -->
                                    <div class="col-lg-3 col-md-6">
                                        <label for="category" class="form-label fw-semibold">Category</label>
                                        <select class="form-select form-select-lg" id="category" name="category">
                                            <option value="">All Categories</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>" 
                                                    <?= ($filters['category'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                                    <?= esc($category['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Date Range -->
                                    <div class="col-lg-2 col-md-6">
                                        <label for="start_date" class="form-label fw-semibold">From Date</label>
                                        <input type="date" 
                                               class="form-control form-control-lg" 
                                               id="start_date" 
                                               name="start_date" 
                                               value="<?= esc($filters['start_date'] ?? '') ?>">
                                    </div>

                                    <div class="col-lg-2 col-md-6">
                                        <label for="end_date" class="form-label fw-semibold">To Date</label>
                                        <input type="date" 
                                               class="form-control form-control-lg" 
                                               id="end_date" 
                                               name="end_date" 
                                               value="<?= esc($filters['end_date'] ?? '') ?>">
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-lg-2 col-md-6 d-flex align-items-end">
                                        <div class="d-flex gap-2 w-100">
                                            <button type="submit" class="btn btn-primary btn-lg flex-fill">
                                                <i class="fas fa-search me-2"></i>Filter
                                            </button>
                                            <a href="<?= site_url('expenses') ?>" class="btn btn-outline-secondary btn-lg">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <!-- In your expenses/index.php view -->
<?php if (!empty($active_filters)): ?>
<div class="row mb-4 animate-fade-in">
    <div class="col-12">
        <div class="card glass-card">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <small class="fw-bold text-muted me-3">Active Filters:</small>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach($active_filters as $key => $filter): ?>
                            <span class="badge bg-primary d-flex align-items-center">
                                <?= ucfirst($key) ?>: <?= $filter['display'] ?>
                                <a href="<?= $filter['remove_url'] ?>" class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <div class="ms-auto">
                        <a href="<?= site_url('expenses/clear-filters') ?>" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-times me-1"></i>Clear All
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

            <!-- Expenses List -->
            <div class="row animate-fade-in">
                <div class="col-12">
                    <div class="card glass-card">
                        <div class="card-header bg-transparent border-0 py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 text-dark fw-bold">
                                    <i class="fas fa-history me-2 text-primary"></i>Recent Expenses
                                    <span class="badge bg-primary ms-2"><?= count($expenses) ?> records</span>
                                </h5>
                                <?php if (!empty($expenses)): ?>
                                    <div class="text-muted">
                                        <i class="fas fa-sort me-1"></i>
                                        Sorted by: Date (Newest First)
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($expenses)): ?>
                                <!-- Empty State -->
                                <div class="text-center py-5">
                                    <div class="display-1 text-muted mb-3">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <h3 class="text-muted mb-3">No Expenses Found</h3>
                                    <p class="text-muted mb-4">Start tracking your expenses by adding your first transaction</p>
                                    <a href="<?= site_url('expenses/create') ?>" class="btn btn-primary btn-lg">
                                        <i class="fas fa-plus-circle me-2"></i>Add Your First Expense
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4" style="width: 40%">Title & Category</th>
                                                <th style="width: 20%">Amount</th>
                                                <th style="width: 20%">Date</th>
                                                <th style="width: 20%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($expenses as $expense): 
                                                $category_class = 'category-color-' . strtolower(str_replace([' ', '&'], ['-', ''], $expense['category_name'] ?? 'other'));
                                            ?>
                                            <tr class="expense-card <?= $category_class ?> clickable-row" onclick="window.location='<?= site_url('expenses/view/' . $expense['id']) ?>'">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="icon-wrapper" style="background: rgba(220, 53, 69, 0.15); color: #dc3545;">
                                                                <i class="fas fa-receipt"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 fw-bold text-dark"><?= esc($expense['title']) ?></h6>
                                                            <span class="badge bg-light text-dark border">
                                                                <i class="fas fa-tag me-1 text-muted"></i>
                                                                <?= esc($expense['category_name'] ?? 'Uncategorized') ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge amount-badge bg-danger bg-opacity-10 text-danger border border-danger">
                                                        ETB <?= number_format($expense['amount'], 2) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <?= date('M j, Y', strtotime($expense['expense_date'])) ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= date('g:i A', strtotime($expense['created_at'])) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" onclick="event.stopPropagation()">
                                                        <a href="<?= site_url('expenses/view/' . $expense['id']) ?>" 
                                                           class="btn btn-outline-info rounded-start-3"
                                                           data-bs-toggle="tooltip"
                                                           title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?= site_url('expenses/edit/' . $expense['id']) ?>" 
                                                           class="btn btn-outline-warning"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit Expense">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="<?= site_url('expenses/delete/' . $expense['id']) ?>" 
                                                           class="btn btn-outline-danger rounded-end-3"
                                                           data-bs-toggle="tooltip"
                                                           title="Delete Expense"
                                                           onclick="return confirm('Are you sure you want to delete this expense? This action cannot be undone.')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($expenses)): ?>
                        <div class="card-footer bg-transparent border-0 py-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        Showing <strong><?= count($expenses) ?></strong> expense<?= count($expenses) !== 1 ? 's' : '' ?>
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Click on any row to view details
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Enhanced clickable rows
            const clickableRows = document.querySelectorAll('.clickable-row');
            clickableRows.forEach(row => {
                row.addEventListener('click', function() {
                    window.location = this.querySelector('a[href*="view"]').href;
                });
            });

            // Form auto-submit on filter change
            const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Don't auto-submit search input to allow typing
                    if (this.type !== 'text') {
                        document.getElementById('filterForm').submit();
                    }
                });
            });

            // Search with debounce
            let searchTimeout;
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        document.getElementById('filterForm').submit();
                    }, 1000);
                });
            }
        });

        // Enhanced delete confirmation
        function confirmDelete(expenseId, expenseTitle) {
            return confirm(`Are you sure you want to delete "${expenseTitle}"?\nThis action cannot be undone.`);
        }
    </script>
</body>
</html>