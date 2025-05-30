<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/auth.php';
require_once __DIR__ . '/../../../classes/User.php';

// Initialize variables with default values
$base_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = str_replace('/pages/admin_dashboard', '', $base_path);
define('BASE_URL', $base_path);

$current_page = basename($_SERVER['PHP_SELF']);
$page_title = $page_title ?? 'Admin Dashboard';
$content = $content ?? '<div class="alert alert-danger">Error: Page content not found.</div>';
$extra_css = $extra_css ?? '';
$extra_js = $extra_js ?? '';
$userManager = null;

try {
    $pdo = getPDO();
    
    // Initialize user manager for permission checks
    $userManager = new User($pdo, $_SESSION['admin_id'] ?? 0);
    
    // Define allowed menu items for each role
    $role = $_SESSION['role'] ?? 'user';
    $roleMenuPermissions = [
        'admin' => ['view_dashboard','manage_hotels','manage_users','manage_bookings','manage_reviews','manage_gallery','manage_settings'],
        'manager' => ['view_dashboard','manage_hotels','manage_users','manage_bookings','view_reports'],
        'editor' => ['view_dashboard','edit_content','manage_gallery'],
        'staff' => ['view_dashboard','view_bookings'],
        'user' => ['view_dashboard'],
    ];
    
    // Define menu items with their required permissions
    $menu_items = [
        [
            'page' => 'index.php',
            'title' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'permission' => 'view_dashboard'
        ],
        [
            'page' => 'hotels.php',
            'title' => 'Hotels',
            'icon' => 'fas fa-hotel',
            'permission' => 'manage_hotels',
            'submenu' => [
                [
                    'page' => 'add_hotel.php',
                    'title' => 'Add New Hotel',
                    'icon' => 'fas fa-plus'
                ],
                [
                    'page' => 'hotel_gallery.php',
                    'title' => 'Hotel Gallery',
                    'icon' => 'fas fa-images'
                ]
            ]
        ],
        [
            'page' => 'users.php',
            'title' => 'Users',
            'icon' => 'fas fa-users',
            'permission' => 'manage_users'
        ],
        [
            'page' => 'bookings.php',
            'title' => 'Bookings',
            'icon' => 'fas fa-calendar-check',
            'permission' => 'manage_bookings'
        ],
        [
            'page' => 'reviews.php',
            'title' => 'Reviews',
            'icon' => 'fas fa-star',
            'permission' => 'manage_reviews'
        ],
        [
            'page' => 'gallery.php',
            'title' => 'User Gallery',
            'icon' => 'fas fa-images',
            'permission' => 'manage_gallery'
        ]
    ];
    
    // Function to check if a menu item should be displayed
    function shouldShowMenuItem($item) {
        global $userManager;
        return !isset($item['permission']) || ($userManager && $userManager->hasPermission($item['permission']));
    }
    
    // Function to check if a menu item is active
    function isMenuItemActive($item) {
        global $current_page;
        if ($current_page === $item['page']) return true;
        if (isset($item['submenu'])) {
            foreach ($item['submenu'] as $submenu_item) {
                if ($current_page === $submenu_item['page']) return true;
            }
        }
        return false;
    }
    
    function shouldShowMenuItemByRole($item, $role, $roleMenuPermissions) {
        if (!isset($item['permission'])) return true;
        return in_array($item['permission'], $roleMenuPermissions[$role] ?? []);
    }
    
} catch (Exception $e) {
    error_log("Admin layout error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while loading the page.";
    // header('Location: login.php'); // تم التعطيل بناءً على طلب الإدارة
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Hotel Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link href="<?= BASE_URL ?>/assets/css/admin.css" rel="stylesheet">
    
    <?php if (isset($extra_css)): ?>
        <?= $extra_css ?>
    <?php endif; ?>

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --text-color: #1f2937;
            --light-bg: #f3f4f6;
            --border-color: #e5e7eb;
            --hover-color: rgba(37, 99, 235, 0.1);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: var(--shadow-md);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: white;
        }

        .sidebar-content {
            padding: 1rem 0;
        }

        .sidebar .nav-link {
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
            font-weight: 500;
        }

        /* Submenu Styles */
        .sidebar .nav .nav {
            margin-top: 0.5rem;
        }

        .sidebar .nav .nav .nav-link {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .sidebar .nav .nav .nav-link:hover {
            opacity: 1;
        }

        .sidebar .nav .nav .nav-link.active {
            opacity: 1;
            font-weight: 500;
        }

        /* Main Content Styles */
        .content {
            flex: 1;
            margin-left: 260px;
            min-height: 100vh;
            background-color: var(--light-bg);
        }

        /* Navbar Styles */
        .navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            padding: 0.1rem 1.5rem;
            max-height: 50px;
        }

        .navbar-brand {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Container Styles */
        .container-fluid {
            padding: 1.5rem;
        }

        /* Card Styles */
        .card {
            background: white;
            border: none;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        /* Table Styles */
        .table {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--light-bg);
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Form Styles */
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            padding: 0.625rem 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }

            .container-fluid {
                padding: 1rem;
            }
        }
    </style>

    <!-- Dashboard specific scripts -->
    <?php if ($page_title === 'Dashboard'): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="../../assets/js/dashboard.js"></script>
    <?php endif; ?>

    <link rel="shortcut icon" href="../../assets/images/icons/web-icon.png" type="image/x-icon">
</head>
<body class="d-flex flex-column h-100">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h5 class="m-0">Egypt Hotels</h5>
            </div>
            <div class="sidebar-content">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'index' ? 'active' : '' ?>" href="index.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- إدارة المستخدمين -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'users' ? 'active' : '' ?>" href="users.php">
                            <i class="fas fa-users"></i> Users Management
                        </a>
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'add_user' ? 'active' : '' ?>" href="add_user.php">
                                    <i class="fas fa-user-plus"></i> Add User
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- إدارة الفنادق -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'hotels' ? 'active' : '' ?>" href="hotels.php">
                            <i class="fas fa-hotel"></i> Hotels Management
                        </a>
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'add_hotel' ? 'active' : '' ?>" href="add_hotel.php">
                                    <i class="fas fa-plus"></i> Add Hotel
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- إدارة الحجوزات -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'bookings' ? 'active' : '' ?>" href="bookings.php">
                            <i class="fas fa-calendar-check"></i> Bookings
                        </a>
                    </li>

                    <!-- إدارة المراجعات -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'reviews' ? 'active' : '' ?>" href="reviews.php">
                            <i class="fas fa-star"></i> Reviews
                        </a>
                    </li>

                    <!-- إدارة الطلبات -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'messages' ? 'active' : '' ?>" href="messages.php">
                            <i class="fas fa-envelope"></i> Contact Requests
                            <?php
                            try {
                                // Get pending messages count
                                $messagesQuery = "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'pending'";
                                $messagesStmt = $pdo->query($messagesQuery);
                                $pendingCount = $messagesStmt->fetch(PDO::FETCH_ASSOC)['count'];
                                
                                if ($pendingCount > 0): ?>
                                    <span class="badge bg-danger rounded-pill ms-auto"><?php echo $pendingCount; ?></span>
                                <?php endif;
                            } catch (PDOException $e) {
                                error_log("Error fetching pending messages count: " . $e->getMessage());
                            }
                            ?>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'gallery' ? 'active' : '' ?>" href="gallery.php">
                            <i class="fas fa-images"></i> User Gallery
                        </a>
                    </li>

                    <!-- العودة للموقع -->
                    <li class="nav-item mt-3">
                        <hr class="border-white opacity-25">
                        <a href="../index.php" class="nav-link text-white" id="back-to-website">
                            <i class="fas fa-arrow-left"></i> Back to Website
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <div class="content">
            <!-- Top navbar -->
            <nav class="navbar navbar-expand-lg shadow-sm">
                <div class="container-fluid px-3">
                    <a class="navbar-brand d-flex align-items-center" href="index.php">
                        <span>Egypt Hotels</span>
                    </a>
                </div>
            </nav>

            <!-- Page content -->
            <div class="container-fluid py-4">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.querySelector('.sidebar-toggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            if (window.innerWidth <= 768 && 
                sidebar?.classList.contains('active') && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
    
    <?= $extra_js ?>
</body>
</html> 