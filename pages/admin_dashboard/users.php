<?php
session_start();
// تحديث التحقق من الأدوار المسموح بها
$allowed_admin_roles = ['admin', 'manager'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_admin_roles)) {
    header('Location: unauthorized.php');
    exit;
}

require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../classes/User.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    // header('Location: ../admin_login.php'); // تم التعطيل بناءً على طلب الإدارة
    exit;
}

$page_title = 'Users Management';
$page_actions = '';

// تعريف الأدوار المسموح بها
$allowed_roles = ['user', 'admin', 'manager', 'staff', 'editor'];

// Initialize variables with default values
$filters = [
    'status' => $_GET['status'] ?? '',
    'role' => $_GET['role'] ?? '',
    'search' => $_GET['search'] ?? ''
];

$sort = [
    'field' => $_GET['sort'] ?? 'created_at',
    'direction' => $_GET['direction'] ?? 'desc'
];

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;

// Initialize empty result sets
$users = [];
$total_pages = 0;
$roles = [];

// Initialize stats with default values
$stats = [
    'total' => 0,
    'active' => 0,
    'disabled' => 0,
    'by_role' => []
];

try {
    $pdo = getPDO();
    $userManager = new User($pdo);

    // Get users with filters and sorting
    $result = $userManager->getAll($filters, $sort, $page, $limit);
    $users = $result['users'];
    $total_pages = $result['pages'];

    // Get user statistics
    $stats = $userManager->getStats();

    // Get all roles for filter dropdown
    $roles = $userManager->getRoles();

    // Handle status toggle
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
        if (!userHasPermission($_SESSION['admin_id'], 'edit_user', $pdo)) {
            $_SESSION['error'] = 'You do not have permission to edit users.';
        } else {
            $user_id = intval($_POST['toggle_status']);
            $stmt = $pdo->prepare("UPDATE users SET status = NOT status WHERE id = ?");
            if ($stmt->execute([$user_id])) {
                $_SESSION['success'] = 'User status updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update user status.';
            }
        }
        header('Location: users.php');
        exit;
    }

    // Handle delete user
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
        $user_id = intval($_POST['delete_user']);
        
        // المدير يمكنه حذف أي مستخدم
        if ($_SESSION['role'] === 'admin' || userHasPermission($_SESSION['admin_id'], 'delete_user', $pdo)) {
            try {
                $pdo->beginTransaction();
                
                // التحقق من وجود المستخدم
                $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                if (!$stmt->fetch()) {
                    throw new Exception("User not found");
                }
                
                // حذف من جدول user_roles
                $stmt = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
                if (!$stmt->execute([$user_id])) {
                    throw new Exception("Failed to delete user roles");
                }
                
                // حذف من جدول admins إذا كان موجوداً
                $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
                if (!$stmt->execute([$user_id])) {
                    throw new Exception("Failed to delete admin record");
                }
                
                // حذف من جدول users
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                if (!$stmt->execute([$user_id])) {
                    throw new Exception("Failed to delete user");
                }
                
                $pdo->commit();
                $_SESSION['success'] = 'User deleted successfully.';
                
                // تسجيل العملية في ملف السجل
                error_log("[" . date('Y-m-d H:i:s') . "] User deleted successfully. User ID: " . $user_id . "\n", 3, "../../logs/admin_actions.log");
                
            } catch (Exception $e) {
                $pdo->rollBack();
                error_log("[" . date('Y-m-d H:i:s') . "] Error deleting user: " . $e->getMessage() . "\n", 3, "../../logs/admin_errors.log");
                $_SESSION['error'] = 'Failed to delete user: ' . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = 'You do not have permission to delete users.';
            error_log("[" . date('Y-m-d H:i:s') . "] Unauthorized delete attempt. User ID: " . $_SESSION['admin_id'] . "\n", 3, "../../logs/admin_errors.log");
        }
        header('Location: users.php');
        exit;
    }

} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Users list error: " . $e->getMessage() . "\n", 3, "../../logs/admin_errors.log");
    $_SESSION['error'] = "An error occurred while loading the users list.";
}

ob_start();
?>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Users -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Total Users</h6>
                        <h4 class="mb-0"><?= number_format($stats['total']) ?></h4>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Active Users</h6>
                        <h4 class="mb-0"><?= number_format($stats['active']) ?></h4>
                        <small class="text-success">
                            <?= round(($stats['active'] / max(1, $stats['total'])) * 100) ?>% of total
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-check fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disabled Users -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Disabled Users</h6>
                        <h4 class="mb-0"><?= number_format($stats['disabled']) ?></h4>
                        <small class="text-danger">
                            <?= round(($stats['disabled'] / max(1, $stats['total'])) * 100) ?>% of total
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-slash fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users by Role -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Users by Role</h6>
                <?php foreach ($stats['by_role'] as $role => $count): ?>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-grow-1">
                            <small class="text-capitalize"><?= htmlspecialchars($role) ?></small>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary"><?= number_format($count) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
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
                           placeholder="Search users..." 
                           value="<?= htmlspecialchars($filters['search']) ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-md-3">
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="disabled" <?= $filters['status'] === 'disabled' ? 'selected' : '' ?>>Disabled</option>
                </select>
            </div>

            <!-- Role Filter -->
            <div class="col-md-3">
                <select class="form-select" name="role" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <?php foreach ($allowed_roles as $role): ?>
                        <option value="<?= $role ?>" <?= $filters['role'] === $role ? 'selected' : '' ?>>
                            <?= ucfirst($role) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Clear Filters -->
            <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['role'])): ?>
                <div class="col-md-2">
                    <a href="?<?= http_build_query(['sort' => $sort['field'], 'direction' => $sort['direction']]) ?>" 
                       class="btn btn-secondary w-100">
                        Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Users List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Users List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'name', 'direction' => $sort['field'] === 'name' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>" 
                               class="text-decoration-none text-dark">
                                Name
                                <?php if ($sort['field'] === 'name'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'email', 'direction' => $sort['field'] === 'email' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>"
                               class="text-decoration-none text-dark">
                                Email
                                <?php if ($sort['field'] === 'email'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Roles</th>
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
                            <a href="?<?= http_build_query(array_merge($filters, ['sort' => 'created_at', 'direction' => $sort['field'] === 'created_at' && $sort['direction'] === 'asc' ? 'desc' : 'asc'])) ?>"
                               class="text-decoration-none text-dark">
                                Registered
                                <?php if ($sort['field'] === 'created_at'): ?>
                                    <i class="fas fa-sort-<?= $sort['direction'] === 'asc' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Last Login</th>
                        <th style="width: 100px">Actions</th>
            </tr>
        </thead>
        <tbody>
                    <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <?php 
                    $roles = !empty($user['roles']) ? explode(',', $user['roles']) : [];
                    foreach ($roles as $role): 
                        if (trim($role) && in_array(trim($role), $allowed_roles)):
                            $roleClass = match(trim($role)) {
                                'admin' => 'danger',
                                'manager' => 'warning',
                                'staff' => 'info',
                                'editor' => 'primary',
                                default => 'secondary'
                            };
                    ?>
                        <span class="badge bg-<?= $roleClass ?>"><?= ucfirst(htmlspecialchars(trim($role))) ?></span>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </td>
                <td>
                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <?= $user['last_login'] ? date('M j, Y H:i', strtotime($user['last_login'])) : 'Never' ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_user.php?id=<?= $user['id'] ?>" 
                                       class="btn btn-primary"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($user['id'] !== $_SESSION['admin_id'] && ($_SESSION['role'] === 'admin' || userHasPermission($_SESSION['admin_id'], 'delete_user', $pdo))): ?>
                                        <form action="" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            <input type="hidden" name="delete_user" value="<?= $user['id'] ?>">
                                            <button type="submit" class="btn btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form action="" method="post">
                    <input type="hidden" name="delete" id="deleteUserId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
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

.badge {
    font-weight: 500;
}
</style>
';

// Add custom JavaScript
$extra_js = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Handle user deletion
    document.querySelectorAll(".delete-user").forEach(btn => {
        btn.addEventListener("click", function() {
            if (!confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
                return;
            }
            
            const userId = this.dataset.id;
            const row = this.closest("tr");
            
            fetch("actions/delete_user.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                } else {
                    alert(data.error || "Failed to delete user");
                }
            });
        });
    });
});
</script>
';

require_once __DIR__ . '/templates/admin_layout.php';
?> 