<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','manager'])) {
    header('Location: unauthorized.php');
    exit;
}
require_once '../../config/database.php';

$pdo = getPDO();
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

// إضافة غرفة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $errors = [];
    if ($name === '') $errors[] = 'Room name is required.';
    if ($price <= 0) $errors[] = 'Valid price is required.';
    
    // معالجة الصورة
    $image = '';
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['room_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Invalid image format. Allowed formats: ' . implode(', ', $allowed);
        } else {
            // اسم مجلد الفندق
            $hotel_folder = $hotel['name'];
            $hotel_folder = str_replace(' ', '-', strtolower($hotel_folder)); // تحويل المسافات إلى - وحروف صغيرة
            $upload_dir = '../../pages/images/rooms/' . $hotel_folder . '/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $new_filename = uniqid('room_') . '.' . $ext;
            $destination = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['room_image']['tmp_name'], $destination)) {
                $image = 'pages/images/rooms/' . $hotel_folder . '/' . $new_filename;
            } else {
                $errors[] = 'Failed to upload image.';
            }
        }
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO rooms (hotel_id, name, description, price, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$hotel_id, $name, $description, $price, $image]);
        $_SESSION['success'] = 'Room added successfully!';
        header("Location: rooms.php?hotel_id=$hotel_id");
        exit;
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

// حذف غرفة
if (isset($_POST['delete_room'])) {
    $room_id = intval($_POST['delete_room']);
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ? AND hotel_id = ?");
    $stmt->execute([$room_id, $hotel_id]);
    $_SESSION['success'] = 'Room deleted successfully!';
    header("Location: rooms.php?hotel_id=$hotel_id");
    exit;
}

// تعديل غرفة
if (isset($_POST['edit_room_id'])) {
    $edit_room_id = intval($_POST['edit_room_id']);
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ? AND hotel_id = ?");
    $stmt->execute([$edit_room_id, $hotel_id]);
    $edit_room = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$edit_room) {
        $_SESSION['error'] = 'Room not found.';
        header("Location: rooms.php?hotel_id=$hotel_id");
        exit;
    }
}

// حفظ التعديلات
if (isset($_POST['update_room'])) {
    $room_id = intval($_POST['room_id']);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $errors = [];
    if ($name === '') $errors[] = 'Room name is required.';
    if ($price <= 0) $errors[] = 'Valid price is required.';
    
    // معالجة الصورة
    $image = $_POST['current_image'] ?? '';
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['room_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Invalid image format. Allowed formats: ' . implode(', ', $allowed);
        } else {
            $hotel_folder = $hotel['name'];
            $hotel_folder = str_replace(' ', '-', strtolower($hotel_folder));
            $upload_dir = '../../pages/images/rooms/' . $hotel_folder . '/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $new_filename = uniqid('room_') . '.' . $ext;
            $destination = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['room_image']['tmp_name'], $destination)) {
                // حذف الصورة القديمة إذا وجدت
                if ($image && file_exists('../../' . $image)) {
                    unlink('../../' . $image);
                }
                $image = 'pages/images/rooms/' . $hotel_folder . '/' . $new_filename;
            } else {
                $errors[] = 'Failed to upload image.';
            }
        }
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE rooms SET name=?, description=?, price=?, image=? WHERE id=? AND hotel_id=?");
        $stmt->execute([$name, $description, $price, $image, $room_id, $hotel_id]);
        $_SESSION['success'] = 'Room updated successfully!';
        header("Location: rooms.php?hotel_id=$hotel_id");
        exit;
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
        $edit_room = [
            'id' => $room_id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $image
        ];
    }
}

// جلب جميع الغرف
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE hotel_id = ? ORDER BY id DESC");
$stmt->execute([$hotel_id]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Manage Rooms for ' . htmlspecialchars($hotel['name']);
ob_start();
?>
<div class="container py-4">
    <h3 class="mb-4">Manage Rooms for <span class="text-primary"><?= htmlspecialchars($hotel['name']) ?></span></h3>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
    <?php endif; ?>
    <form method="post" class="mb-4" enctype="multipart/form-data">
        <?php $is_edit = isset($edit_room); ?>
        <?php if ($is_edit): ?>
            <input type="hidden" name="room_id" value="<?= $edit_room['id'] ?>">
            <input type="hidden" name="current_image" value="<?= $edit_room['image'] ?? '' ?>">
        <?php endif; ?>
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Room Name</label>
                <input type="text" name="name" class="form-control" required value="<?= $is_edit ? htmlspecialchars($edit_room['name']) : '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control" value="<?= $is_edit ? htmlspecialchars($edit_room['description']) : '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" min="1" step="0.01" required value="<?= $is_edit ? htmlspecialchars($edit_room['price']) : '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Room Image</label>
                <input type="file" name="room_image" class="form-control" accept="image/*">
                <?php if ($is_edit && !empty($edit_room['image'])): ?>
                    <img src="../../<?= htmlspecialchars($edit_room['image']) ?>" alt="Room Image" class="mt-2" style="max-width: 100px;">
                <?php endif; ?>
            </div>
            <div class="col-md-12 mt-3">
                <?php if ($is_edit): ?>
                    <button type="submit" name="update_room" class="btn btn-primary">Update</button>
                    <a href="rooms.php?hotel_id=<?= $hotel_id ?>" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add_room" class="btn btn-success">Add</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-header"><b>Rooms List</b></div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td><?= htmlspecialchars($room['name']) ?></td>
                            <td><?= htmlspecialchars($room['description']) ?></td>
                            <td>EGP <?= number_format($room['price'], 2) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="edit_room_id" value="<?= $room['id'] ?>">
                                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                </form>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Delete this room?');">
                                    <input type="hidden" name="delete_room" value="<?= $room['id'] ?>">
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