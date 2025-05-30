<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','manager'])) {
    header('Location: unauthorized.php');
    exit;
}
require_once '../../config/database.php';

$pdo = getPDO();

// إنشاء جدول hotel_policies إذا لم يكن موجوداً
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS hotel_policies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        hotel_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        type ENUM('check-in', 'check-out', 'cancellation', 'payment', 'other') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
    )");
} catch (PDOException $e) {
    error_log("Error creating hotel_policies table: " . $e->getMessage());
    $_SESSION['error'] = "Error setting up policies table. Please contact administrator.";
    header("Location: hotels.php");
    exit;
}

$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0;
if (!$hotel_id) {
    $_SESSION['error'] = 'Hotel ID is required.';
    header('Location: hotels.php');
    exit;
}

// جلب بيانات الفندق
$stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$hotel) {
    $_SESSION['error'] = 'Hotel not found.';
    header('Location: hotels.php');
    exit;
}

// إضافة سياسة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_policy'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $errors = [];
    if ($title === '') $errors[] = 'Policy title is required.';
    if ($type === '') $errors[] = 'Policy type is required.';
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO hotel_policies (hotel_id, title, description, type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$hotel_id, $title, $description, $type]);
        $_SESSION['success'] = 'Policy added successfully!';
        header("Location: policies.php?hotel_id=$hotel_id");
        exit;
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

// حذف سياسة
if (isset($_POST['delete_policy'])) {
    $policy_id = intval($_POST['delete_policy']);
    $stmt = $pdo->prepare("DELETE FROM hotel_policies WHERE id = ? AND hotel_id = ?");
    $stmt->execute([$policy_id, $hotel_id]);
    $_SESSION['success'] = 'Policy deleted successfully!';
    header("Location: policies.php?hotel_id=$hotel_id");
    exit;
}

// تعديل سياسة
if (isset($_POST['edit_policy_id'])) {
    $edit_policy_id = intval($_POST['edit_policy_id']);
    $stmt = $pdo->prepare("SELECT * FROM hotel_policies WHERE id = ? AND hotel_id = ?");
    $stmt->execute([$edit_policy_id, $hotel_id]);
    $edit_policy = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$edit_policy) {
        $_SESSION['error'] = 'Policy not found.';
        header("Location: policies.php?hotel_id=$hotel_id");
        exit;
    }
}

// حفظ التعديلات
if (isset($_POST['update_policy'])) {
    $policy_id = intval($_POST['policy_id']);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $errors = [];
    if ($title === '') $errors[] = 'Policy title is required.';
    if ($type === '') $errors[] = 'Policy type is required.';
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE hotel_policies SET title=?, description=?, type=? WHERE id=? AND hotel_id=?");
        $stmt->execute([$title, $description, $type, $policy_id, $hotel_id]);
        $_SESSION['success'] = 'Policy updated successfully!';
        header("Location: policies.php?hotel_id=$hotel_id");
        exit;
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
        $edit_policy = [
            'id' => $policy_id,
            'title' => $title,
            'description' => $description,
            'type' => $type
        ];
    }
}

// جلب جميع السياسات
$stmt = $pdo->prepare("SELECT * FROM hotel_policies WHERE hotel_id = ? ORDER BY type, id DESC");
$stmt->execute([$hotel_id]);
$policies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Manage Policies for ' . htmlspecialchars($hotel['name']);
ob_start();
?>
<div class="container py-4">
    <h3 class="mb-4">Manage Policies for <span class="text-primary"><?= htmlspecialchars($hotel['name']) ?></span></h3>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
    <?php endif; ?>
    <form method="post" class="mb-4">
        <?php $is_edit = isset($edit_policy); ?>
        <?php if ($is_edit): ?>
            <input type="hidden" name="policy_id" value="<?= $edit_policy['id'] ?>">
        <?php endif; ?>
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Policy Title</label>
                <input type="text" name="title" class="form-control" required value="<?= $is_edit ? htmlspecialchars($edit_policy['title']) : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="1"><?= $is_edit ? htmlspecialchars($edit_policy['description']) : '' ?></textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label">Policy Type</label>
                <select name="type" class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="check-in" <?= $is_edit && $edit_policy['type'] === 'check-in' ? 'selected' : '' ?>>Check-in</option>
                    <option value="check-out" <?= $is_edit && $edit_policy['type'] === 'check-out' ? 'selected' : '' ?>>Check-out</option>
                    <option value="cancellation" <?= $is_edit && $edit_policy['type'] === 'cancellation' ? 'selected' : '' ?>>Cancellation</option>
                    <option value="payment" <?= $is_edit && $edit_policy['type'] === 'payment' ? 'selected' : '' ?>>Payment</option>
                    <option value="other" <?= $is_edit && $edit_policy['type'] === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <?php if ($is_edit): ?>
                    <button type="submit" name="update_policy" class="btn btn-primary">Update</button>
                    <a href="policies.php?hotel_id=<?= $hotel_id ?>" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add_policy" class="btn btn-success">Add</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-header"><b>Policies List</b></div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($policies as $policy): ?>
                        <tr>
                            <td>
                                <span class="badge bg-<?= $policy['type'] === 'check-in' ? 'success' : 
                                    ($policy['type'] === 'check-out' ? 'info' : 
                                    ($policy['type'] === 'cancellation' ? 'warning' : 
                                    ($policy['type'] === 'payment' ? 'primary' : 'secondary'))) ?>">
                                    <?= ucfirst(htmlspecialchars($policy['type'])) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($policy['title']) ?></td>
                            <td><?= htmlspecialchars($policy['description']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="edit_policy_id" value="<?= $policy['id'] ?>">
                                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                </form>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Delete this policy?');">
                                    <input type="hidden" name="delete_policy" value="<?= $policy['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <a href="hotels.php" class="btn btn-secondary mt-4"><i class="fas fa-arrow-left"></i> Back to Hotels</a>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/templates/admin_layout.php'; 