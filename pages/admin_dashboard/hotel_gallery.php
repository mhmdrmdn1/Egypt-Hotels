<?php
$page_title = 'Hotel Gallery';

require_once '../../config/database.php';
require_once '../../classes/HotelGallery.php';

try {
$pdo = getPDO();
    $gallery = new HotelGallery($pdo);

    // Fetch all hotels
    $stmt = $pdo->query("SELECT id, name FROM hotels ORDER BY name");
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $page_actions = '
        <a href="hotels.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Hotels
        </a>
    ';

    ob_start();
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Hotel Gallery</h1>
    <div class="row">
        <?php foreach ($hotels as $hotel): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?= htmlspecialchars($hotel['name']) ?></h5>
        </div>
        <div class="card-body">
                        <div class="gallery-grid" id="gallery-<?= $hotel['id'] ?>">
                            <?php
                            $images = $gallery->getHotelImages($hotel['id']);
                            foreach ($images as $image):
                            ?>
                                <div class="gallery-item" data-id="<?= $image['id'] ?>">
                                    <img src="../../<?= htmlspecialchars($image['image']) ?>" 
                                         alt="<?= htmlspecialchars($hotel['name']) ?> Image" 
                                         class="img-fluid"
                                         onerror="this.onerror=null;this.src='../../assets/images/no-image.jpg';">
                                    <div class="gallery-item-overlay">
                                        <button class="btn btn-sm btn-danger delete-image" 
                                                data-id="<?= $image['id'] ?>"
                                                data-hotel-id="<?= $hotel['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <form class="mt-3" action="ajax/gallery_handler.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="upload">
                            <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
                            <div class="input-group">
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                        </div>
                </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}
.gallery-item {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
    border-radius: 5px;
}
.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.gallery-item-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s;
}
.gallery-item:hover .gallery-item-overlay {
    opacity: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle image deletion
    document.querySelectorAll('.delete-image').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this image?')) {
                const imageId = this.dataset.id;
                const hotelId = this.dataset.hotelId;
                fetch('ajax/gallery_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'delete',
                        image_id: imageId,
                        hotel_id: hotelId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.gallery-item').remove();
                    } else {
                        alert('Failed to delete image: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete image. Please try again.');
                });
            }
        });
    });
});
</script>
<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/templates/admin_layout.php';
} catch (Exception $e) {
    error_log("Hotel Gallery error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while loading the hotel gallery.";
    header('Location: hotels.php');
    exit;
} 