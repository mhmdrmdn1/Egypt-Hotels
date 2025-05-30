<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: unauthorized.php');
    exit;
}
require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../classes/User.php';

$pdo = getPDO();
if (!userHasPermission($_SESSION['admin_id'], 'manage_reviews', $pdo)) {
    $_SESSION['error'] = 'You do not have permission to access this page.';
    header('Location: index.php');
    exit;
}

// Initialize variables with default values
$page_title = 'Reviews';
$reviews = [];
$total_reviews = 0;
$total_pages = 1;
$hotels = [];
$status = $_GET['status'] ?? 'all';
$hotel_id = $_GET['hotel'] ?? 'all';
$rating = $_GET['rating'] ?? 'all';
$page = max(1, $_GET['page'] ?? 1);
$per_page = 10;

// Start output buffering
ob_start();

try {
    // Handle actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $review_id = $_POST['review_id'] ?? 0;
        
        try {
            switch ($_POST['action']) {
                case 'approve':
                    $stmt = $pdo->prepare("UPDATE hotel_ratings SET status = 'approved' WHERE id = ?");
                    $stmt->execute([$review_id]);
                    $_SESSION['success'] = 'Review approved successfully.';
                    break;
                    
                case 'reject':
                    $stmt = $pdo->prepare("UPDATE hotel_ratings SET status = 'rejected' WHERE id = ?");
                    $stmt->execute([$review_id]);
                    $_SESSION['success'] = 'Review rejected successfully.';
                    break;
                    
                case 'delete':
                    $stmt = $pdo->prepare("DELETE FROM hotel_ratings WHERE id = ?");
                    $stmt->execute([$review_id]);
                    $_SESSION['success'] = 'Review deleted successfully.';
                    break;
            }
        } catch (PDOException $e) {
            error_log("Review action error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to process review action.';
        }
        
        header('Location: reviews.php');
        exit;
    }

    // Get filters
    $status = $_GET['status'] ?? 'all';
    $hotel_id = $_GET['hotel'] ?? 'all';
    $rating = $_GET['rating'] ?? 'all';

    // Build query
    $where_conditions = [];
    $params = [];

    if ($status !== 'all') {
        $where_conditions[] = "r.status = ?";
        $params[] = $status;
    }

    if ($hotel_id !== 'all') {
        $where_conditions[] = "r.hotel_id = ?";
        $params[] = $hotel_id;
    }

    if ($rating !== 'all') {
        $where_conditions[] = "r.rating = ?";
        $params[] = $rating;
    }

    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

    // Get reviews with pagination
    $page = max(1, $_GET['page'] ?? 1);
    $per_page = 10;
    $offset = ($page - 1) * $per_page;

    // جلب جميع التقييمات (فنادق وغرف)
    $hotel_ratings_sql = "
        SELECT r.id, h.name AS hotel_name, NULL AS room_name, u.username AS user_name, r.rating, NULL AS comment, r.created_at, 'hotel' AS review_type
        FROM hotel_ratings r
        JOIN hotels h ON r.hotel_id = h.id
        JOIN users u ON r.user_id = u.id
    ";
    $room_reviews_sql = "
        SELECT r.id, h.name AS hotel_name, r.room_name, u.username AS user_name, r.rating, r.comment, r.review_date AS created_at, 'room' AS review_type
        FROM reviews r
        JOIN hotels h ON r.hotel_id = h.id
        JOIN users u ON r.user_id = u.id
    ";
    $all_reviews_sql = "$hotel_ratings_sql UNION ALL $room_reviews_sql ORDER BY created_at DESC";
    try {
        $stmt = $pdo->prepare($all_reviews_sql);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('<div style="color:red;font-weight:bold;">SQL Error: ' . htmlspecialchars($e->getMessage()) . '</div>');
    }

    // تفعيل زر التصدير (Export) قبل أي إخراج
    if (isset($_GET['export'])) {
        $export_type = $_GET['export'];
        $filename = 'reviews_export_' . date('Ymd_His');
        $delimiter = $export_type === 'csv' ? ',' : "\t";
        header('Content-Type: text/' . ($export_type === 'csv' ? 'csv' : 'plain'));
        header('Content-Disposition: attachment; filename="' . $filename . '.' . $export_type . '"');
        $output = fopen('php://output', 'w');
        // رؤوس الأعمدة
        fputcsv($output, ['Type', 'Hotel', 'Room', 'User', 'Rating', 'Review', 'Date'], $delimiter);
        foreach ($reviews as $review) {
            fputcsv($output, [
                $review['review_type'],
                $review['hotel_name'],
                $review['room_name'],
                $review['user_name'],
                $review['rating'],
                $review['comment'],
                $review['created_at'],
            ], $delimiter);
        }
        fclose($output);
        exit;
    }

    // Get total count for pagination (both hotel_ratings and reviews)
    $count_sql = "
        SELECT COUNT(*) FROM (
            SELECT r.id
            FROM hotel_ratings r
            JOIN hotels h ON r.hotel_id = h.id
            JOIN users u ON r.user_id = u.id
            UNION ALL
            SELECT r.id
            FROM reviews r
            JOIN hotels h ON r.hotel_id = h.id
            JOIN users u ON r.user_id = u.id
        ) AS all_reviews
    ";
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute();
    $total_reviews = $stmt->fetchColumn();
    $total_pages = ceil($total_reviews / $per_page);

    // Get hotels for filter
    $hotels = $pdo->query("SELECT id, name FROM hotels ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $_SESSION['error'] = 'Failed to load reviews.';
    header('Location: index.php');
    exit;
}
?>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-9">
                <form method="get" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Hotel</label>
                        <select name="hotel" class="form-select">
                            <option value="all">All Hotels</option>
                            <?php foreach ($hotels as $hotel): ?>
                                <option value="<?= $hotel['id'] ?>" <?= $hotel_id == $hotel['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($hotel['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select">
                            <option value="all">All Ratings</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>" <?= $rating == $i ? 'selected' : '' ?>>
                                    <?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="reviews.php" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-end d-flex align-items-end justify-content-end">
                <div class="btn-group dropup">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="?export=csv">
                                <i class="fas fa-file-csv"></i> Export as CSV
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="?export=excel">
                                <i class="fas fa-file-excel"></i> Export as Excel
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Reviews</h5>
        <span class="badge bg-secondary">
            <?php
                if ($total_reviews == 0) {
                    echo "No reviews";
                } elseif ($total_reviews == 1) {
                    echo "1 review";
                } else {
                    echo number_format($total_reviews) . " reviews";
                }
            ?>
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Hotel</th>
                    <th>Room</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="mb-0 text-muted">No reviews found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td>
                                <?php if ($review['review_type'] === 'hotel'): ?>
                                    <span class="badge bg-info">Hotel</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Room</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($review['hotel_name']) ?></td>
                            <td><?= $review['room_name'] ? htmlspecialchars($review['room_name']) : '-' ?></td>
                            <td><?= htmlspecialchars($review['user_name']) ?></td>
                            <td>
                                <span class="text-warning">
                                    <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (strlen($review['comment']) > 100): ?>
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                        <?= htmlspecialchars($review['comment']) ?>
                                    </span>
                                    <button type="button" class="btn btn-link btn-sm p-0" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#reviewModal<?= $review['id'] ?>">
                                        Read more
                                    </button>
                                <?php else: ?>
                                    <?= htmlspecialchars($review['comment']) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M j, Y', strtotime($review['created_at'])) ?></td>
                            <td>
                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Full Review Modal -->
                        <?php if (strlen($review['comment']) > 100): ?>
                            <div class="modal fade" id="reviewModal<?= $review['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Review for <?= htmlspecialchars($review['hotel_name']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <strong>Rating:</strong>
                                                <span class="text-warning">
                                                    <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>User:</strong>
                                                <?= htmlspecialchars($review['user_name']) ?>
                                            </div>
                                            <div>
                                                <strong>Review:</strong>
                                                <p class="mt-2"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($total_pages > 1): ?>
        <div class="card-footer">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&status=<?= $status ?>&hotel=<?= $hotel_id ?>&rating=<?= $rating ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the admin layout
require_once 'templates/admin_layout.php';
?> 
