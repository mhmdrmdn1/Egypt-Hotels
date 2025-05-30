<?php
session_start();
require_once '../../config/database.php';

// Check if user is logged in and is admin, manager, or editor
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'manager', 'editor'])) {
    header('Location: unauthorized.php');
    exit();
}

try {
    $pdo = getPDO();

    // Handle status updates
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id']) && isset($_POST['status'])) {
        $message_id = $_POST['message_id'];
        $status = $_POST['status'];
        
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        $stmt->execute([$status, $message_id]);
        
        header('Location: messages.php');
        exit();
    }

    // Fetch messages
    $query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
    $stmt = $pdo->query($query);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set page title
    $page_title = 'Contact Messages';

    // Start output buffering
    ob_start();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact Messages</h5>
                </div>
                <div class="card-body">
                    <?php if (count($messages) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $row['status'] === 'pending' ? 'warning' : 
                                                        ($row['status'] === 'in_progress' ? 'info' : 'success'); 
                                                ?>">
                                                    <?php echo ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="in_progress" <?php echo $row['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                                        <option value="resolved" <?php echo $row['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            No messages found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $content = ob_get_clean();

    // Include the admin layout template
    require_once 'templates/admin_layout.php';
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while accessing the database.";
    header('Location: index.php');
    exit();
}
?> 