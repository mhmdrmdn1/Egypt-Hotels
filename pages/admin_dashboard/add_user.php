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

$page_title = 'Add User';
$page_actions = '<a href="users.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Users</a>';

// Add CSS file
$page_css = ['assets/css/admin/add_user.css'];
$errors = [];
$success = '';
$firstname = $lastname = $email = $username = $role = $status = '';

try {
    $pdo = getPDO();
    $userManager = new User($pdo);
    $roles = $userManager->getRoles();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstname = trim($_POST['firstname'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        $status = isset($_POST['status']) ? 1 : 0;

        if ($firstname === '') $errors[] = 'First name is required.';
        if ($lastname === '') $errors[] = 'Last name is required.';
        if ($username === '' || !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) $errors[] = 'Valid username is required (3-20 letters, numbers, underscores).';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if ($password === '' || strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        
        // تعريف الأدوار المسموح بها
        $allowed_roles = ['user', 'admin', 'manager', 'staff', 'editor'];
        if ($role === '' || !in_array($role, $allowed_roles)) {
            $errors[] = 'Please select a valid user type.';
        }

        if (empty($errors)) {
            if ($userManager->emailExists($email)) {
                $errors[] = 'Email already exists.';
            } else if ($userManager->usernameExists && $userManager->usernameExists($username)) {
                $errors[] = 'Username already exists.';
            } else {
                // إضافة المستخدم في جدول users
                $userManager->addUser($firstname . ' ' . $lastname, $email, $password, $role, $status, $username);
                
                // الحصول على معرف المستخدم المضاف حديثاً
                $user_id = $pdo->lastInsertId();
                
                // إضافة الدور في جدول user_roles لكل المستخدمين
                try {
                    $roleStmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) SELECT ?, id FROM roles WHERE name = ?");
                    $roleStmt->execute([$user_id, $role]);
                } catch (PDOException $e) {
                    error_log("Error adding user role: " . $e->getMessage());
                    $errors[] = 'Error adding user role. Please try again.';
                }
                
                // إضافة المستخدم في جدول admins إذا كان من الأدوار الإدارية
                $admin_roles = ['admin', 'manager', 'staff', 'editor'];
                if (in_array($role, $admin_roles)) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO admins (user_id, username, role, status) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$user_id, $username, $role, $status]);
                    } catch (PDOException $e) {
                        error_log("Error adding admin user: " . $e->getMessage());
                        $errors[] = 'Error adding admin privileges. Please try again.';
                    }
                }
                
                $_SESSION['success'] = 'User added successfully!';
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
<div class="container py-4">
    <div class="card add-user-card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
        </div>
        <div class="card-body">
            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= implode('<br>', $errors) ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="post" class="needs-validation" novalidate id="addUserForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($firstname) ?>" required placeholder="Enter first name">
                        </div>
                        <div class="invalid-feedback">Please enter a first name.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="lastname" name="lastname" value="<?= htmlspecialchars($lastname) ?>" required placeholder="Enter last name">
                        </div>
                        <div class="invalid-feedback">Please enter a last name.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required placeholder="Enter username">
                        </div>
                        <div class="invalid-feedback">Please enter a valid username (3-20 letters, numbers, underscores).</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Enter email address">
                        </div>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Enter password">
                        </div>
                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Confirm password">
                        </div>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="manager" <?= $role === 'manager' ? 'selected' : '' ?>>Manager</option>
                                <option value="staff" <?= $role === 'staff' ? 'selected' : '' ?>>Staff</option>
                                <option value="editor" <?= $role === 'editor' ? 'selected' : '' ?>>Editor</option>
                            </select>
                        </div>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Account Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" <?= $status ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status">
                                <span class="badge bg-<?= $status ? 'success' : 'secondary' ?>">
                                    <?= $status ? 'Active' : 'Inactive' ?>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="users.php" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addUserForm');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const statusSwitch = document.getElementById('status');
    const statusLabel = statusSwitch.nextElementSibling.querySelector('.badge');

    // Password validation
    form.addEventListener('submit', function(e) {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
            confirmPassword.classList.add('is-invalid');
            e.preventDefault();
        } else {
            confirmPassword.setCustomValidity('');
            confirmPassword.classList.remove('is-invalid');
        }
    });

    // Status switch styling
    statusSwitch.addEventListener('change', function() {
        if (this.checked) {
            statusLabel.className = 'badge bg-success';
            statusLabel.textContent = 'Active';
        } else {
            statusLabel.className = 'badge bg-secondary';
            statusLabel.textContent = 'Inactive';
        }
    });

    // Form validation
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/templates/admin_layout.php'; 