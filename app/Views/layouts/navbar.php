<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <button class="sidebar-toggle me-3" id="sidebarToggle">
            <i class="fas fa-bars toggle-icon"></i>
        </button>
        
        <div class="navbar-brand">
            <i class="fas fa-chart-pie me-2 text-primary"></i>
           Dashboard
        </div>
        
        <div class="d-flex align-items-center ms-auto">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-2"></i>
                    <?= auth()->user()->username ?? 'User' ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                      <li><a class="dropdown-item" href="<?= site_url('profile') ?>">
                        <i class="fas fa-user me-2"></i>Profile
                    </a></li>
                    <li><a class="dropdown-item" href="<?= site_url('settings') ?>">
                        <i class="fas fa-cog me-2"></i>Settings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>