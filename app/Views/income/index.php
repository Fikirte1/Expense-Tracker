<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Management - Expense Tracker</title>
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

        /* Income Specific Styles */
        .income-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .quick-add-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #dee2e6;
        }

        .income-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .income-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .stat-badge {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
        }

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

        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Chart Styles */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
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

        /* Income Status Colors */
        .income-recent {
            border-left: 4px solid #28a745;
        }

        .income-old {
            border-left: 4px solid #6c757d;
        }

        /* Loading Animation */
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

            <!-- Header -->
            <div class="page-header animate-fade-in">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h2 fw-bold text-dark mb-2">
                            <i class="fas fa-money-bill-wave me-3 text-success"></i>Income Management
                        </h1>
                        <p class="text-muted mb-0 fs-5">Track and manage your income sources</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addIncomeModal">
                            <i class="fas fa-plus-circle me-2"></i>Add Income
                        </button>
                    </div>
                </div>
            </div>

            <!-- Income Summary Cards -->
            <div class="row mb-4 animate-fade-in">
                <div class="col-md-4">
                    <div class="card income-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Monthly Income</h6>
                                    <h3 class="fw-bold text-success currency-birr">Br <?= number_format($monthly_income ?? 0, 2) ?></h3>
                                    <small class="text-muted">Current Month</small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-calendar-alt fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card income-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Total Income</h6>
                                    <h3 class="fw-bold text-primary currency-birr">Br <?= number_format($total_income ?? 0, 2) ?></h3>
                                    <small class="text-muted">All Time</small>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-chart-line fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card income-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Income Sources</h6>
                                    <h3 class="fw-bold text-info"><?= count($incomes ?? []) ?></h3>
                                    <small class="text-muted">Total Records</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-list fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4 animate-fade-in">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2 text-warning"></i>
                                Income Overview
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <small class="text-muted">This Week</small>
                                    <h5 class="fw-bold text-success currency-birr">Br <?= number_format($this_week_income ?? 0, 2) ?></h5>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">This Month</small>
                                    <h5 class="fw-bold text-primary currency-birr">Br <?= number_format($monthly_income ?? 0, 2) ?></h5>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Last Month</small>
                                    <h5 class="fw-bold text-info currency-birr">Br <?= number_format($last_month_income ?? 0, 2) ?></h5>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Average Monthly</small>
                                    <h5 class="fw-bold text-warning currency-birr">Br <?= number_format($average_monthly_income ?? 0, 2) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income List -->
            <div class="row animate-fade-in">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2 text-info"></i>
                                Income Records
                            </h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="exportIncomeData()">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($incomes)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">No income records found</h5>
                                    <p class="text-muted">Start by adding your first income record</p>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addIncomeModal">
                                        <i class="fas fa-plus-circle me-2"></i>Add Your First Income
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($incomes as $income): ?>
                                            <?php 
                                                $isRecent = strtotime($income['income_date']) >= strtotime('-30 days');
                                                $cardClass = $isRecent ? 'income-recent' : 'income-old';
                                            ?>
                                            <tr class="<?= $cardClass ?>">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                        <strong><?= esc($income['title']) ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?= esc($income['category_name'] ?? 'Uncategorized') ?>
                                                    </span>
                                                </td>
                                                <td class="currency-birr">
                                                    <strong class="text-success">Br <?= number_format($income['amount'], 2) ?></strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?= date('M j, Y', strtotime($income['income_date'])) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php if (!empty($income['description'])): ?>
                                                        <small class="text-muted" title="<?= esc($income['description']) ?>">
                                                            <?= strlen($income['description']) > 50 ? substr($income['description'], 0, 50) . '...' : $income['description'] ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary edit-income"
                                                                data-id="<?= $income['id'] ?>"
                                                                data-title="<?= esc($income['title']) ?>"
                                                                data-category-id="<?= $income['category_id'] ?>"
                                                                data-amount="<?= $income['amount'] ?>"
                                                                data-income-date="<?= $income['income_date'] ?>"
                                                                data-description="<?= esc($income['description'] ?? '') ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editIncomeModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger delete-income"
                                                                data-id="<?= $income['id'] ?>"
                                                                data-title="<?= esc($income['title']) ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Total Summary -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="alert alert-light">
                                            <strong>Total Records:</strong> <?= count($incomes) ?> income entries
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="alert alert-success">
                                            <strong>Grand Total:</strong> 
                                            <span class="currency-birr">Br <?= number_format(array_sum(array_column($incomes, 'amount')), 2) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Income Modal -->
    <div class="modal fade" id="addIncomeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2 text-success"></i>Add New Income
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addIncomeForm" action="<?= site_url('income/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Income Title</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   required placeholder="e.g., Salary, Freelance, Bonus">
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-birr">Br</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       step="0.01" min="0.01" required placeholder="Enter amount">
                            </div>
                        </div>
                        <div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select class="form-select" id="category_id" name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach($categories as $category): ?>
            <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
                        <div class="mb-3">
                            <label for="income_date" class="form-label">Income Date</label>
                            <input type="date" class="form-control" id="income_date" name="income_date" 
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="3" placeholder="Add any notes about this income"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="addIncomeBtn">
                            <i class="fas fa-plus-circle me-2"></i>Add Income
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Income Modal -->
    <div class="modal fade" id="editIncomeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2 text-primary"></i>Edit Income
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editIncomeForm" action="" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Income Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-birr">Br</span>
                                <input type="number" class="form-control" id="edit_amount" name="amount" 
                                       step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select class="form-select" id="category_id" name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach($categories as $category): ?>
            <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
                        <div class="mb-3">
                            <label for="edit_income_date" class="form-label">Income Date</label>
                            <input type="date" class="form-control" id="edit_income_date" name="income_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="editIncomeBtn">
                            <i class="fas fa-save me-2"></i>Update Income
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global functions
        function setLoading(button, isLoading) {
            if (isLoading) {
                button.disabled = true;
                button.classList.add('btn-loading');
            } else {
                button.disabled = false;
                button.classList.remove('btn-loading');
            }
        }

        function exportIncomeData() {
            // Simple CSV export functionality
            const incomes = <?= json_encode($incomes) ?>;
            let csv = 'Title,Category,Amount,Date,Description\n';
            
            incomes.forEach(income => {
                csv += `"${income.title}","${income.category_name}",${income.amount},"${income.income_date}","${income.description}"\n`;
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'income_data.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeSidebar();
            initializeModals();
            initializeEventListeners();
        });

        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (!sidebar || !mainContent || !toggleBtn) return;

            const toggleIcon = toggleBtn.querySelector('.toggle-icon');
            let isCollapsed = false;

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
        }

        function initializeModals() {
            // Edit Income Modal
            const editButtons = document.querySelectorAll('.edit-income');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    const categoryId = this.getAttribute('data-category-id');
                    const amount = this.getAttribute('data-amount');
                    const incomeDate = this.getAttribute('data-income-date');
                    const description = this.getAttribute('data-description');
                    
                    const editForm = document.getElementById('editIncomeForm');
                    const editTitle = document.getElementById('edit_title');
                    const editCategory = document.getElementById('edit_category_id');
                    const editAmount = document.getElementById('edit_amount');
                    const editIncomeDate = document.getElementById('edit_income_date');
                    const editDescription = document.getElementById('edit_description');
                    
                    if (editForm && editTitle && editCategory && editAmount && editIncomeDate && editDescription) {
                        editTitle.value = title;
                        editCategory.value = categoryId;
                        editAmount.value = amount;
                        editIncomeDate.value = incomeDate;
                        editDescription.value = description || '';
                        editForm.action = '<?= site_url('income/update/') ?>' + id;
                    }
                });
            });

            // Delete Income buttons
            const deleteButtons = document.querySelectorAll('.delete-income');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    
                    if (confirm(`Are you sure you want to delete the income record: "${title}"?`)) {
                        window.location.href = `<?= site_url('income/delete/') ?>${id}`;
                    }
                });
            });
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
                }, 5000);
            });

            // Add hover effects to cards
            const cards = document.querySelectorAll('.income-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.transition = 'transform 0.3s ease';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        }
    </script>
</body>
</html>