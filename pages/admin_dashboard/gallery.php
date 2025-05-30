<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','editor'])) {
    header('Location: unauthorized.php');
    exit;
}
require_once '../../config/database.php';
require_once '../../config/auth.php';
require_once '../../classes/User.php';

// Check admin login
// checkAdminLogin(); // تم التعطيل بناءً على طلب الإدارة

try {
    $pdo = getPDO();
    
    // Check permission using User class
    $userManager = new User($pdo, $_SESSION['admin_id'] ?? 0);
    if (!$userManager->hasPermission('manage_gallery')) {
        $_SESSION['error'] = 'You do not have permission to manage the gallery.';
        header('Location: index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
        $id = (int)$_POST['id'];
        if ($_POST['action'] === 'approve') {
            $pdo->prepare("UPDATE user_gallery SET approved = 1 WHERE id = ?")->execute([$id]);
        } elseif ($_POST['action'] === 'reject') {
            $pdo->prepare("UPDATE user_gallery SET approved = 2 WHERE id = ?")->execute([$id]);
        } elseif ($_POST['action'] === 'delete') {
            // حذف الصورة من قاعدة البيانات وحذف الملف من السيرفر
            $stmt = $pdo->prepare("SELECT image FROM user_gallery WHERE id = ?");
            $stmt->execute([$id]);
            $img = $stmt->fetch();
            if ($img) {
                $img_path = __DIR__ . '/../../assets/user_uploads/' . $img['image'];
                if (file_exists($img_path)) {
                    unlink($img_path);
                }
                $pdo->prepare("DELETE FROM user_gallery WHERE id = ?")->execute([$id]);
            }
        }
        header('Location: gallery.php');
        exit;
    }

    $images = $pdo->query("SELECT ug.*, u.name as user_name 
                       FROM user_gallery ug 
                       LEFT JOIN users u ON ug.user_id = u.id 
                       WHERE ug.approved = 0 
                       ORDER BY ug.created_at DESC")->fetchAll();

    // جلب الصور المعتمدة
    $approved_images = $pdo->query("SELECT ug.*, u.name as user_name FROM user_gallery ug LEFT JOIN users u ON ug.user_id = u.id WHERE ug.approved = 1 ORDER BY ug.created_at DESC")->fetchAll();

    $page_title = 'Gallery Management';
    ob_start();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gallery Review</h1>
    </div>

    <?php if (count($images) === 0): ?>
        <div class="alert alert-info">No new images for review currently.</div>
    <?php else: ?>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Caption</th>
                                <th>User</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($images as $img): ?>
                            <tr>
                                <td>
                                    <img class="img-thumbnail" style="max-width: 150px;" 
                                         src="../../assets/user_uploads/<?php echo htmlspecialchars($img['image']); ?>" 
                                         alt="User Photo">
                                </td>
                                <td><?php echo nl2br(htmlspecialchars($img['caption'])); ?></td>
                                <td><?php echo htmlspecialchars($img['user_name']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($img['created_at'])); ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $img['id']; ?>">
                                        <button type="submit" name="action" value="approve" 
                                                class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $img['id']; ?>">
                                        <button type="submit" name="action" value="reject" 
                                                class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (count($approved_images) > 0): ?>
        <div class="card shadow mb-4">
            <div class="card-header"><strong>All Approved User Gallery Images</strong></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Caption</th>
                                <th>User</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($approved_images as $img): ?>
                            <tr>
                                <td>
                                    <img class="img-thumbnail" style="max-width: 150px;" 
                                         src="../../assets/user_uploads/<?php echo htmlspecialchars($img['image']); ?>" 
                                         alt="User Photo">
                                </td>
                                <td><?php echo nl2br(htmlspecialchars($img['caption'])); ?></td>
                                <td><?php echo htmlspecialchars($img['user_name']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($img['created_at'])); ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $img['id']; ?>">
                                        <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once 'templates/admin_layout.php';
} catch (Exception $e) {
    error_log("Gallery page error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while loading the gallery page.";
    header('Location: index.php');
    exit;
}
?> 