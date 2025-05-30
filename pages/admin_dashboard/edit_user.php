<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit;
}
// checkAdminLogin(); // تم التعطيل بناءً على طلب الإدارة
require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../classes/User.php';

$page_title = 'Edit User';
$page_actions = '<a href="users.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Users</a>';

$errors = [];
$success = '';

try {
    $pdo = getPDO();
    $userManager = new User($pdo);
    $roles = $userManager->getRoles();

    if (!isset($_GET['id'])) {
        throw new Exception('User ID is required.');
    }
    $userId = intval($_GET['id']);
    $user = $userManager->getById($userId);
    if (!$user) {
        throw new Exception('User not found.');
    }

    $name = $user['name'];
    $email = $user['email'];
    // جلب أدوار المستخدم من جدول user_roles/roles
    $userRoles = [];
    $stmt = $pdo->prepare("SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?");
    $stmt->execute([$userId]);
    $userRoles = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $role = $userRoles[0] ?? '';
    $status = $user['status'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        $status = isset($_POST['status']) ? 1 : 0;

        if ($name === '') $errors[] = 'Name is required.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if ($role === '' || !in_array($role, array_column($roles, 'name'))) $errors[] = 'Valid role is required.';

        if (empty($errors)) {
            if ($userManager->emailExists($email, $userId)) {
                $errors[] = 'Email already exists.';
            } else {
                $userManager->updateUser($userId, $name, $email, $password, $role, $status);
                $_SESSION['success'] = 'User updated successfully!';
                header('Location: users.php');
                exit;
            }
        }
    }
} catch (Exception $e) {
    $errors[] = 'An error occurred: ' . $e->getMessage();
}

ob_start();
?>
<div class="card">
    <div class="card-header"><h5 class="card-title mb-0">Edit User</h5></div>
    <div class="card-body">
        <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', $errors) ?></div><?php endif; ?>
        <form action="" method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                <div class="invalid-feedback">Please enter a name.</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (leave blank to keep current)</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6">
                <div class="invalid-feedback">Password must be at least 6 characters if changed.</div>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Select Role</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= htmlspecialchars($r['name']) ?>" <?= $role === $r['name'] ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($r['name'])) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please select a role.</div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" <?= $status ? 'checked' : '' ?>>
                <label class="form-check-label" for="status">Active</label>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="users.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/templates/admin_layout.php'; 