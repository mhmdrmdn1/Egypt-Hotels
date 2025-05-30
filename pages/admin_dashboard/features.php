<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','manager'])) {
    header('Location: unauthorized.php');
    exit;
}
require_once '../../config/database.php';

$pdo = getPDO();

// إنشاء جدول hotel_features إذا لم يكن موجوداً
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS hotel_features (
        id INT AUTO_INCREMENT PRIMARY KEY,
        hotel_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        icon VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
    )");
} catch (PDOException $e) {
    error_log("Error creating hotel_features table: " . $e->getMessage());
    $_SESSION['error'] = "Error setting up features table. Please contact administrator.";
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

// إضافة مميزة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_feature'])) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $errors = [];
    if ($name === '') $errors[] = 'Feature name is required.';
    if ($icon === '') $errors[] = 'Icon is required.';
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO hotel_features (hotel_id, name, description, icon) VALUES (?, ?, ?, ?)");
        $stmt->execute([$hotel_id, $name, $description, $icon]);
        $_SESSION['success'] = 'Feature added successfully!';
        header("Location: features.php?hotel_id=$hotel_id");
        exit;
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

// حذف مميزة
if (isset($_POST['delete_feature'])) {
    $feature_id = intval($_POST['delete_feature']);
    $stmt = $pdo->prepare("DELETE FROM hotel_features WHERE id = ? AND hotel_id = ?");
    $stmt->execute([$feature_id, $hotel_id]);
    $_SESSION['success'] = 'Feature deleted successfully!';
    header("Location: features.php?hotel_id=$hotel_id");
    exit;
}

// تعديل مميزة
if (isset($_POST['edit_feature_id'])) {
    $edit_feature_id = intval($_POST['edit_feature_id']);
    $stmt = $pdo->prepare("SELECT * FROM hotel_features WHERE id = ? AND hotel_id = ?");
    $stmt->execute([$edit_feature_id, $hotel_id]);
    $edit_feature = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$edit_feature) {
        $_SESSION['error'] = 'Feature not found.';
        header("Location: features.php?hotel_id=$hotel_id");
        exit;
    }
}

// حفظ التعديلات
if (isset($_POST['update_feature'])) {
    $feature_id = intval($_POST['feature_id']);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $errors = [];
    if ($name === '') $errors[] = 'Feature name is required.';
    if ($icon === '') $errors[] = 'Icon is required.';
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE hotel_features SET name=?, description=?, icon=? WHERE id=? AND hotel_id=?");
        $stmt->execute([$name, $description, $icon, $feature_id, $hotel_id]);
        $_SESSION['success'] = 'Feature updated successfully!';
        header("Location: features.php?hotel_id=$hotel_id");
        exit;
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
        $edit_feature = [
            'id' => $feature_id,
            'name' => $name,
            'description' => $description,
            'icon' => $icon
        ];
    }
}

// جلب جميع المميزات
$stmt = $pdo->prepare("SELECT * FROM hotel_features WHERE hotel_id = ? ORDER BY id DESC");
$stmt->execute([$hotel_id]);
$features = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Manage Features for ' . htmlspecialchars($hotel['name']);
ob_start();
?>
<div class="container py-4">
    <h3 class="mb-4">Manage Features for <span class="text-primary"><?= htmlspecialchars($hotel['name']) ?></span></h3>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
    <?php endif; ?>
    <form method="post" class="mb-4">
        <?php $is_edit = isset($edit_feature); ?>
        <?php if ($is_edit): ?>
            <input type="hidden" name="feature_id" value="<?= $edit_feature['id'] ?>">
        <?php endif; ?>
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Feature Name</label>
                <input type="text" name="name" class="form-control" required value="<?= $is_edit ? htmlspecialchars($edit_feature['name']) : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control" value="<?= $is_edit ? htmlspecialchars($edit_feature['description']) : '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Icon (FontAwesome class)</label>
                <small class="text-muted">Example: fas fa-wifi</small>
                <input type="text" name="icon" class="form-control" required value="<?= $is_edit ? htmlspecialchars($edit_feature['icon']) : '' ?>">
            </div>
            <div class="col-md-2">
                <?php if ($is_edit): ?>
                    <button type="submit" name="update_feature" class="btn btn-primary">Update</button>
                    <a href="features.php?hotel_id=<?= $hotel_id ?>" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add_feature" class="btn btn-success">Add</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-header"><b>Features List</b></div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($features as $feature): ?>
                        <tr>
                            <td><i class="<?= htmlspecialchars($feature['icon']) ?>"></i></td>
                            <td><?= htmlspecialchars($feature['name']) ?></td>
                            <td><?= htmlspecialchars($feature['description']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="edit_feature_id" value="<?= $feature['id'] ?>">
                                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                </form>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Delete this feature?');">
                                    <input type="hidden" name="delete_feature" value="<?= $feature['id'] ?>">
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