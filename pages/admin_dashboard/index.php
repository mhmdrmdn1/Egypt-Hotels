<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','manager','editor','staff','user'])) {
    header('Location: unauthorized.php');
    exit;
}

// Admin access check
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../login/login.html');
    exit;
}
// ضبط admin_id تلقائيًا إذا كان المستخدم أدمن
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../classes/User.php';
    require_once __DIR__ . '/../../config/database.php';
    $pdo = getPDO();
    $userManager = new User($pdo, $_SESSION['user_id']);
    if ($userManager->hasRole('admin')) {
        $_SESSION['admin_id'] = $_SESSION['user_id'];
    }
}

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../classes/User.php';

// Initialize variables with default values
$page_title = 'Dashboard';
$content = '';
$extra_css = '';
$error = false;

try {
    $pdo = getPDO();
    
    // Get pending messages count
    try {
        $messagesQuery = "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'pending'";
        $messagesStmt = $pdo->query($messagesQuery);
        $pendingMessages = $messagesStmt->fetch(PDO::FETCH_ASSOC)['count'];
    } catch (PDOException $e) {
        error_log("Error fetching pending messages count: " . $e->getMessage());
        $pendingMessages = 0;
    }
    
    // Start output buffering to capture the content
    ob_start();
?>

<div class="container-fluid py-4">
    <!-- Stats Cards Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-light-primary rounded">
                                <i class="fas fa-hotel fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1" id="totalHotels">-</h3>
                            <p class="text-muted mb-0">Total Hotels</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-light-success rounded">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1" id="totalUsers">-</h3>
                            <p class="text-muted mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-light-warning rounded">
                                <i class="fas fa-calendar-check fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1" id="activeHotels">-</h3>
                            <p class="text-muted mb-0">Active Hotels</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-light-info rounded">
                                <i class="fas fa-star fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1" id="pendingReviews">-</h3>
                            <p class="text-muted mb-0">Pending Reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-light-danger rounded">
                                <i class="fas fa-envelope fa-2x text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1" id="pendingMessages">-</h3>
                            <p class="text-muted mb-0">Contact Requests</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-md-6">
            <div class="card dashboard-chart-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Revenue Overview</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Last 6 Months
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
                            <li><a class="dropdown-item" href="#">Last Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="height:320px;max-height:320px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="col-md-6">
            <div class="card dashboard-chart-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Rating Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="ratingChart" style="height:320px;max-height:320px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Top Hotels Row -->
    <div class="row g-4">
        <!-- Recent Activities -->
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activities</h5>
                </div>
                <div class="card-body">
                    <div class="timeline" id="recentActivities">
                        <?php
                        try {
                            // Get all activities in one query
                            $activitiesQuery = "
                                (SELECT 'booking' as type, created_at, CONCAT(user_name, ' booked ', hotel_name) as description 
                                FROM bookings 
                                ORDER BY created_at DESC)
                                UNION ALL
                                (SELECT 'review' as type, created_at, CONCAT(user_name, ' reviewed ', hotel_name) as description 
                                FROM reviews 
                                ORDER BY created_at DESC)
                                UNION ALL
                                (SELECT 'message' as type, created_at, CONCAT(name, ' sent a message') as description 
                                FROM contact_messages 
                                ORDER BY created_at DESC)
                                ORDER BY created_at DESC 
                                LIMIT 5";
                            
                            $stmt = $pdo->query($activitiesQuery);
                            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($activities) > 0):
                                foreach ($activities as $activity):
                                    $icon = '';
                                    $color = '';
                                    switch ($activity['type']) {
                                        case 'booking':
                                            $icon = 'calendar-check';
                                            $color = 'success';
                                            break;
                                        case 'review':
                                            $icon = 'star';
                                            $color = 'warning';
                                            break;
                                        case 'message':
                                            $icon = 'envelope';
                                            $color = 'info';
                                            break;
                                    }
                        ?>
                                    <div class="timeline-item">
                                        <div class="timeline-icon bg-light-<?php echo $color; ?>">
                                            <i class="fas fa-<?php echo $icon; ?> text-<?php echo $color; ?>"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <p class="mb-0"><?php echo htmlspecialchars($activity['description']); ?></p>
                                            <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?></small>
                                        </div>
                                    </div>
                        <?php 
                                endforeach;
                            else:
                        ?>
                                <div class="text-center py-3">
                                    <p class="text-muted mb-0">No recent activities</p>
                                </div>
                        <?php 
                            endif;
                        } catch (PDOException $e) {
                            error_log("Error fetching recent activities: " . $e->getMessage());
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Hotels -->
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Hotels</h5>
                </div>
                <div class="card-body">
                    <div id="topHotels">
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update pending messages count
    document.getElementById('pendingMessages').textContent = '<?php echo $pendingMessages; ?>';
    
    // ... existing JavaScript code ...
});
</script>

<?php
    // Capture the content
    $content = ob_get_clean();

    // Add extra CSS
    $extra_css = '
    <style>
    /* Timeline styles */
    .timeline {
        position: relative;
        padding: 0;
        list-style: none;
    }

    .timeline-item {
        position: relative;
        padding-left: 3rem;
        padding-bottom: 1.5rem;
    }

    .timeline-item:not(:last-child):before {
        content: "";
        position: absolute;
        left: 0.85rem;
        top: 2rem;
        bottom: 0;
        border-left: 2px dashed #e9ecef;
    }

    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        text-align: center;
        line-height: 2rem;
        background: #fff;
        border: 2px solid;
        z-index: 1;
    }

    .timeline-icon.success {
        color: #28a745;
        border-color: #28a745;
    }

    .timeline-icon.warning {
        color: #ffc107;
        border-color: #ffc107;
    }

    .timeline-icon.info {
        color: #17a2b8;
        border-color: #17a2b8;
    }

    .timeline-content {
        padding: 0.5rem 0;
    }

    /* Avatar styles */
    .avatar {
        width: 3rem;
        height: 3rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
    }

    .avatar.avatar-lg {
        width: 4rem;
        height: 4rem;
    }

    .bg-light-primary {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }

    .bg-light-success {
        background-color: rgba(var(--bs-success-rgb), 0.1) !important;
    }

    .bg-light-warning {
        background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
    }

    .bg-light-info {
        background-color: rgba(var(--bs-info-rgb), 0.1) !important;
    }

    .dashboard-chart-card { min-height: 400px; max-height: 400px; overflow: hidden; }
    @media (max-width: 991.98px) {
        .dashboard-chart-card { min-height: 320px; max-height: 320px; }
    }

    .top-hotels-list {
        max-height: 350px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .hotel-entry {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    .hotel-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
        background: #f8f9fa;
    }
    .hotel-info {
        min-width: 0;
        flex: 1 1 0%;
    }
    .hotel-name {
        display: block;
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .rating {
        font-size: 1.1rem;
        line-height: 1;
    }
    </style>
    ';

} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while loading the dashboard.";
    echo '<div style="color:red;font-weight:bold;">Dashboard error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit;
}

// Include the admin layout template
require_once __DIR__ . '/templates/admin_layout.php';
?> 