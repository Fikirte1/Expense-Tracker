<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Expense Tracker</title>
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
            background: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
        }

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

        /* Category Cards */
        .category-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        /* Empty State */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }
            
            .btn {
                padding: 10px 20px;
            }
            
            .icon-wrapper {
                width: 50px;
                height: 50px;
                margin-right: 10px;
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
                            <i class="fas fa-tags me-3 text-info"></i>Category Management
                        </h1>
                        <p class="text-muted mb-0 fs-5">Organize and manage your expense categories</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group shadow">
                            <a href="<?= site_url('expenses') ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Expenses
                            </a>
                            <a href="<?= site_url('categories/create') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>New Category
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

            <!-- Categories Content -->
            <div class="animate-fade-in">
                <?php if (empty($categories)): ?>
                    <!-- Empty State -->
                    <div class="card glass-card empty-state">
                        <div class="card-body">
                            <i class="fas fa-tags"></i>
                            <h3 class="text-muted mb-3">No Categories Found</h3>
                            <p class="text-muted mb-4">Get started by creating your first category to organize your expenses.</p>
                            <a href="<?= site_url('categories/create') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Create Your First Category
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Categories List -->
                    <div class="card glass-card">
                        <div class="card-header bg-transparent border-0 py-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-0 text-dark fw-bold">
                                        <i class="fas fa-list me-2 text-info"></i>
                                        Your Categories
                                        <span class="badge bg-info ms-2"><?= count($categories) ?> total</span>
                                    </h5>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="input-group" style="max-width: 300px; margin-left: auto;">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-0 bg-light" placeholder="Search categories...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4" style="width: 60%">
                                                <i class="fas fa-tag me-2"></i>Category Name
                                            </th>
                                            <th style="width: 20%">
                                                <i class="fas fa-calendar me-2"></i>Created Date
                                            </th>
                                            <th style="width: 20%">
                                                <i class="fas fa-cog me-2"></i>Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category): ?>
                                            <tr class="category-card">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-wrapper">
                                                            <i class="fas fa-tag"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-bold text-dark"><?= esc($category['name']) ?></h6>
                                                            <small class="text-muted">Used in expenses</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?= date('M j, Y', strtotime($category['created_at'])) ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= date('g:i A', strtotime($category['created_at'])) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="<?= site_url('categories/edit/' . $category['id']) ?>" 
                                                           class="btn btn-outline-primary rounded-start-3"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit Category">
                                                            <i class="fas fa-edit"></i>
                                                            <span class="d-none d-md-inline ms-1">Edit</span>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger rounded-end-3"
                                                                onclick="confirmDelete(<?= $category['id'] ?>, '<?= esc($category['name']) ?>')"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Category">
                                                            <i class="fas fa-trash"></i>
                                                            <span class="d-none d-md-inline ms-1">Delete</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-0 py-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        Showing <strong><?= count($categories) ?></strong> categories
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Categories help organize your expenses
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="icon-wrapper mx-auto mb-3" style="background: rgba(220, 53, 69, 0.15); color: #dc3545;">
                        <i class="fas fa-trash fs-4"></i>
                    </div>
                    <h5 class="text-dark mb-3">Delete Category</h5>
                    <p class="text-muted mb-3">
                        Are you sure you want to delete the category 
                        "<strong id="categoryName" class="text-dark"><?= esc($category['name'] ?? '') ?></strong>"?
                    </p>
                    <div class="alert alert-warning border-0 small">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        This action cannot be undone. All expenses in this category will become uncategorized.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <a href="#" id="deleteConfirm" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Category
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        function confirmDelete(categoryId, categoryName) {
            document.getElementById('categoryName').textContent = categoryName;
            document.getElementById('deleteConfirm').href = '<?= site_url('categories/delete/') ?>' + categoryId;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[type="text"]');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const categoryName = row.querySelector('h6').textContent.toLowerCase();
                        if (categoryName.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>