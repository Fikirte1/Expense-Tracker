<!-- app/Views/profile/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Profile' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }

        .profile-card {
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            border: none;
            background: white;
        }

        .avatar-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #007bff;
        }

        /* Ensure main content has proper transition */
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
                    <i class="fas fa-user me-2 text-primary"></i>
                    <span>Profile</span>
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

        <!-- Profile Content -->
        <div class="container-fluid">
            <div class="profile-container">
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

                <div class="card profile-card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user me-2"></i>User Profile
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username) ?>&background=007bff&color=fff&size=120" 
                                     alt="Avatar" class="avatar-img mb-3">
                                <h5><?= esc($user->username) ?></h5>
                                <p class="text-muted"><?= esc($user->email) ?></p>
                                <span class="badge bg-<?= $user->active ? 'success' : 'warning' ?>">
                                    <?= $user->active ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <div class="col-md-8">
                                <form action="/profile/update" method="post">
                                    <?= csrf_field() ?>
                                    
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?= esc($user->username) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" 
                                               value="<?= esc($user->email) ?>" disabled>
                                        <small class="text-muted">Email cannot be changed</small>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Member Since</label>
                                            <p class="form-control-plaintext"><?= date('F j, Y', strtotime($user->created_at)) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Last Active</label>
                                            <p class="form-control-plaintext">
                                                <?= $user->last_active ? date('F j, Y g:i A', strtotime($user->last_active)) : 'Never' ?>
                                            </p>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>