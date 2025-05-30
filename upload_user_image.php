<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /Booking-Hotel-Project/pages/gallery.php?upload=error");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['user_image'])) {
    $uploadDir = __DIR__ . '/assets/user_uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = uniqid() . '_' . basename($_FILES['user_image']['name']);
    $targetFile = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['user_image']['tmp_name'], $targetFile)) {
        $caption = htmlspecialchars($_POST['caption']);
        $userId = $_SESSION['user_id'];
        require_once __DIR__ . '/config/database.php';
        $stmt = $pdo->prepare("INSERT INTO user_gallery (user_id, image, caption, approved) VALUES (?, ?, ?, 0)");
        $stmt->execute([$userId, $fileName, $caption]);
        header("Location: /Booking-Hotel-Project/pages/gallery.php?upload=success");
        exit;
    } else {
        echo "<h2>Upload failed!</h2>";
        echo "<pre>";
        print_r($_FILES['user_image']);
        echo "<br>Target: $targetFile";
        echo "<br>Is dir writable: ".(is_writable(dirname($targetFile)) ? 'yes' : 'no');
        echo "<br>__DIR__: " . __DIR__;
        echo "<br>Current user: " . get_current_user();
        echo "<br>open_basedir: " . ini_get('open_basedir');
        echo "<br>upload_max_filesize: " . ini_get('upload_max_filesize');
        echo "<br>post_max_size: " . ini_get('post_max_size');
        echo "<br>file_uploads: " . ini_get('file_uploads');
        echo "<br>error code: " . $_FILES['user_image']['error'];
        echo "<br>See https://www.php.net/manual/en/features.file-upload.errors.php for error code meaning.";
        echo "</pre>";
        exit;
    }
} else {
    header("Location: /Booking-Hotel-Project/pages/gallery.php?upload=error");
    exit;
}