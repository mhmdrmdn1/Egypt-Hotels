<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','manager','staff'])) {
    header('Location: unauthorized.php');
    exit;
}
require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../classes/User.php';

// Check admin login
// checkAdminLogin(); // تم التعطيل بناءً على طلب الإدارة

// Initialize variables with default values
$page_title = 'Bookings Management';
$bookings = [];
$total_bookings = 0;
$total_pages = 1;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;

try {
    $pdo = getPDO();
    
    // Check permission
    $userManager = new User($pdo, $_SESSION['admin_id'] ?? 0);
    if (!$userManager->hasPermission('manage_bookings')) {
        $_SESSION['error'] = 'You do not have permission to access this page.';
        header('Location: index.php');
        exit;
    }

    // Get filters from request
    $filters = [
        'hotel' => $_GET['hotel'] ?? '',
        'guest' => $_GET['guest'] ?? '',
        'status' => $_GET['status'] ?? '',
        'date_from' => $_GET['date_from'] ?? '',
        'date_to' => $_GET['date_to'] ?? '',
        'search' => $_GET['search'] ?? ''
    ];

    // Get sort parameters
    $sort = [
        'field' => $_GET['sort'] ?? 'created_at',
        'direction' => $_GET['direction'] ?? 'desc'
    ];

    // Validate sort field to prevent SQL injection
    $allowed_sort_fields = ['created_at', 'check_in', 'check_out', 'status', 'total_price'];
    if (!in_array($sort['field'], $allowed_sort_fields)) {
        $sort['field'] = 'created_at';
    }

    // Build query conditions
    $where = [];
    $params = [];

    if ($filters['hotel']) {
        $where[] = "h.id = ?";
        $params[] = $filters['hotel'];
    }

    if ($filters['guest']) {
        $where[] = "u.id = ?";
        $params[] = $filters['guest'];
    }

    if ($filters['status']) {
        $where[] = "b.status = ?";
        $params[] = $filters['status'];
    }

    if ($filters['date_from']) {
        $where[] = "b.check_in >= ?";
        $params[] = $filters['date_from'];
    }

    if ($filters['date_to']) {
        $where[] = "b.check_out <= ?";
        $params[] = $filters['date_to'];
    }

    if ($filters['search']) {
        $where[] = "(h.name LIKE ? OR u.name LIKE ? OR u.email LIKE ?)";
        $search = "%{$filters['search']}%";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    // Get total count for pagination
    try {
        $countQuery = "
            SELECT COUNT(*) 
            FROM bookings b
            JOIN hotels h ON b.hotel_id = h.id
            JOIN users u ON b.user_id = u.id
            $whereClause
        ";
        $stmt = $pdo->prepare($countQuery);
        $stmt->execute($params);
        $total_bookings = $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error getting booking count: " . $e->getMessage());
        $total_bookings = 0;
    }

    // Pagination
    $total_pages = ceil($total_bookings / $limit);
    $offset = ($page - 1) * $limit;

    // Get bookings
    try {
        $query = "
            SELECT 
                b.*,
                h.name as hotel_name,
                u.name as guest_name,
                u.email as guest_email
            FROM bookings b
            JOIN hotels h ON b.hotel_id = h.id
            JOIN users u ON b.user_id = u.id
            $whereClause
            ORDER BY b.{$sort['field']} {$sort['direction']}
            LIMIT ? OFFSET ?
        ";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting bookings: " . $e->getMessage());
        $bookings = [];
        $_SESSION['error'] = 'Failed to load bookings. Please try again later.';
    }

    // Get hotels for filter dropdown
    $stmt = $pdo->query("SELECT id, name FROM hotels ORDER BY name");
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get booking statistics
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
            SUM(CASE WHEN check_in >= CURDATE() THEN 1 ELSE 0 END) as upcoming,
            SUM(total_price) as total_revenue
        FROM bookings
    ");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    // بعد جلب $bookings مباشرة
    if (isset($_GET['export'])) {
        $export_type = $_GET['export'];
        $filename = 'bookings_export_' . date('Ymd_His');
        if ($export_type === 'csv') {
            $delimiter = ',';
            $content_type = 'text/csv';
            $ext = 'csv';
        } else {
            $delimiter = "\t";
            $content_type = 'application/vnd.ms-excel';
            $ext = 'xls';
        }
        header('Content-Type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . $filename . '.' . $ext . '"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Booking ID', 'Hotel', 'Guest', 'Check-in', 'Check-out', 'Status', 'Total', 'Created At'], $delimiter);
        foreach ($bookings as $booking) {
            fputcsv($output, [
                $booking['id'],
                $booking['hotel_name'],
                $booking['guest_name'],
                date('M j, Y', strtotime($booking['check_in'])),
                date('M j, Y', strtotime($booking['check_out'])),
                ucfirst($booking['status']),
                'EGP'.number_format($booking['total_price'], 2),
                date('M j, Y H:i', strtotime($booking['created_at'])),
            ], $delimiter);
        }
        fclose($output);
        exit;
    }

} catch (Exception $e) {
    error_log("Bookings page error: " . $e->getMessage());
    $_SESSION['error'] = 'An error occurred while loading the page.';
    header('Location: index.php');
    exit;
}

ob_start();
?>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Bookings -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Total Bookings</h6>
                        <h4 class="mb-0"><?= number_format($stats['total'] ?? 0) ?></h4>
                        <small class="text-muted">
                            <?= number_format($stats['upcoming'] ?? 0) ?> upcoming
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmed Bookings -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Confirmed</h6>
                        <h4 class="mb-0"><?= number_format($stats['confirmed'] ?? 0) ?></h4>
                        <small class="text-success">
                            <?= round((($stats['confirmed'] ?? 0) / max(1, $stats['total'] ?? 0)) * 100) ?>% of total
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Bookings -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Pending</h6>
                        <h4 class="mb-0"><?= number_format($stats['pending'] ?? 0) ?></h4>
                        <small class="text-warning">
                            <?= round((($stats['pending'] ?? 0) / max(1, $stats['total'] ?? 0)) * 100) ?>% of total
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Total Revenue</h6>
                        <h4 class="mb-0">EGP<?= number_format($stats['total_revenue'] ?? 0, 2) ?></h4>
                        <small class="text-muted">
                            From confirmed bookings
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-dollar-sign fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <form action="" method="get" class="row g-2">
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Search bookings..." 
                                   value="<?= htmlspecialchars($search ?? '') ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if (isset($search)): ?>
                                <a href="bookings.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form action="" method="get" class="row g-3">
            <!-- Search -->
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search bookings..." 
                           value="<?= htmlspecialchars($filters['search']) ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Hotel Filter -->
            <div class="col-md-4">
                <select class="form-select" name="hotel" onchange="this.form.submit()">
                    <option value="">All Hotels</option>
                    <?php foreach ($hotels as $hotel): ?>
                        <option value="<?= $hotel['id'] ?>" <?= $filters['hotel'] == $hotel['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($hotel['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="col-md-4">
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed" <?= $filters['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="cancelled" <?= $filters['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="col-md-4">
                <input type="date" class="form-control" name="date_from" 
                       value="<?= htmlspecialchars($filters['date_from']) ?>"
                       placeholder="From Date">
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control" name="date_to" 
                       value="<?= htmlspecialchars($filters['date_to']) ?>"
                       placeholder="To Date">
            </div>

            <!-- Clear Filters -->
            <?php if (array_filter($filters)): ?>
                <div class="col-md-4">
                    <a href="?" class="btn btn-secondary w-100">Clear Filters</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Bookings List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Bookings List</h5>
        <div class="btn-group">
            <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'excel'])) ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'pdf'])) ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'id', 'direction' => $sort['field'] === 'id' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>"
                               class="text-decoration-none text-dark">
                                ID
                                <?php if ($sort['field'] === 'id'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'hotel_name', 'direction' => $sort['field'] === 'hotel_name' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>"
                               class="text-decoration-none text-dark">
                                Hotel
                                <?php if ($sort['field'] === 'hotel_name'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Guest</th>
                        <th>
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'check_in', 'direction' => $sort['field'] === 'check_in' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>"
                               class="text-decoration-none text-dark">
                                Check-in
                                <?php if ($sort['field'] === 'check_in'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'check_out', 'direction' => $sort['field'] === 'check_out' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>"
                               class="text-decoration-none text-dark">
                                Check-out
                                <?php if ($sort['field'] === 'check_out'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'status', 'direction' => $sort['field'] === 'status' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>"
                               class="text-decoration-none text-dark">
                                Status
                                <?php if ($sort['field'] === 'status'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'total_price', 'direction' => $sort['field'] === 'total_price' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>"
                               class="text-decoration-none text-dark">
                                Total
                                <?php if ($sort['field'] === 'total_price'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th style="width: 100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= $booking['id'] ?></td>
                            <td><?= htmlspecialchars($booking['hotel_name']) ?></td>
                            <td>
                                <div><?= htmlspecialchars($booking['guest_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($booking['guest_email']) ?></small>
                            </td>
                            <td><?= date('M j, Y', strtotime($booking['check_in'])) ?></td>
                            <td><?= date('M j, Y', strtotime($booking['check_out'])) ?></td>
                            <td>
                                <select class="form-select form-select-sm status-select" 
                                        data-booking-id="<?= $booking['id'] ?>"
                                        style="width: 130px;">
                                    <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>
                                        Pending
                                    </option>
                                    <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>
                                        Confirmed
                                    </option>
                                    <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>
                                        Cancelled
                                    </option>
                                </select>
                            </td>
                            <td>EGP <?= number_format($booking['total_price'] ?? 0, 2) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="view_booking.php?id=<?= $booking['id'] ?>" 
                                       class="btn btn-info"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger delete-booking"
                                            data-id="<?= $booking['id'] ?>"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($total_pages > 1): ?>
        <div class="card-footer">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $page - 1, 'sort' => $sort['field'], 'direction' => $sort['direction']])) ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $i, 'sort' => $sort['field'], 'direction' => $sort['direction']])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $page + 1, 'sort' => $sort['field'], 'direction' => $sort['direction']])) ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();

// Add custom CSS
$extra_css = '
<style>
.stats-card {
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.table th a {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.table th a:hover {
    color: var(--bs-primary) !important;
}

.status-select {
    background-color: transparent;
    border-color: #dee2e6;
}

.status-select option[value="pending"] {
    background-color: var(--bs-warning);
    color: white;
}

.status-select option[value="confirmed"] {
    background-color: var(--bs-success);
    color: white;
}

.status-select option[value="cancelled"] {
    background-color: var(--bs-danger);
    color: white;
}
</style>
';

// Add custom JavaScript
$extra_js = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Handle status changes
    document.querySelectorAll(".status-select").forEach(select => {
        select.addEventListener("change", function() {
            const bookingId = this.dataset.bookingId;
            const newStatus = this.value;
            
            fetch("actions/update_booking_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    booking_id: bookingId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.error || "Failed to update booking status");
                    // Reset to previous value
                    this.value = this.getAttribute("data-original-value");
                }
            });
        });
        
        // Store original value for reverting on error
        select.setAttribute("data-original-value", select.value);
    });

    // Handle booking deletion
    document.querySelectorAll(".delete-booking").forEach(btn => {
        btn.addEventListener("click", function() {
            if (!confirm("Are you sure you want to delete this booking? This action cannot be undone.")) {
                return;
            }
            
            const bookingId = this.dataset.id;
            const row = this.closest("tr");
            
            fetch("actions/delete_booking.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    booking_id: bookingId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                } else {
                    alert(data.error || "Failed to delete booking");
                }
            });
        });
    });
});
</script>
';

require_once __DIR__ . '/templates/admin_layout.php';
?> 