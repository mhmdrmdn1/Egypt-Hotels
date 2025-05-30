<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    // header('Location: admin_login.php'); // تم التعطيل بناءً على طلب الإدارة
    exit;
}
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = (int)$_POST['id'];
    if ($_POST['action'] === 'approve') {
        $pdo->prepare("UPDATE user_gallery SET approved = 1 WHERE id = ?")->execute([$id]);
    } elseif ($_POST['action'] === 'reject') {
        $pdo->prepare("UPDATE user_gallery SET approved = 2 WHERE id = ?")->execute([$id]);
    }
    header('Location: admin_user_gallery.php');
    exit;
}

$images = $pdo->query("SELECT * FROM user_gallery WHERE approved = 0 ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | User Gallery Review</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/book.css">
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f8fafc; min-height: 100vh; }
        .admin-container { max-width: 1100px; margin: 2rem auto; padding: 0 2rem; }
        .gallery-review-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .gallery-review-table th, .gallery-review-table td { padding: 1rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .gallery-review-table th { background: #f9fafb; font-weight: 600; color: #374151; }
        .gallery-review-table tr:last-child td { border-bottom: none; }
        .user-img-thumb { width: 120px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; }
        .action-btn { padding: 0.5rem 1.2rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; margin-right: 0.5rem; }
        .approve-btn { background: #22c55e; color: #fff; }
        .approve-btn:hover { background: #16a34a; }
        .reject-btn { background: #ef4444; color: #fff; }
        .reject-btn:hover { background: #b91c1c; }
        .caption-cell { max-width: 300px; white-space: pre-line; }
        .no-images { text-align: center; color: #6b7280; padding: 2rem; }

        .admin-header {
            background: #fff;
            padding: 1rem 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }
        .admin-title {
            color: #2563eb;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .admin-nav-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            padding: 0.5rem 1rem;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-title">Egypt Hotels Admin</div>
            <div class="admin-nav-right">
                <form action="index.php" method="POST">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </nav>
    </header>
    <main class="admin-container">
        <h2 class="section-title" style="margin-bottom:2rem;">User Gallery Review</h2>
        <?php if (count($images) === 0): ?>
            <div class="no-images">No new images for review currently.</div>
        <?php else: ?>
        <table class="gallery-review-table">
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
                    <td><img class="user-img-thumb" src="../assets/user_uploads/<?php echo htmlspecialchars($img['image']); ?>" alt="User Photo"></td>
                    <td class="caption-cell"><?php echo nl2br(htmlspecialchars($img['caption'])); ?></td>
                    <td>User</td>
                    <td><?php echo htmlspecialchars($img['created_at']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $img['id']; ?>">
                            <button type="submit" name="action" value="approve" class="action-btn approve-btn">Approve</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $img['id']; ?>">
                            <button type="submit" name="action" value="reject" class="action-btn reject-btn">Reject</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </main>
</body>
</html> 