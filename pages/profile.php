<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config/pdo.php'; // تأكد من مسار ملف pdo.php

// --- Remember Me Auto-Login Logic ---
// Check if session is not set but remember me cookies are present
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_token'])) {
    $user_id = $_COOKIE['remember_user'];
    $token = $_COOKIE['remember_token'];

    try {
        // Verify the token in the database
        $stmt = $pdo->prepare("SELECT u.* FROM users u JOIN remember_tokens rt ON u.id = rt.user_id WHERE rt.user_id = ? AND rt.token = ? AND rt.expires_at > NOW()");
        $stmt->execute([$user_id, $token]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Token is valid, log the user in
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'] ?? $user_data['name'];
            $_SESSION['first_name'] = $user_data['first_name'] ?? null;
            $_SESSION['last_name'] = $user_data['last_name'] ?? null;
            $_SESSION['user_email'] = $user_data['email'];
            $_SESSION['user_type'] = 'user';
            $_SESSION['profile_image'] = $user_data['profile_image'] ?? null;
            $_SESSION['phone'] = $user_data['phone'] ?? null;
            $_SESSION['date_of_birth'] = $user_data['date_of_birth'] ?? null;
            $_SESSION['gender'] = $user_data['gender'] ?? null;
            $_SESSION['address'] = $user_data['address'] ?? null;
            error_log("Auto-login success for user_id: $user_id");
        } else {
            // Invalid or expired token, clear cookies
            setcookie('remember_user', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
            setcookie('remember_token', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
            error_log("Auto-login failed: invalid or expired token for user_id: $user_id");
        }
    } catch (PDOException $e) {
        error_log("Remember Me auto-login error: " . $e->getMessage());
        // Handle database error (optional)
    }
}
// --- End Remember Me Auto-Login Logic ---

if (!isset($_SESSION['user_id'])) {
    error_log("No user session found, redirecting to login.");
    header("Location: login/login.html");
    exit();
}

// جلب بيانات المستخدم من قاعدة البيانات عند تحميل الصفحة
try {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();

    if ($user_data) {
        // تحديث بيانات الجلسة ببيانات قاعدة البيانات
        $_SESSION['username'] = $user_data['username'] ?? null;
        $_SESSION['first_name'] = $user_data['first_name'] ?? null;
        $_SESSION['last_name'] = $user_data['last_name'] ?? null;
        $_SESSION['user_email'] = $user_data['email'];
        $_SESSION['phone'] = $user_data['phone'] ?? null;
        $_SESSION['date_of_birth'] = $user_data['date_of_birth'] ?? null;
        $_SESSION['gender'] = $user_data['gender'] ?? null;
        $_SESSION['address'] = $user_data['address'] ?? null;
        $_SESSION['profile_image'] = isset($user_data['profile_image']) && $user_data['profile_image'] ? '../' . $user_data['profile_image'] : '../assets/images/default-profile.png';
        $cover_image = isset($user_data['cover_image']) && $user_data['cover_image'] ? '../' . $user_data['cover_image'] : '../assets/images/cover-default.jpg';
        error_log("User data loaded for user_id: $user_id");
    } else {
        // If user ID from session is not found in database (very rare after auto-login)
        error_log("User ID from session not found in database after auth check: " . $user_id);
        // Clear session and cookies and redirect to login
        session_unset();
        session_destroy();
        setcookie('remember_user', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
        setcookie('remember_token', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
        header("Location: login/login.html");
        exit();
    }

    // جلب حجوزات المستخدم
    $stmt = $pdo->prepare("SELECT b.*, h.name as hotel_name FROM bookings b 
                          JOIN hotels h ON b.hotel_id = h.id 
                          WHERE b.user_id = ? ORDER BY b.created_at DESC");
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll();
    error_log("Bookings loaded for user_id: $user_id, count: " . count($bookings));

    // Fetch user's uploaded gallery images
    $user_gallery_images = [];
    try {
        $stmt = $pdo->prepare("SELECT * FROM user_gallery WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        $user_gallery_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Fetched user gallery images for user {$user_id}: " . print_r($user_gallery_images, true));
    } catch (PDOException $e) {
        error_log("Error fetching user gallery images for user {$user_id}: " . $e->getMessage());
    }

    // Fetch user reviews
    $user_reviews = [];
    try {
        $stmt = $pdo->prepare("SELECT r.*, h.name AS hotel_name FROM reviews r JOIN hotels h ON r.hotel_id = h.id WHERE r.user_id = ? ORDER BY r.review_date DESC");
        $stmt->execute([$user_id]);
        $user_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching user reviews for user {$user_id}: " . $e->getMessage());
    }

    // تعريف المتغيرات الخاصة بالاسم واسم المستخدم
    $first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
    $last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

} catch (PDOException $e) {
    error_log("Database error loading user data after auth check in profile.php: " . $e->getMessage());
    $bookings = []; // تعيين مصفوفة فارغة في حالة حدوث خطأ
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Egypt Hotels</title>
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
    <div class="profile-container">
        <!-- صورة الغلاف -->
        <div class="profile-cover" style="background-image: url('<?php echo $cover_image; ?>'), linear-gradient(135deg, #1e5dd1 0%, #2c3e50 100%);">
            <a href="index.php" class="btn btn-outline-secondary profile-back-btn">
                <i class="fas fa-arrow-left"></i> Back to website
            </a>
            <div class="cover-upload-overlay" style="position: absolute; left: 50%; bottom: 18px; transform: translateX(-50%); z-index: 5;">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="editCoverDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-camera"></i>
                        <span>Edit Cover</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="editCoverDropdown">
                        <li>
                            <label class="dropdown-item mb-0" style="cursor:pointer;">
                                <i class="fas fa-upload me-2"></i> Upload Cover
                                <input type="file" id="coverImage" name="cover_image" class="cover-upload-input" accept="image/jpeg,image/png,image/gif" style="display:none" form="profileForm" onchange="uploadCoverImage(this)">
                            </label>
                        </li>
                        <?php if (!str_contains($cover_image, 'cover-default.jpg')): ?>
                        <li>
                            <form method="post" action="profile/update_profile.php" style="display:inline;">
                                <button type="submit" name="delete_cover_image" value="1" class="dropdown-item text-danger">
                                    <i class="fas fa-trash me-2"></i>Delete Cover
                                </button>
                            </form>
                        </li>
                        <li>
                            <a href="<?php echo $cover_image; ?>" target="_blank" class="dropdown-item">
                                <i class="fas fa-external-link-alt me-2"></i>View Cover
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="profile-header-content">
                <div class="profile-image-container mb-3">
                    <a href="<?php echo $_SESSION['profile_image']; ?>" target="_blank">
                        <img src="<?php echo $_SESSION['profile_image']; ?>" alt="Profile Image" id="previewImage" class="profile-preview">
                    </a>
                    <div class="upload-overlay" onclick="document.getElementById('profileImage').click()">
                        <i class="fas fa-camera"></i>
                        <span>Change Photo</span>
                    </div>
                    <input type="file" id="profileImage" name="profile_image" accept="image/*" hidden form="profileForm" onchange="previewImage(this)">
                    <?php if (!str_contains($_SESSION['profile_image'], 'default-profile.png')): ?>
                        <form method="post" action="profile/update_profile.php" style="margin-top:8px;">
                            <button type="submit" name="delete_profile_image" value="1" class="btn btn-sm btn-danger w-100">
                                <i class="fas fa-trash"></i> Delete Photo
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="profile-info-left">
                    <h2><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h2>
                    <div class="username">@<?php echo htmlspecialchars($username); ?></div>
                </div>
            </div>
        </div>
        <!-- التبويبات والمحتوى -->
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="fas fa-user"></i> Account Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">
                    <i class="fas fa-calendar-check"></i> My Bookings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                    <i class="fas fa-shield-alt"></i> Security
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab">
                    <i class="fas fa-images"></i> My Gallery
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                    <i class="fas fa-star"></i> My Reviews
                </button>
            </li>
        </ul>
        <div class="tab-content" id="profileTabsContent">
            <!-- Account Info Tab -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="info-section">
                    <h5><i class="fas fa-info-circle"></i> Personal Information</h5>
                    <form id="profileForm" action="profile/update_profile.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($_SESSION['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth" value="<?php echo htmlspecialchars($_SESSION['date_of_birth'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-control" name="gender">
                                    <option value="" <?php if(empty($_SESSION['gender'])) echo 'selected'; ?>>Select</option>
                                    <option value="male" <?php if($_SESSION['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                    <option value="female" <?php if($_SESSION['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                    <option value="other" <?php if($_SESSION['gender'] == 'other') echo 'selected'; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="2"><?php echo htmlspecialchars($_SESSION['address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
            <!-- Bookings Tab -->
            <div class="tab-pane fade" id="bookings" role="tabpanel">
                <div class="info-section">
                    <h5><i class="fas fa-calendar-check"></i> My Bookings</h5>
                    <?php if ($bookings): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <div class="booking-card">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2"><?php echo htmlspecialchars($booking['hotel_name']); ?></h5>
                                        <p class="mb-1">
                                            <i class="fas fa-calendar"></i> 
                                            <?php echo date('M d, Y', strtotime($booking['check_in'])); ?> - 
                                            <?php echo date('M d, Y', strtotime($booking['check_out'])); ?>
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-users"></i> 
                                            <?php echo $booking['guests']; ?> Guests
                                        </p>
                            </div>
                                    <div class="col-md-4 text-end">
                                        <span class="booking-status status-<?php echo strtolower($booking['status']); ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                        <?php if ($booking['status'] == 'pending'): ?>
                                            <button class="btn btn-sm btn-danger mt-2" onclick="cancelBooking(<?php echo $booking['id']; ?>)">
                                                Cancel Booking
                                            </button>
                                        <?php endif; ?>
                            </div>
                    </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>No Bookings Found</h5>
                            <p class="text-muted">You haven't made any bookings yet.</p>
                            <a href="explore.php" class="btn btn-primary">Explore Hotels</a>
                            </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Security Tab -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="info-section">
                    <h5><i class="fas fa-shield-alt"></i> Change Password</h5>
                    <form id="passwordForm" action="profile/update_password.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required>
                    </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
            <!-- Gallery Tab -->
            <div class="tab-pane fade" id="gallery" role="tabpanel">
                <div class="info-section">
                    <h5><i class="fas fa-images"></i> My Uploaded Photos</h5>
                    <?php if (!empty($user_gallery_images)): ?>
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            <?php foreach ($user_gallery_images as $image): ?>
                                <div class="col">
                                    <div class="card h-100 user-gallery-image-card">
                                        <?php
                                        $imagePath = "/Booking-Hotel-Project/assets/user_uploads/" . $image['image'];
                                        $defaultImage = "/Booking-Hotel-Project/assets/images/gallery/default.jpg"; // استخدام صورة موجودة كصورة افتراضية
                                        
                                        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
                                            // حاول إزالة البادئة حتى أول "_"
                                            $parts = explode('_', $image['image'], 2);
                                            if (count($parts) == 2) {
                                                $imagePathAlt = "/Booking-Hotel-Project/assets/images/gallery/" . $parts[1];
                                                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePathAlt)) {
                                                    $imagePath = $imagePathAlt;
                                                } else {
                                                    $imagePath = $defaultImage;
                                                }
                                            } else {
                                                $imagePath = $defaultImage;
                                            }
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                             class="card-img-top" 
                                             alt="User Uploaded Image"
                                             onerror="this.src='<?php echo $defaultImage; ?>'">
                                        <div class="card-body">
                                            <p class="card-text"><?php echo htmlspecialchars($image['caption'] ?? 'No caption'); ?></p>
                                            <?php if ($image['approved'] == 1): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php elseif ($image['approved'] == 2): ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending Review</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>You have not uploaded any photos yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="info-section">
                    <h5><i class="fas fa-star"></i> My Reviews</h5>
                    <?php if (!empty($user_reviews)): ?>
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <?php foreach ($user_reviews as $review): ?>
                                <div class="col">
                                    <div class="card review-card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title mb-2">
                                                <i class="fas fa-hotel me-2"></i><?php echo htmlspecialchars($review['hotel_name']); ?> - 
                                                <span class="text-primary"><?php echo htmlspecialchars($review['room_name']); ?></span>
                                            </h5>
                                            <div class="mb-2">
                                                <span class="fw-bold">Rating:</span> 
                                                <span class="text-warning">
                                                    <?php echo str_repeat('★', round($review['rating']/2)); ?>
                                                    <?php echo str_repeat('☆', 5-round($review['rating']/2)); ?>
                                                </span>
                                                <span class="ms-1 text-dark"><?php echo htmlspecialchars($review['rating']); ?>/10</span>
                                            </div>
                                            <p class="card-text mb-2"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                            <div class="text-muted small">Reviewed on <?php echo htmlspecialchars($review['review_date']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>You have not submitted any reviews yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Form Validation
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const currentPassword = this.querySelector('[name="current_password"]').value;
        const newPassword = this.querySelector('[name="new_password"]').value;
        const confirmPassword = this.querySelector('[name="confirm_password"]').value;

        if (newPassword.length < 8) {
            e.preventDefault();
            alert('New password must be at least 8 characters long!');
            return;
        }

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match!');
            return;
        }

        // Password strength validation
        const hasUpperCase = /[A-Z]/.test(newPassword);
        const hasLowerCase = /[a-z]/.test(newPassword);
        const hasNumbers = /\d/.test(newPassword);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(newPassword);

        if (!hasUpperCase || !hasLowerCase || !hasNumbers || !hasSpecialChar) {
            e.preventDefault();
            alert('Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character!');
        }
    });

    // Image Preview Function
    function previewImage(input) {
        const preview = document.getElementById('previewImage');
        const file = input.files[0];
        
        if (file) {
            // Check file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                input.value = '';
                return;
            }

            // Check file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a valid image file (JPG, PNG, or GIF)');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    // Cancel Booking Function
    function cancelBooking(bookingId) {
        if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
            fetch('profile/cancel_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ booking_id: bookingId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>Booking cancelled successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.profile-container').insertBefore(alert, document.querySelector('.profile-container').firstChild);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert(data.message || 'Failed to cancel booking');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while canceling the booking');
            });
        }
    }

    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });

    function uploadCoverImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.profile-cover').style.backgroundImage =
                    'url(' + e.target.result + '), linear-gradient(135deg, #1e5dd1 0%, #2c3e50 100%)';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    window.onerror = function(message, source, lineno, colno, error) {
        alert(
            "JS Error: " + message +
            "\nSource: " + source +
            "\nLine: " + lineno +
            "\nColumn: " + colno +
            (error && error.stack ? "\nStack: " + error.stack : "")
        );
        return false;
    };
    document.addEventListener('error', function(e) {
        if (e.target.tagName === 'IMG') {
            alert('Image failed to load: ' + e.target.src);
        }
    }, true);
    </script>
</body>
</html> 