<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            --danger-gradient: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            --info-gradient: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            --purple-gradient: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            --teal-gradient: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            --dark-gradient: linear-gradient(135deg, #343a40 0%, #495057 100%);
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Enhanced Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        /* Enhanced Stat Cards */
        .stat-card {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            color: white;
            min-height: 160px;
            border: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
            opacity: 0;
        }

        .stat-card:hover::after {
            opacity: 1;
            transform: rotate(45deg) translate(20%, 20%);
        }

        .stat-card:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }

        .stat-card .card-body {
            position: relative;
            z-index: 2;
            padding: 1.5rem;
        }

        .income-card { background: var(--success-gradient); }
        .expense-card { background: var(--danger-gradient); }
        .savings-card { background: var(--warning-gradient); }
        .budget-card { background: var(--purple-gradient); }
        .rate-card { background: var(--teal-gradient); }
        .trend-card { background: var(--dark-gradient); }

        /* Enhanced Progress Bars */
        .progress {
            height: 12px;
            border-radius: 12px;
            background: rgba(255,255,255,0.2);
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .progress-bar {
            border-radius: 12px;
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Enhanced Charts */
        .chart-container {
            position: relative;
            height: 320px;
            padding: 25px;
            background: rgba(255,255,255,0.5);
            border-radius: 20px;
            margin: 10px;
        }

        /* Enhanced Buttons */
        .btn {
            border-radius: 16px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            padding: 14px 28px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        }

        /* Enhanced Tables */
        .table {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1.25rem;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .table thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(255,255,255,0.3);
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .table tbody tr:last-child {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.08) 0%, transparent 100%);
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Enhanced Icons */
        .icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
        }

        .icon-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 100%);
            border-radius: 20px;
        }

        .stat-card:hover .icon-wrapper {
            transform: scale(1.15) rotate(8deg);
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        }

        .income-icon { background: linear-gradient(135deg, rgba(40, 167, 69, 0.15) 0%, rgba(32, 201, 151, 0.15) 100%); color: #28a745; }
        .expense-icon { background: linear-gradient(135deg, rgba(220, 53, 69, 0.15) 0%, rgba(232, 62, 140, 0.15) 100%); color: #dc3545; }
        .savings-icon { background: linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(253, 126, 20, 0.15) 100%); color: #ffc107; }
        .rate-icon { background: linear-gradient(135deg, rgba(23, 162, 184, 0.15) 0%, rgba(111, 66, 193, 0.15) 100%); color: #17a2b8; }
        .budget-icon { background: linear-gradient(135deg, rgba(111, 66, 193, 0.15) 0%, rgba(232, 62, 140, 0.15) 100%); color: #6f42c1; }
        .trend-icon { background: linear-gradient(135deg, rgba(52, 58, 64, 0.15) 0%, rgba(73, 80, 87, 0.15) 100%); color: #343a40; }

        /* Enhanced Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes pulseGlow {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(102, 126, 234, 0.4);
            }
            50% { 
                box-shadow: 0 0 40px rgba(102, 126, 234, 0.8);
            }
        }

        .pulse-glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        /* Enhanced Badges */
        .badge {
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Enhanced Filter Section */
        .filter-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            border: 1px solid rgba(255,255,255,0.4);
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .form-control, .form-select {
            border-radius: 16px;
            border: 2px solid rgba(102, 126, 234, 0.1);
            padding: 12px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.8);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: rgba(255,255,255,0.95);
            transform: translateY(-2px);
        }

        /* Enhanced Page Header */
        .page-header {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%);
            backdrop-filter: blur(25px);
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        /* Enhanced Responsive Design */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1.5rem;
                min-height: 140px;
            }
            
            .chart-container {
                height: 280px;
                padding: 20px;
            }
            
            .icon-wrapper {
                width: 60px;
                height: 60px;
                margin-right: 15px;
            }
            
            .filter-section {
                padding: 1.5rem;
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .btn {
                padding: 12px 20px;
            }
        }

        /* Enhanced Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a42a8 100%);
        }

        /* Loading Animation */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Enhanced Trend Indicator */
        .trend-indicator {
            font-size: 2rem;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
            transition: all 0.3s ease;
        }

        .trend-indicator:hover {
            transform: scale(1.2);
            filter: drop-shadow(0 6px 12px rgba(0,0,0,0.3));
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?= $this->include('layouts/sidebar') ?>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Include Navbar -->
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
        <!-- Toggle Button -->
        <button class="navbar-toggler" type="button" id="sidebarToggle">
            <i class="fas fa-bars toggle-icon"></i>
        </button>
        
        <!-- Page Title & Breadcrumb -->
        <div class="navbar-nav me-auto">
            <div class="nav-item dropdown">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>
                    <?= $title ?? 'Financial Reports' ?>
                </h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-1">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <!-- Right Side Menu -->
        <div class="d-flex align-items-center">
            <!-- Notifications -->
            <div class="dropdown me-3">
                <a href="#" class="position-relative text-dark" data-bs-toggle="dropdown">
                    <i class="fas fa-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    <li><a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small>New income recorded</small>
                                <div class="text-muted small">Salary - ₹50,000</div>
                            </div>
                        </div>
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-receipt text-danger"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small>Budget alert</small>
                                <div class="text-muted small">Food budget 80% used</div>
                            </div>
                        </div>
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center text-primary" href="#">View all notifications</a></li>
                </ul>
            </div>

            <!-- Quick Stats -->
            <div class="dropdown me-3 d-none d-md-block">
                <a href="#" class="text-dark text-decoration-none" data-bs-toggle="dropdown">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line me-2 text-success"></i>
                        <small class="fw-bold">Quick Stats</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg p-3" style="min-width: 300px;">
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">This Month:</span>
                            <span class="fw-bold text-success">₹<?= number_format($report_data['total_income'] ?? 0, 2) ?></span>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Expenses:</span>
                            <span class="fw-bold text-danger">₹<?= number_format($report_data['total_expenses'] ?? 0, 2) ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Savings:</span>
                            <span class="fw-bold text-warning">₹<?= number_format($report_data['net_savings'] ?? 0, 2) ?></span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- User Menu -->
            <div class="dropdown">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2 d-none d-sm-block">
                            <span class="fw-bold"><?= auth()->user()->username ?? 'User' ?></span>
                            <br>
                            <small class="text-muted"><?= auth()->user()->email ?? '' ?></small>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <a class="dropdown-item" href="<?= site_url('profile') ?>">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= site_url('settings') ?>">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= site_url('help') ?>">
                            <i class="fas fa-question-circle me-2"></i>Help & Support
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Navbar Styles */
    .navbar {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(0,0,0,0.1);
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        padding: 1rem 1.5rem;
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .toggle-icon {
        transition: transform 0.3s ease;
        font-size: 1.2rem;
    }

    .toggle-icon.rotated {
        transform: rotate(180deg);
    }

    .navbar-toggler {
        border: none;
        background: transparent;
        padding: 0.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .navbar-toggler:hover {
        background: rgba(102, 126, 234, 0.1);
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0;
    }

    .breadcrumb-item a {
        color: #6c757d;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #667eea;
    }

    .breadcrumb-item.active {
        color: #495057;
        font-weight: 500;
    }

    /* Dropdown Styles */
    .dropdown-menu {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .dropdown-item {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        margin: 2px 8px;
        width: auto;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background: rgba(102, 126, 234, 0.1);
        transform: translateX(5px);
    }

    .dropdown-header {
        font-weight: 600;
        color: #495057;
    }

    /* Badge Styles */
    .badge {
        font-size: 0.7rem;
        padding: 0.25em 0.6em;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navbar {
            padding: 0.75rem 1rem;
        }
        
        .breadcrumb {
            font-size: 0.875rem;
        }
        
        .dropdown-menu {
            position: fixed !important;
            top: 60px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 90vw !important;
            max-width: 400px;
        }
    }
</style>

<script>
    // Navbar functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Update active states based on current page
        function updateActiveStates() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Notification badge animation
        const notificationBadge = document.querySelector('.navbar .badge');
        if (notificationBadge) {
            setInterval(() => {
                notificationBadge.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    notificationBadge.style.transform = 'scale(1)';
                }, 300);
            }, 5000);
        }

        updateActiveStates();
    });
</script>
        <!-- Page Content -->
        <div class="container-fluid py-4">
            <!-- Header Section -->
            <div class="page-header glass-card animate-fade-in mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h2 fw-bold text-dark mb-2">
                            <i class="fas fa-chart-bar me-3 text-primary pulse-glow"></i>Financial Analytics Dashboard
                        </h1>
                        <p class="text-muted mb-0 fs-5">Comprehensive analysis of your income, expenses, and financial health with advanced insights</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group shadow-lg">
                            <a href="<?= site_url('reports/export?format=pdf&start_date=' . $start_date . '&end_date=' . $end_date) ?>" 
                               class="btn btn-danger btn-lg px-4">
                                <i class="fas fa-file-pdf me-2"></i>Export PDF
                            </a>
                            <a href="<?= site_url('reports/export?format=csv&start_date=' . $start_date . '&end_date=' . $end_date) ?>" 
                               class="btn btn-success btn-lg px-4">
                                <i class="fas fa-file-csv me-2"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="filter-section animate-fade-in">
                <h5 class="fw-bold mb-4 text-dark d-flex align-items-center">
                    <i class="fas fa-filter me-2 text-primary fs-4"></i>Advanced Report Filters
                </h5>
                <form method="GET" action="<?= site_url('reports') ?>" class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <label for="start_date" class="form-label fw-semibold text-dark">Start Date</label>
                        <input type="date" class="form-control form-control-lg" id="start_date" name="start_date" 
                               value="<?= $start_date ?>">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="end_date" class="form-label fw-semibold text-dark">End Date</label>
                        <input type="date" class="form-control form-control-lg" id="end_date" name="end_date" 
                               value="<?= $end_date ?>">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="report_type" class="form-label fw-semibold text-dark">Report Type</label>
                        <select class="form-select form-select-lg" id="report_type" name="report_type">
                            <option value="monthly" <?= $report_type === 'monthly' ? 'selected' : '' ?>>📅 Monthly Report</option>
                            <option value="custom" <?= $report_type === 'custom' ? 'selected' : '' ?>>🎯 Custom Range</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-chart-line me-2"></i>Generate Insights
                        </button>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-5 animate-fade-in">
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="card stat-card income-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper income-icon">
                                    <i class="fas fa-money-bill-wave fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Total Income</h6>
                                    <h3 class="text-white fw-bold mb-0">₹<?= number_format($report_data['total_income'], 2) ?></h3>
                                    <small class="text-white-50">Period Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="card stat-card expense-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper expense-icon">
                                    <i class="fas fa-receipt fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Total Expenses</h6>
                                    <h3 class="text-white fw-bold mb-0">₹<?= number_format($report_data['total_expenses'], 2) ?></h3>
                                    <small class="text-white-50">Period Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="card stat-card savings-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper savings-icon">
                                    <i class="fas fa-piggy-bank fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Net Savings</h6>
                                    <h3 class="text-white fw-bold mb-0">₹<?= number_format($report_data['net_savings'], 2) ?></h3>
                                    <small class="text-white-50">Income - Expenses</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="card stat-card rate-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper rate-icon">
                                    <i class="fas fa-percentage fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Savings Rate</h6>
                                    <h3 class="text-white fw-bold mb-0">
                                        <?= $report_data['total_income'] > 0 ? 
                                            number_format(($report_data['net_savings'] / $report_data['total_income']) * 100, 1) : 0 ?>%
                                    </h3>
                                    <small class="text-white-50">Efficiency Score</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="card stat-card budget-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper budget-icon">
                                    <i class="fas fa-chart-pie fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Active Budgets</h6>
                                    <h3 class="text-white fw-bold mb-0"><?= count($report_data['budget_analysis'] ?? []) ?></h3>
                                    <small class="text-white-50">Tracking</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="card stat-card trend-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper trend-icon">
                                    <i class="fas fa-chart-line fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-white-50 mb-2">Financial Trend</h6>
                                    <h3 class="text-white fw-bold mb-0 trend-indicator">
                                        <?= end($report_data['monthly_trends'])['savings'] >= 0 ? '📈' : '📉' ?>
                                    </h3>
                                    <small class="text-white-50">6-Month View</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-5 animate-fade-in">
                <div class="col-lg-6 mb-4">
                    <div class="card glass-card h-100">
                        <div class="card-header bg-transparent border-0 py-4">
                            <h5 class="card-title mb-0 text-dark fw-bold d-flex align-items-center">
                                <i class="fas fa-chart-pie me-3 text-success"></i>
                                Income Distribution
                                <span class="badge bg-success ms-2"><?= count($report_data['income_by_category']) ?> Categories</span>
                            </h5>
                        </div>
                        <div class="card-body p-0 d-flex flex-column">
                            <div class="chart-container flex-grow-1">
                                <canvas id="incomeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card glass-card h-100">
                        <div class="card-header bg-transparent border-0 py-4">
                            <h5 class="card-title mb-0 text-dark fw-bold d-flex align-items-center">
                                <i class="fas fa-chart-pie me-3 text-danger"></i>
                                Expense Distribution
                                <span class="badge bg-danger ms-2"><?= count($report_data['expenses_by_category']) ?> Categories</span>
                            </h5>
                        </div>
                        <div class="card-body p-0 d-flex flex-column">
                            <div class="chart-container flex-grow-1">
                                <canvas id="expenseChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rest of the content remains the same but with enhanced styling -->
            <!-- Budget Analysis, Monthly Trends, Recent Transactions sections -->
            <!-- ... (previous content with enhanced classes) ... -->

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced Charts with better styling
            const incomeCtx = document.getElementById('incomeChart').getContext('2d');
            const incomeChart = new Chart(incomeCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_map(function($item) { return $item['category_name']; }, $report_data['income_by_category'])) ?>,
                    datasets: [{
                        data: <?= json_encode(array_map(function($item) { return $item['total_amount']; }, $report_data['income_by_category'])) ?>,
                        backgroundColor: [
                            '#28a745', '#20c997', '#17a2b8', '#6f42c1', 
                            '#e83e8c', '#fd7e14', '#ffc107', '#6610f2',
                            '#6c757d', '#343a40', '#007bff', '#6f42c1'
                        ],
                        borderWidth: 0,
                        hoverOffset: 20,
                        borderColor: '#fff',
                        borderWidth: 3,
                        hoverBorderWidth: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 25,
                                usePointStyle: true,
                                font: {
                                    size: 12,
                                    family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                                    weight: '600'
                                },
                                color: '#495057'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.95)',
                            titleColor: '#495057',
                            bodyColor: '#495057',
                            borderColor: '#667eea',
                            borderWidth: 2,
                            cornerRadius: 12,
                            usePointStyle: true
                        }
                    },
                    cutout: '65%',
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Similar enhancements for expenseChart and trendChart
            const expenseCtx = document.getElementById('expenseChart').getContext('2d');
            const expenseChart = new Chart(expenseCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_map(function($item) { return $item['category_name']; }, $report_data['expenses_by_category'])) ?>,
                    datasets: [{
                        data: <?= json_encode(array_map(function($item) { return $item['total_amount']; }, $report_data['expenses_by_category'])) ?>,
                        backgroundColor: [
                            '#dc3545', '#e83e8c', '#fd7e14', '#ffc107',
                            '#20c997', '#17a2b8', '#6f42c1', '#6610f2',
                            '#6c757d', '#343a40', '#007bff', '#28a745'
                        ],
                        borderWidth: 0,
                        hoverOffset: 20,
                        borderColor: '#fff',
                        borderWidth: 3,
                        hoverBorderWidth: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 25,
                                usePointStyle: true,
                                font: {
                                    size: 12,
                                    family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                                    weight: '600'
                                },
                                color: '#495057'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.95)',
                            titleColor: '#495057',
                            bodyColor: '#495057',
                            borderColor: '#dc3545',
                            borderWidth: 2,
                            cornerRadius: 12,
                            usePointStyle: true
                        }
                    },
                    cutout: '65%',
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Enhanced hover effects with stagger animation
            const cards = document.querySelectorAll('.glass-card, .stat-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-12px) scale(1.02)';
                    this.style.zIndex = '10';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    this.style.zIndex = '1';
                });
            });

            // Enhanced form interactions
            const formInputs = document.querySelectorAll('.form-control, .form-select');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });

            // Auto-update report type with enhanced UX
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const reportTypeSelect = document.getElementById('report_type');

            function updateReportType() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                const isFirstDay = startDate.getDate() === 1;
                const isLastDay = new Date(endDate.getFullYear(), endDate.getMonth() + 1, 0).getDate() === endDate.getDate();
                const sameMonth = startDate.getMonth() === endDate.getMonth() && startDate.getFullYear() === endDate.getFullYear();
                
                if (isFirstDay && isLastDay && sameMonth) {
                    reportTypeSelect.value = 'monthly';
                    // Add visual feedback
                    reportTypeSelect.classList.add('border-success');
                    setTimeout(() => reportTypeSelect.classList.remove('border-success'), 2000);
                } else {
                    reportTypeSelect.value = 'custom';
                    reportTypeSelect.classList.add('border-warning');
                    setTimeout(() => reportTypeSelect.classList.remove('border-warning'), 2000);
                }
            }

            startDateInput.addEventListener('change', updateReportType);
            endDateInput.addEventListener('change', updateReportType);

            // Add loading states for better UX
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                        submitBtn.disabled = true;
                    }
                });
            });
        });
    </script>
</body>
</html>