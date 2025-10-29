<!-- app/Views/settings/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Settings' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .settings-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .settings-card {
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            border: none;
            background: white;
            margin-bottom: 25px;
        }

        .settings-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            border-radius: 15px;
        }

        .nav-pills .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 3px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-pills .nav-link:hover:not(.active) {
            background: #f8f9fa;
            color: #667eea;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .setting-item {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .setting-item:hover {
            background: #f8f9fa;
        }

        .setting-item:last-child {
            border-bottom: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #667eea;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .main-content {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?= $this->include('layouts/sidebar') ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar with Toggle Button -->
        <nav class="navbar navbar-expand-lg bg-white shadow-sm">
            <div class="container-fluid">
                <!-- Sidebar Toggle Button -->
                <button class="sidebar-toggle me-3" id="sidebarToggle">
                    <i class="fas fa-bars toggle-icon"></i>
                </button>
                
                <div class="navbar-brand">
                    <i class="fas fa-cog me-2 text-primary"></i>
                    <span>Settings</span>
                </div>
                
                <div class="d-flex align-items-center ms-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <?= auth()->user()->username ?? 'User' ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile">
                                <i class="fas fa-user me-2"></i>Profile
                            </a></li>
                            <li><a class="dropdown-item" href="/settings">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Settings Content -->
        <div class="container-fluid">
            <div class="settings-container">
                <!-- Header -->
                <div class="settings-header">
                    <h1 class="display-4 fw-bold">
                        <i class="fas fa-cog me-3"></i>Settings
                    </h1>
                    <p class="lead">Manage your account preferences and application settings</p>
                </div>

                <!-- Notifications -->
                <?php if (session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Settings Navigation -->
                    <div class="col-md-3 mb-4">
                        <div class="settings-card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#account" data-bs-toggle="tab">
                                            <i class="fas fa-user-cog me-2"></i>Account Settings
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#security" data-bs-toggle="tab">
                                            <i class="fas fa-shield-alt me-2"></i>Security
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#notifications" data-bs-toggle="tab">
                                            <i class="fas fa-bell me-2"></i>Notifications
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#privacy" data-bs-toggle="tab">
                                            <i class="fas fa-lock me-2"></i>Privacy
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#appearance" data-bs-toggle="tab">
                                            <i class="fas fa-palette me-2"></i>Appearance
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Content -->
                    <div class="col-md-9">
                        <div class="tab-content">
                            <!-- Account Settings Tab -->
                            <div class="tab-pane fade show active" id="account">
                                <div class="settings-card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-user-cog me-2"></i>Account Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="/settings/update" method="post">
                                            <?= csrf_field() ?>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Language</label>
                                                    <select class="form-select" name="language">
                                                        <option value="en">English</option>
                                                        <option value="es">Spanish</option>
                                                        <option value="fr">French</option>
                                                        <option value="de">German</option>
                                                        <option value="it">Italian</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Timezone</label>
                                                    <select class="form-select" name="timezone">
                                                        <option value="UTC">UTC</option>
                                                        <option value="EST">Eastern Time (EST)</option>
                                                        <option value="PST">Pacific Time (PST)</option>
                                                        <option value="CST">Central Time (CST)</option>
                                                        <option value="MST">Mountain Time (MST)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Date Format</label>
                                                    <select class="form-select" name="date_format">
                                                        <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                                        <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                                                        <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Currency</label>
                                                    <select class="form-select" name="currency">
                                                        <option value="USD">USD ($)</option>
                                                        <option value="EUR">EUR (€)</option>
                                                        <option value="GBP">GBP (£)</option>
                                                        <option value="INR">INR (₹)</option>
                                                        <option value="JPY">JPY (¥)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="emailNotifications" name="email_notifications" checked>
                                                    <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Save Account Settings
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Tab -->
                            <div class="tab-pane fade" id="security">
                                <div class="settings-card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-shield-alt me-2"></i>Security Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="/settings/security" method="post">
                                            <?= csrf_field() ?>
                                            
                                            <div class="setting-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Two-Factor Authentication</h6>
                                                        <p class="text-muted mb-0">Add an extra layer of security to your account</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="twoFactor" name="two_factor">
                                                        <label class="form-check-label" for="twoFactor"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="setting-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Login Notifications</h6>
                                                        <p class="text-muted mb-0">Get notified when someone logs into your account</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="loginNotifications" name="login_notifications" checked>
                                                        <label class="form-check-label" for="loginNotifications"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="setting-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Session Timeout</h6>
                                                        <p class="text-muted mb-0">Automatically log out after 30 minutes of inactivity</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="sessionTimeout" name="session_timeout" checked>
                                                        <label class="form-check-label" for="sessionTimeout"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <h6 class="mb-3">Change Password</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Current Password</label>
                                                        <input type="password" class="form-control" name="current_password">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" class="form-control" name="new_password">
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-lock me-2"></i>Update Security Settings
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Notifications Tab -->
                            <div class="tab-pane fade" id="notifications">
                                <div class="settings-card">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0">
                                            <i class="fas fa-bell me-2"></i>Notification Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="/settings/notifications" method="post">
                                            <?= csrf_field() ?>
                                            
                                            <div class="setting-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Push Notifications</h6>
                                                        <p class="text-muted mb-0">Receive push notifications in your browser</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="pushNotifications" name="push_notifications" checked>
                                                        <label class="form-check-label" for="pushNotifications"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="setting-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Email Notifications</h6>
                                                        <p class="text-muted mb-0">Receive notifications via email</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="emailAlerts" name="email_alerts" checked>
                                                        <label class="form-check-label" for="emailAlerts"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="setting-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">SMS Notifications</h6>
                                                        <p class="text-muted mb-0">Receive notifications via SMS</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="smsNotifications" name="sms_notifications">
                                                        <label class="form-check-label" for="smsNotifications"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="setting-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Expense Alerts</h6>
                                                        <p class="text-muted mb-0">Get notified about large expenses</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="expenseAlerts" name="expense_alerts" checked>
                                                        <label class="form-check-label" for="expenseAlerts"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="setting-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Budget Warnings</h6>
                                                        <p class="text-muted mb-0">Receive warnings when approaching budget limits</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="budgetWarnings" name="budget_warnings" checked>
                                                        <label class="form-check-label" for="budgetWarnings"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-bell me-2"></i>Update Notification Settings
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy Tab -->
                            <div class="tab-pane fade" id="privacy">
                                <div class="settings-card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-lock me-2"></i>Privacy Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="/settings/update" method="post">
                                            <?= csrf_field() ?>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Profile Visibility</label>
                                                <select class="form-select" name="profile_visibility">
                                                    <option value="public">Public</option>
                                                    <option value="private">Private</option>
                                                    <option value="friends">Friends Only</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Data Sharing</label>
                                                <select class="form-select" name="data_sharing">
                                                    <option value="none">No Sharing</option>
                                                    <option value="anonymous">Anonymous Data Only</option>
                                                    <option value="full">Full Data Sharing</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="activityStatus" name="activity_status" checked>
                                                <label class="form-check-label" for="activityStatus">Show Activity Status</label>
                                            </div>

                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="dataExport" name="data_export">
                                                <label class="form-check-label" for="dataExport">Allow Data Export</label>
                                            </div>

                                            <button type="submit" class="btn btn-info">
                                                <i class="fas fa-save me-2"></i>Update Privacy Settings
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Appearance Tab -->
                            <div class="tab-pane fade" id="appearance">
                                <div class="settings-card">
                                    <div class="card-header bg-secondary text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-palette me-2"></i>Appearance Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <h6 class="mb-3">Theme</h6>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="theme" id="lightTheme" value="light" checked>
                                                    <label class="form-check-label" for="lightTheme">
                                                        <i class="fas fa-sun me-2"></i>Light Theme
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="theme" id="darkTheme" value="dark">
                                                    <label class="form-check-label" for="darkTheme">
                                                        <i class="fas fa-moon me-2"></i>Dark Theme
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="theme" id="autoTheme" value="auto">
                                                    <label class="form-check-label" for="autoTheme">
                                                        <i class="fas fa-adjust me-2"></i>Auto (System)
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <h6 class="mb-3">Font Size</h6>
                                                <select class="form-select" name="font_size">
                                                    <option value="small">Small</option>
                                                    <option value="medium" selected>Medium</option>
                                                    <option value="large">Large</option>
                                                    <option value="xlarge">Extra Large</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <h6 class="mb-3">Sidebar Style</h6>
                                                <select class="form-select" name="sidebar_style">
                                                    <option value="expanded">Expanded</option>
                                                    <option value="collapsed">Collapsed</option>
                                                    <option value="auto">Auto Hide</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <h6 class="mb-3">Color Scheme</h6>
                                                <select class="form-select" name="color_scheme">
                                                    <option value="blue">Blue</option>
                                                    <option value="green">Green</option>
                                                    <option value="purple">Purple</option>
                                                    <option value="red">Red</option>
                                                    <option value="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-secondary" onclick="saveAppearanceSettings()">
                                            <i class="fas fa-palette me-2"></i>Update Appearance
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Activate tabs when clicking on nav items
            const triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'));
            triggerTabList.forEach(function (triggerEl) {
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault();
                    const tabTrigger = new bootstrap.Tab(triggerEl);
                    tabTrigger.show();
                });
            });

            // Update active nav item when tab changes
            const tabEls = document.querySelectorAll('a[data-bs-toggle="tab"]');
            tabEls.forEach(tabEl => {
                tabEl.addEventListener('shown.bs.tab', function (event) {
                    // Remove active class from all nav items
                    tabEls.forEach(el => el.classList.remove('active'));
                    // Add active class to clicked nav item
                    event.target.classList.add('active');
                });
            });
        });

        function saveAppearanceSettings() {
            // Here you would typically save appearance settings
            alert('Appearance settings updated!');
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>