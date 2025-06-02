<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','manager'])) {
    header('Location: unauthorized.php');
    exit;
}
require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../classes/HotelGallery.php';

// checkAdminLogin(); // تم التعطيل بناءً على طلب الإدارة

// Initialize variables
$hotels = [];
$total_hotels = 0;
$total_pages = 1;
$current_page = isset($_GET['page']) ? max(1, min($total_pages, intval($_GET['page']))) : 1;
$search = '';
$error = false;

try {
    $pdo = getPDO();
    $gallery = new HotelGallery($pdo);

    // Handle bulk actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['selected_hotels'])) {
        if (!hasPermission('manage_hotels')) {
            $_SESSION['error'] = 'You do not have permission to perform this action.';
            header('Location: hotels.php');
            exit;
        }

        $selected_hotels = array_map('intval', $_POST['selected_hotels']);
        
        switch ($_POST['action']) {
            case 'activate':
                $stmt = $pdo->prepare("UPDATE hotels SET is_active = 1 WHERE id IN (" . str_repeat('?,', count($selected_hotels) - 1) . "?)");
                $stmt->execute($selected_hotels);
                $_SESSION['success'] = count($selected_hotels) . ' hotels activated successfully.';
                break;
                
            case 'deactivate':
                $stmt = $pdo->prepare("UPDATE hotels SET is_active = 0 WHERE id IN (" . str_repeat('?,', count($selected_hotels) - 1) . "?)");
                $stmt->execute($selected_hotels);
                $_SESSION['success'] = count($selected_hotels) . ' hotels deactivated successfully.';
                break;
                
            case 'delete':
                try {
                    $pdo->beginTransaction();
                    
                    // Delete images for each hotel
                    foreach ($selected_hotels as $hotel_id) {
                        $images = $gallery->getHotelImages($hotel_id);
                        foreach ($images as $image) {
                            $gallery->deleteImage($image['id'], $hotel_id);
                        }
                    }
                    
                    // Delete hotels
                    $stmt = $pdo->prepare("DELETE FROM hotels WHERE id IN (" . str_repeat('?,', count($selected_hotels) - 1) . "?)");
                    $stmt->execute($selected_hotels);
                    
                    $pdo->commit();
                    $_SESSION['success'] = count($selected_hotels) . ' hotels deleted successfully.';
                } catch (Exception $e) {
                    $pdo->rollBack();
                    error_log("Bulk delete error: " . $e->getMessage());
                    $_SESSION['error'] = 'An error occurred while deleting hotels.';
                }
                break;
        }
        
        header('Location: hotels.php');
        exit;
    }

    // Handle search
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $where = '';
    $params = [];
    
    if ($search) {
        $where = "WHERE h.name LIKE ? OR h.location LIKE ?";
        $params = ["%$search%", "%$search%"];
    }

    // Get hotels with their featured images
    try {
        // Test database connection
        $test_stmt = $pdo->query("SELECT 1");
        if (!$test_stmt) {
            throw new Exception("Database connection test failed");
        }

        // Get total count for pagination
        $count_query = "SELECT COUNT(*) FROM hotels h";
        if ($where) {
            $count_query .= " " . $where;
        }
        $count_stmt = $pdo->prepare($count_query);
        $count_stmt->execute($params);
        $total_hotels = $count_stmt->fetchColumn();

        // Set pagination variables
        $per_page = 10;
        $total_pages = ceil($total_hotels / $per_page);
        $current_page = isset($_GET['page']) ? max(1, min($total_pages, intval($_GET['page']))) : 1;
        $offset = ($current_page - 1) * $per_page;

        // Main hotels query
        $query = "SELECT h.id, h.name, h.location, h.price, h.rating, h.is_active, h.created_at, h.updated_at, h.image, h.description, COUNT(DISTINCT b.id) as booking_count
                  FROM hotels h
                  LEFT JOIN bookings b ON h.id = b.hotel_id";
        
        if ($where) {
            $query .= " " . $where;
        }
        
        $query .= " GROUP BY h.id ORDER BY h.name ASC LIMIT ? OFFSET ?";
        
        // Add pagination parameters
        $params[] = $per_page;
        $params[] = $offset;
        
        // Prepare and execute query
        $stmt = $pdo->prepare($query);
        if (!$stmt->execute($params)) {
            throw new Exception("Failed to execute query: " . implode(" ", $stmt->errorInfo()));
        }
        
        $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($hotels) && !$error) {
            if ($search) {
                $_SESSION['info'] = "No hotels found matching your search criteria.";
            } else {
                $_SESSION['info'] = "No hotels found in the database.";
            }
        }
    } catch (Exception $e) {
        error_log("[" . date('Y-m-d H:i:s') . "] Hotels list error: " . $e->getMessage());
        $hotels = [];
        $error = true;
        $_SESSION['error'] = "An error occurred while loading the hotels list. Please try again.";
        // Debug: Print SQL error to the page for development
        echo '<div style="color:red;font-weight:bold;">SQL Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }

    // Handle delete
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        if (!hasPermission('delete_hotel')) {
            $_SESSION['error'] = 'You do not have permission to delete hotels.';
        } else {
            $delete_id = intval($_POST['delete']);
            
            // Delete hotel images
            $images = $gallery->getHotelImages($delete_id);
            foreach ($images as $image) {
                $gallery->deleteImage($image['id'], $delete_id);
            }
            
            // Delete hotel
            $stmt = $pdo->prepare("DELETE FROM hotels WHERE id = ?");
            if ($stmt->execute([$delete_id])) {
                $_SESSION['success'] = 'Hotel deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete hotel.';
            }
            header('Location: hotels.php');
            exit;
        }
    }

    // Handle hotel update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_hotel'])) {
        $hotel_id = intval($_POST['hotel_id']);
        $name = trim($_POST['name'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $rating = intval($_POST['rating'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $errors = [];
        if ($name === '') $errors[] = 'Hotel name is required.';
        if ($location === '') $errors[] = 'Location is required.';
        if ($price <= 0) $errors[] = 'Valid price is required.';
        if ($rating < 1 || $rating > 5) $errors[] = 'Valid rating is required.';
        
        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE hotels SET name=?, location=?, price=?, rating=?, description=?, is_active=? WHERE id=?");
            if ($stmt->execute([$name, $location, $price, $rating, $description, $is_active, $hotel_id])) {
                $_SESSION['success'] = 'Hotel updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update hotel.';
            }
        } else {
            $_SESSION['error'] = implode('<br>', $errors);
        }
        header('Location: hotels.php');
        exit;
    }

    if (isset($_POST['bulk_delete'])) {
        $hotel_ids = $_POST['hotel_ids'] ?? [];
        if (!empty($hotel_ids)) {
            $placeholders = str_repeat('?,', count($hotel_ids) - 1) . '?';
            $stmt = $pdo->prepare("DELETE FROM hotels WHERE id IN ($placeholders)");
            $stmt->execute($hotel_ids);
            $_SESSION['success'] = 'Selected hotels deleted successfully';
        }
    }

} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Hotels list error: " . $e->getMessage() . "\n", 3, "../../logs/admin_errors.log");
    $_SESSION['error'] = "An error occurred while loading the hotels list.";
    $hotels = [];
    $error = true;
    // Debug: Print SQL error to the page for development
    echo '<div style="color:red;font-weight:bold;">SQL Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

$page_title = 'Hotels';
$page_actions = '';

ob_start();
?>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form action="" method="get" class="row g-2">
            <div class="col-md">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search hotels by name or location..." 
                           value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if ($search): ?>
                        <a href="hotels.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions Form -->
<form id="bulkActionsForm" method="post">
    <input type="hidden" name="action" id="bulkAction">
    
    <!-- Hotels List -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Hotels List</h5>
                </div>
                <div class="col-auto">
                    <div class="btn-group">
                        <select class="form-select form-select-sm" id="bulkActionSelect">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <button type="button" class="btn btn-secondary btn-sm" id="applyBulkAction">
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($hotels)): ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-hotel fa-3x mb-3"></i>
                    <p class="mb-0">No hotels found<?= $search ? ' matching your search criteria' : '' ?>.</p>
                    <?php if ($search): ?>
                        <a href="hotels.php" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-times"></i> Clear Search
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Bookings</th>
                                <th style="width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hotels as $hotel): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input hotel-checkbox" 
                                               name="selected_hotels[]" value="<?= $hotel['id'] ?>">
                                    </td>
                                    <td><?= htmlspecialchars($hotel['name']) ?></td>
                                    <td><?= htmlspecialchars($hotel['location']) ?></td>
                                    <td>EGP <?= number_format($hotel['price'], 2) ?></td>
                                    <td><?php 
                                        $rating = round(floatval($hotel['rating']));
                                        echo str_repeat('⭐', $rating);
                                    ?></td>
                                    <td>
                                        <span class="badge bg-<?= $hotel['is_active'] ? 'success' : 'danger' ?>">
                                            <?= $hotel['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($hotel['booking_count']) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="rooms.php?hotel_id=<?= $hotel['id'] ?>" class="btn btn-info btn-sm" title="Manage Rooms">
                                                <i class="fas fa-bed"></i>
                                            </a>
                                            <a href="features.php?hotel_id=<?= $hotel['id'] ?>" class="btn btn-success btn-sm" title="Manage Features">
                                                <i class="fas fa-star"></i>
                                            </a>
                                            <a href="policies.php?hotel_id=<?= $hotel['id'] ?>" class="btn btn-warning btn-sm" title="Manage Policies">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <a href="edit_hotel.php?id=<?= $hotel['id'] ?>" class="btn btn-warning btn-sm" title="Edit Hotel">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                    data-id="<?= $hotel['id'] ?>" data-name="<?= htmlspecialchars($hotel['name']) ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<?php if ($total_pages > 1): ?>
    <div class="card-footer">
        <nav>
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $current_page - 1 ?><?= $search ? "&search=$search" : '' ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                
                <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                    <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= $search ? "&search=$search" : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $current_page + 1 ?><?= $search ? "&search=$search" : '' ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
<?php endif; ?>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="hotel_id" id="editHotelId">
                    <div class="mb-3">
                        <label class="form-label">Hotel Name</label>
                        <input type="text" class="form-control" name="name" id="editHotelName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" name="location" id="editHotelLocation" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (EGP)</label>
                        <input type="number" class="form-control" name="price" id="editHotelPrice" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <select class="form-control" name="rating" id="editHotelRating" required>
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="editHotelDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="editHotelStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_hotel" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this hotel? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="delete" id="deleteHotelId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Add custom JavaScript
$extra_js = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Handle edit button click
    const editModal = document.getElementById("editModal");
    if (editModal) {
        editModal.addEventListener("show.bs.modal", function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute("data-id");
            const name = button.getAttribute("data-name");
            const location = button.getAttribute("data-location");
            const price = button.getAttribute("data-price");
            const rating = button.getAttribute("data-rating");
            const description = button.getAttribute("data-description");
            const status = button.getAttribute("data-status");

            const modal = this;
            modal.querySelector("#editHotelId").value = id;
            modal.querySelector("#editHotelName").value = name;
            modal.querySelector("#editHotelLocation").value = location;
            modal.querySelector("#editHotelPrice").value = price;
            modal.querySelector("#editHotelRating").value = rating;
            modal.querySelector("#editHotelDescription").value = description;
            modal.querySelector("#editHotelStatus").value = status;
        });
    }

    // Handle delete button click
    const deleteModal = document.getElementById("deleteModal");
    if (deleteModal) {
        deleteModal.addEventListener("show.bs.modal", function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute("data-id");
            const name = button.getAttribute("data-name");
            const modal = this;
            modal.querySelector("#deleteHotelId").value = id;
            modal.querySelector("#deleteModalLabel").textContent = "Delete Hotel: " + name;
        });
    }

    // Handle select all checkbox
    const selectAll = document.getElementById("selectAll");
    if (selectAll) {
        selectAll.addEventListener("change", function() {
            document.querySelectorAll(".hotel-checkbox").forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Handle bulk actions
    const applyBulkAction = document.getElementById("applyBulkAction");
    if (applyBulkAction) {
        applyBulkAction.addEventListener("click", function() {
            const bulkActionSelect = document.getElementById("bulkActionSelect");
            const bulkAction = document.getElementById("bulkAction");
            const bulkActionsForm = document.getElementById("bulkActionsForm");
            
            if (!bulkActionSelect || !bulkAction || !bulkActionsForm) return;
            
            const selectedAction = bulkActionSelect.value;
            if (!selectedAction) {
                alert("Please select an action");
                return;
            }

            const selectedHotels = document.querySelectorAll(".hotel-checkbox:checked");
            if (selectedHotels.length === 0) {
                alert("Please select at least one hotel");
                return;
            }

            if (selectedAction === "delete" && !confirm("Are you sure you want to delete the selected hotels? This action cannot be undone.")) {
                return;
            }

            bulkAction.value = selectedAction;
            bulkActionsForm.submit();
        });
    }
});
</script>
';

require_once 'templates/admin_layout.php';
?> 