<!-- app/Views/layouts/sidebar.php -->
<style>
    /* Enhanced Sidebar Styles */
    .sidebar {
        width: 280px;
        min-height: 100vh;
        background: linear-gradient(180deg, #2c3e50 0%, #34495e 50%, #3498db 100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: fixed;
        z-index: 1000;
        box-shadow: 4px 0 25px rgba(0,0,0,0.15);
        backdrop-filter: blur(10px);
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar.collapsed .menu-text,
    .sidebar.collapsed .sidebar-header-text,
    .sidebar.collapsed .user-info,
    .sidebar.collapsed .nav-badge {
        display: none !important;
    }

    .sidebar.collapsed .nav-link {
        text-align: center;
        justify-content: center;
        padding: 0.75rem 0.25rem;
        margin: 6px 8px;
        border-radius: 10px;
    }

    .sidebar.collapsed .nav-link i {
        margin-right: 0;
        font-size: 1.3rem;
    }

    .sidebar.collapsed .user-section {
        padding: 1rem 0.5rem !important;
    }

    /* Header Styles */
    .sidebar-header {
        padding: 1.5rem 1rem;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .sidebar-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #3498db, #2ecc71, #e74c3c);
    }

    /* Navigation Styles */
    .sidebar .nav-link {
        color: #ecf0f1;
        padding: 14px 20px;
        margin: 4px 12px;
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
        overflow: hidden;
        border: 1px solid transparent;
        position: relative;
        font-weight: 500;
    }

    .sidebar .nav-link::before {
        content: '';
        position: absolute;
        left: -10px;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 0;
        background: #3498db;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateX(8px);
        border-color: rgba(255, 255, 255, 0.15);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .sidebar .nav-link:hover::before {
        height: 60%;
    }

    .sidebar .nav-link.active {
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.25), rgba(41, 128, 185, 0.15));
        border-left: 4px solid #3498db;
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.25);
        transform: translateX(5px);
        border-color: rgba(52, 152, 219, 0.3);
    }

    .sidebar .nav-link.active::before {
        height: 80%;
        background: #2ecc71;
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 12px;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .sidebar .nav-link.active i {
        transform: scale(1.2);
        color: #2ecc71;
    }

    /* Badge Styles */
    .nav-badge {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: auto;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* User Section */
    .user-section {
        background: rgba(0, 0, 0, 0.2);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem 1rem;
        margin-top: auto;
        position: relative;
    }

    .user-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 20%;
        right: 20%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3498db, #2ecc71);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .user-status {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #2ecc71;
        border: 2px solid #2c3e50;
        position: absolute;
        bottom: 0;
        right: 0;
    }

    /* Quick Stats */
    .quick-stats {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1rem;
        margin: 1rem 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .stat-item {
        text-align: center;
        padding: 0.5rem;
    }

    .stat-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: #3498db;
        display: block;
    }

    .stat-label {
        font-size: 0.7rem;
        color: #bdc3c7;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Main Content Adjustment */
    .main-content {
        margin-left: 280px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 100vh;
        background: #f8f9fa;
    }

    .main-content.expanded {
        margin-left: 70px;
    }

    /* Toggle Button Enhancement */
    .sidebar-toggle {
        background: rgba(52, 152, 219, 0.1);
        border: 1px solid rgba(52, 152, 219, 0.2);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }

    .sidebar-toggle:hover {
        background: rgba(52, 152, 219, 0.2);
        transform: scale(1.05);
    }

    .toggle-icon.rotated {
        transform: rotate(180deg);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: -280px;
            width: 280px;
        }
        
        .sidebar.mobile-show {
            margin-left: 0;
            box-shadow: 4px 0 30px rgba(0,0,0,0.3);
        }
        
        .main-content {
            margin-left: 0;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .mobile-overlay.show {
            display: block;
        }
    }

    /* Scrollbar Styling */
    .sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.1);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(52, 152, 219, 0.5);
        border-radius: 2px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(52, 152, 219, 0.7);
    }
</style>

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>

<!-- Sidebar -->
<nav id="sidebar" class="sidebar d-flex flex-column">
    <div class="flex-grow-1 d-flex flex-column">
        <!-- Sidebar Header -->
        <div class="sidebar-header text-center">
            <h4 class="fw-bold text-white sidebar-header-text mb-2">
                <i class="fas fa-wallet me-2"></i>Expense Tracker
            </h4>
            <p class="text-white-50 small sidebar-header-text mb-0">Manage your finances</p>
        </div>
        
      

        <!-- Navigation Menu -->
        <ul class="nav flex-column flex-grow-1 px-3 mb-4">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= current_url() === base_url() ? 'active' : '' ?>" href="<?= base_url() ?>">
                    <i class="fas fa-home"></i>
                    <span class="menu-text ms-3">Expenses</span>
                    <!-- <span class="nav-badge">New</span> -->
                </a>
            </li>
          
            <!-- <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= strpos(current_url(), 'expenses') !== false ? 'active' : '' ?>" href="<?= site_url('expenses') ?>">
                    <i class="fas fa-receipt"></i>
                    <span class="menu-text ms-3">Expenses</span>
                </a>
            </li> -->

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= strpos(current_url(), 'income') !== false ? 'active' : '' ?>" href="<?= site_url('income') ?>">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="menu-text ms-3">Income</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= strpos(current_url(), 'categories') !== false ? 'active' : '' ?>" href="<?= site_url('categories') ?>">
                    <i class="fas fa-tags"></i>
                    <span class="menu-text ms-3">Categories</span>
                    <!-- <span class="nav-badge">8</span> -->
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= strpos(current_url(), 'budget') !== false ? 'active' : '' ?>" href="<?= site_url('budget') ?>">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-text ms-3">Budget</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= strpos(current_url(), 'reports') !== false ? 'active' : '' ?>" href="<?= site_url('reports') ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span class="menu-text ms-3">Reports</span>
                </a>
            </li>

            <!-- Additional Menu Items -->
            <li class="nav-item mt-3">
                <a class="nav-link d-flex align-items-center <?= strpos(current_url(), 'profile') !== false ? 'active' : '' ?>" href="<?= site_url('profile') ?>">
                    <i class="fas fa-user-cog"></i>
                    <span class="menu-text ms-3">Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?= strpos(current_url(), 'settings') !== false ? 'active' : '' ?>" href="<?= site_url('settings') ?>">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text ms-3">Settings</span>
                </a>
            </li>
        </ul>

        <!-- User Section -->
        <div class="user-section user-info mt-auto">
            <div class="d-flex align-items-center position-relative">
                <div class="position-relative">
                    <div class="user-avatar">
                        <?= strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="user-status"></div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0 text-white"><?= auth()->user()->username ?? 'User' ?></h6>
                    <small class="text-white-50"><?= auth()->user()->email ?? '' ?></small>
                </div>
            </div>
            <div class="mt-3">
                <a href="<?= site_url('logout') ?>" class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    <span class="sidebar-header-text">Logout</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Enhanced Sidebar functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        const toggleBtn = document.getElementById('sidebarToggle');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        if (sidebar && mainContent && toggleBtn) {
            const toggleIcon = toggleBtn.querySelector('.toggle-icon');
            let isCollapsed = false;

            // Check saved state
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

            // Mobile behavior
            function handleResize() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('collapsed', 'mobile-show');
                    mainContent.classList.remove('expanded');
                    if (mobileOverlay) mobileOverlay.classList.remove('show');
                } else {
                    if (isCollapsed) {
                        collapseSidebar();
                    } else {
                        expandSidebar();
                    }
                }
            }

            // Mobile toggle with overlay
            function toggleMobileSidebar() {
                sidebar.classList.toggle('mobile-show');
                if (mobileOverlay) mobileOverlay.classList.toggle('show');
            }

            // Event listeners
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (window.innerWidth < 768) {
                    toggleMobileSidebar();
                } else {
                    toggleSidebar();
                }
            });

            // Close sidebar when clicking on overlay (mobile)
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-show');
                    mobileOverlay.classList.remove('show');
                });
            }

            // Close sidebar when clicking on a link (mobile)
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('mobile-show');
                        if (mobileOverlay) mobileOverlay.classList.remove('show');
                    }
                });
            });

            // Initial setup
            handleResize();
            window.addEventListener('resize', handleResize);

            // Add hover effects for desktop
            if (window.innerWidth >= 768) {
                sidebar.addEventListener('mouseenter', function() {
                    if (isCollapsed) {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.add('expanded-temp');
                    }
                });

                sidebar.addEventListener('mouseleave', function() {
                    if (isCollapsed) {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.remove('expanded-temp');
                    }
                });
            }
        }
    });
</script>