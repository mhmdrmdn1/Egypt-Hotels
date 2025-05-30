<?php
$page_title = 'View Booking';

// Start output buffering
ob_start();

require_once '../../config/database.php';
require_once '../../classes/User.php';

// Initialize database connection
$pdo = getPDO();

// Check permission
// $userManager = new User($pdo, $_SESSION['admin_id']);
// if (!$userManager->hasPermission('view_bookings')) {
//     header('Location: index.php');
//     exit;
// }

// Get booking ID from URL
$booking_id = $_GET['id'] ?? 0;

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'update_status':
            $new_status = $_POST['status'] ?? '';
            if (!empty($new_status)) {
                $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
                $stmt->execute([$new_status, $booking_id]);
                $_SESSION['success'] = 'Booking status updated successfully.';
                header('Location: view_booking.php?id=' . $booking_id);
                exit;
            }
            break;
            
        case 'add_note':
            $note = $_POST['note'] ?? '';
            if (!empty($note)) {
                $stmt = $pdo->prepare("
                    INSERT INTO booking_notes (booking_id, admin_id, note) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$booking_id, $_SESSION['admin_id'], $note]);
                $_SESSION['success'] = 'Note added successfully.';
                header('Location: view_booking.php?id=' . $booking_id);
                exit;
            }
            break;
    }
}

// Get booking details
$stmt = $pdo->prepare("
    SELECT b.*, h.name as hotel_name, h.location as hotel_location,
           r.name as room_name, r.price as room_price,
           u.username, u.email, u.phone
    FROM bookings b
    JOIN hotels h ON b.hotel_id = h.id
    JOIN rooms r ON b.room_id = r.id
    JOIN users u ON b.user_id = u.id
    WHERE b.id = ?
");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    $_SESSION['error'] = 'Booking not found.';
    header('Location: bookings.php');
    exit;
}

// Get booking notes
$stmt = $pdo->prepare("
    SELECT bn.*, a.username as admin_name
    FROM booking_notes bn
    JOIN admins a ON bn.admin_id = a.id
    WHERE bn.booking_id = ?
    ORDER BY bn.created_at DESC
");
$stmt->execute([$booking_id]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total nights and price
$check_in = new DateTime($booking['check_in']);
$check_out = new DateTime($booking['check_out']);
$nights = $check_in->diff($check_out)->days;
$total_price = $booking['room_price'] * $nights;
?>

<div class="row">
    <!-- Booking Details -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Booking #<?= $booking['id'] ?></h5>
                <span class="badge <?= $booking['status'] === 'confirmed' ? 'bg-success' : ($booking['status'] === 'pending' ? 'bg-warning' : 'bg-danger') ?>">
                    <?= ucfirst($booking['status']) ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Hotel Information</h6>
                        <p class="mb-1"><strong>Hotel:</strong> <?= htmlspecialchars($booking['hotel_name']) ?></p>
                        <p class="mb-1"><strong>Location:</strong> <?= htmlspecialchars($booking['hotel_location']) ?></p>
                        <p class="mb-1"><strong>Room:</strong> <?= htmlspecialchars($booking['room_name']) ?></p>
                        <p class="mb-0"><strong>Price per night:</strong> $<?= number_format($booking['room_price'], 2) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Guest Information</h6>
                        <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($booking['username']) ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></p>
                        <p class="mb-0"><strong>Phone:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Stay Details</h6>
                        <p class="mb-1"><strong>Check-in:</strong> <?= date('M j, Y', strtotime($booking['check_in'])) ?></p>
                        <p class="mb-1"><strong>Check-out:</strong> <?= date('M j, Y', strtotime($booking['check_out'])) ?></p>
                        <p class="mb-1"><strong>Nights:</strong> <?= $nights ?></p>
                        <p class="mb-0"><strong>Total Price:</strong> $<?= number_format($total_price, 2) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Payment Information</h6>
                        <p class="mb-1"><strong>Payment Status:</strong> <?= isset($booking['payment_status']) && $booking['payment_status'] ? ucfirst($booking['payment_status']) : 'Not specified' ?></p>
                        <p class="mb-1"><strong>Payment Method:</strong> <?= ucfirst($booking['payment_method'] ?? 'Not specified') ?></p>
                        <p class="mb-0"><strong>Transaction ID:</strong> <?= $booking['transaction_id'] ?? 'N/A' ?></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted">Special Requests</h6>
                        <p class="mb-0"><?= !empty($booking['special_requests']) ? nl2br(htmlspecialchars($booking['special_requests'])) : 'No special requests' ?></p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <form method="post" class="d-inline-block">
                    <input type="hidden" name="action" value="update_status">
                    <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm ms-2">Update Status</button>
                </form>
                <a href="bookings.php" class="btn btn-outline-secondary btn-sm float-end">Back to Bookings</a>
            </div>
        </div>
    </div>
    
    <!-- Notes Section -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Booking Notes</h5>
            </div>
            <div class="card-body">
                <form method="post" class="mb-4">
                    <input type="hidden" name="action" value="add_note">
                    <div class="mb-3">
                        <label class="form-label">Add Note</label>
                        <textarea name="note" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Add Note</button>
                </form>
                
                <div class="notes-list">
                    <?php if (empty($notes)): ?>
                        <p class="text-muted text-center mb-0">No notes yet</p>
                    <?php else: ?>
                        <?php foreach ($notes as $note): ?>
                            <div class="note-item mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">
                                        By <?= htmlspecialchars($note['admin_name']) ?>
                                    </small>
                                    <small class="text-muted">
                                        <?= date('M j, Y g:i A', strtotime($note['created_at'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($note['note'])) ?></p>
                            </div>
                            <?php if (!$loop->last): ?>
                                <hr class="my-3">
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the admin layout
require_once 'templates/admin_layout.php';
?> 