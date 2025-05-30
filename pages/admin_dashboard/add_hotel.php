<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','manager'])) {
    header('Location: unauthorized.php');
    exit;
}
// header('Location: ../admin_login.php'); // تم التعطيل بناءً على طلب الإدارة

$page_title = 'Add New Hotel';
$page_actions = '
    <a href="hotels.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Hotels
    </a>
';

// Check for required files
$required_files = [
    '../../classes/ImageManager.php' => 'ImageManager class',
    '../../config/database.php' => 'Database configuration'
];

foreach ($required_files as $file => $description) {
    if (!file_exists($file)) {
        error_log("Required file not found: $file");
        die("Error: Required $description file not found. Please contact the administrator.");
    }
}

require_once '../../classes/ImageManager.php';
require_once '../../config/database.php';

try {
    $imageManager = new ImageManager();
} catch (Exception $e) {
    error_log("ImageManager initialization error: " . $e->getMessage());
    die("Error: Could not initialize image management system. Please contact the administrator.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getPDO();

        // Validate required fields
        $required_fields = ['name', 'location', 'price', 'rating', 'description'];
        $errors = [];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = ucfirst($field) . ' is required.';
            }
        }

        // Validate price and rating
        if (!empty($_POST['price']) && (!is_numeric($_POST['price']) || $_POST['price'] < 0)) {
            $errors[] = 'Price must be a valid positive number.';
        }
        
        if (!empty($_POST['rating']) && (!is_numeric($_POST['rating']) || $_POST['rating'] < 0 || $_POST['rating'] > 5)) {
            $errors[] = 'Rating must be between 0 and 5.';
        }

        // Handle image upload
        $image_path = $imageManager->getDefaultImage();
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $image_errors = $imageManager->validateImage($_FILES['image']);
            if (!empty($image_errors)) {
                $errors = array_merge($errors, $image_errors);
            }
        }

        if (empty($errors)) {
            // Upload image if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $image_path = $imageManager->uploadImage($_FILES['image']);
                if ($image_path && $image_path[0] !== '/') {
                    $image_path = '/' . ltrim($image_path, '/');
                }
            }

            // Insert hotel
            $stmt = $pdo->prepare("
                INSERT INTO hotels (name, location, description, price, rating, image)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $_POST['name'],
                $_POST['location'],
                $_POST['description'],
                $_POST['price'],
                $_POST['rating'],
                $image_path
            ]);

            $_SESSION['success'] = 'Hotel added successfully!';
            header('Location: hotels.php');
            exit;
        } else {
            $_SESSION['error'] = implode('<br>', $errors);
        }
    } catch (Exception $e) {
        error_log("[" . date('Y-m-d H:i:s') . "] Add hotel error: " . $e->getMessage() . "\n", 3, "../../logs/admin_errors.log");
        $_SESSION['error'] = "An error occurred while adding the hotel.";
    }
}

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Add New Hotel</h5>
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Hotel Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                        <div class="invalid-feedback">Please enter a hotel name.</div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="location" name="location" 
                               value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" required>
                        <div class="invalid-feedback">Please enter a location.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price per Night (EGP) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01"
                                       value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
                                <div class="invalid-feedback">Please enter a valid price.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating (0-5) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="rating" name="rating" min="0" max="5" step="0.1"
                                       value="<?= htmlspecialchars($_POST['rating'] ?? '') ?>" required>
                                <div class="invalid-feedback">Please enter a rating between 0 and 5.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <div class="invalid-feedback">Please enter a description.</div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Hotel Image</label>
                        <div class="card">
                            <img src="<?= $imageManager->getDefaultImage() ?>" 
                                 class="card-img-top" 
                                 id="imagePreview"
                                 alt="Hotel Preview">
                            <div class="card-body">
                                <div class="input-group">
                                    <input type="file" class="form-control" id="image" name="image" 
                                           accept="image/jpeg,image/png,image/webp"
                                           data-preview="imagePreview">
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Supported formats: JPEG, PNG, WebP<br>
                                    Max file size: <?= $imageManager->formatSize($imageManager->getMaxFileSize()) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="hotels.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Hotel
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();

// Add custom CSS
$extra_css = '
<style>
.card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
</style>
';

// Add custom JavaScript for form validation and image preview
$extra_js = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Form validation
    const form = document.querySelector(".needs-validation");
    form.addEventListener("submit", function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add("was-validated");
    });

    // Image preview
    const imageInput = document.getElementById("image");
    const imagePreview = document.getElementById("imagePreview");
    const defaultImage = "' . $imageManager->getDefaultImage() . '";

    imageInput.addEventListener("change", function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            imagePreview.src = defaultImage;
        }
    });
});
</script>
';

require_once __DIR__ . '/templates/admin_layout.php';
?> 