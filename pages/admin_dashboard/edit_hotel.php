<?php
$page_title = 'Edit Hotel';

require_once '../../config/database.php';
require_once '../../classes/ImageManager.php';

try {
    $pdo = getPDO();
    $imageManager = new ImageManager();

if (!isset($_GET['id'])) {
        throw new Exception('Hotel ID is required');
    }

    $hotelId = $_GET['id'];
    
    // Fetch hotel details
    $stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
    $stmt->execute([$hotelId]);
    $hotel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hotel) {
        throw new Exception('Hotel not found');
    }

    $page_actions = '
        <a href="hotels.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Hotels
        </a>
    ';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $image_path = $hotel['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $image_errors = $imageManager->validateImage($_FILES['image']);
            if (!empty($image_errors)) {
                $errors = array_merge($errors, $image_errors);
            }
        }

        if (empty($errors)) {
            // Upload new image if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $image_path = $imageManager->uploadImage($_FILES['image'], $hotel['image']);
                if ($image_path && $image_path[0] !== '/') {
                    $image_path = '/' . ltrim($image_path, '/');
                }
            }

            // Update hotel
            $stmt = $pdo->prepare("
                UPDATE hotels 
                SET name = ?, location = ?, description = ?, price = ?, rating = ?, image = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $_POST['name'],
                $_POST['location'],
                $_POST['description'],
                $_POST['price'],
                $_POST['rating'],
                $image_path,
                $hotel['id']
            ]);

            $_SESSION['success'] = 'Hotel updated successfully!';
            header('Location: hotels.php');
            exit;
        } else {
            $_SESSION['error'] = implode('<br>', $errors);
        }
    }
} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Edit hotel error: " . $e->getMessage() . "\n", 3, "../../logs/admin_errors.log");
    $_SESSION['error'] = $e->getMessage();
    header('Location: hotels.php');
    exit;
}

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Hotel</h5>
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Hotel Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($_POST['name'] ?? $hotel['name']) ?>" required>
                        <div class="invalid-feedback">Please enter a hotel name.</div>
                    </div>

        <div class="mb-3">
                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="location" name="location" 
                               value="<?= htmlspecialchars($_POST['location'] ?? $hotel['location']) ?>" required>
                        <div class="invalid-feedback">Please enter a location.</div>
        </div>

                    <div class="row">
                        <div class="col-md-6">
        <div class="mb-3">
                                <label for="price" class="form-label">Price per Night (EGP) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01"
                                       value="<?= htmlspecialchars($_POST['price'] ?? $hotel['price']) ?>" required>
                                <div class="invalid-feedback">Please enter a valid price.</div>
                            </div>
        </div>
                        <div class="col-md-6">
        <div class="mb-3">
                                <label for="rating" class="form-label">Rating (0-5) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="rating" name="rating" min="0" max="5" step="0.1"
                                       value="<?= htmlspecialchars($_POST['rating'] ?? $hotel['rating']) ?>" required>
                                <div class="invalid-feedback">Please enter a rating between 0 and 5.</div>
                            </div>
                        </div>
        </div>

        <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($_POST['description'] ?? $hotel['description']) ?></textarea>
                        <div class="invalid-feedback">Please enter a description.</div>
                    </div>
        </div>

                <!-- Right Column -->
                <div class="col-md-4">
        <div class="mb-3">
            <label for="image" class="form-label">Hotel Image</label>
                        <div class="card">
                            <img src="<?= htmlspecialchars('../..' . $hotel['image']) ?>" 
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
                                    Max file size: <?= $imageManager->formatSize($imageManager->getMaxFileSize()) ?><br>
                                    Leave empty to keep current image
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
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();

require_once __DIR__ . '/templates/admin_layout.php';
?> 