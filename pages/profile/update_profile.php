<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../config/pdo.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.html");
    exit();
}

$debug_log = __DIR__ . '/debug_profile_update.log';
function debug_log($msg) {
    global $debug_log;
    file_put_contents($debug_log, date('Y-m-d H:i:s') . ' | ' . $msg . "\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    debug_log('POST: ' . json_encode($_POST));
    debug_log('FILES: ' . json_encode($_FILES));
    
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));

    $user_id = $_SESSION['user_id'];
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';

    $errors = [];
    
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email is invalid";
    if (!empty($username)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$username, $email, $user_id]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Username or email already exists";
            }
        } catch (PDOException $e) {
            error_log("Error checking duplicate username/email: " . $e->getMessage());
            $errors[] = "Error checking duplicate username/email";
        }
    }

    if (isset($stmt) && $stmt === false) {
        error_log("PDO Error: " . print_r($pdo->errorInfo(), true));
    }

    // معالجة صورة البروفايل
    $profile_image_path = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024;
        $file_type = $_FILES['profile_image']['type'];
        $file_size = $_FILES['profile_image']['size'];
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $upload_dir = '../../assets/images/profiles/';
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                    error_log("Failed to create upload directory: " . $upload_dir);
                    $errors[] = "Failed to create upload directory";
                }
            }
            $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            error_log("Attempting to move uploaded file to: " . $upload_path);

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                $profile_image_path = 'assets/images/profiles/' . $new_filename;
                $_SESSION['profile_image'] = $profile_image_path;
                error_log("Profile image updated successfully. Path saved: " . $profile_image_path);
            } else {
                error_log("Failed to move uploaded file. Details: " . print_r($_FILES['profile_image'], true));
                $errors[] = "Failed to upload image. Please try again.";
            }
        } else {
            error_log("Invalid file type or size. Type: " . $file_type . ", Size: " . $file_size);
            $errors[] = "Unsupported file type or image size is too large (max 5MB).";
        }
    } else if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        error_log("File upload error: " . $_FILES['profile_image']['error']);
        $errors[] = "File upload error: " . $_FILES['profile_image']['error'];
    }

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        error_log("Profile image upload error: " . $_FILES['profile_image']['error']);
    }

    // معالجة صورة الغلاف
    $cover_image_path = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        debug_log('Uploading cover_image...');
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024;
        $file_type = $_FILES['cover_image']['type'];
        $file_size = $_FILES['cover_image']['size'];
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $upload_dir = '../../assets/images/covers/';
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                    header('Location: ../profile.php?error=cover_upload');
                    exit();
                }
            }
            $file_extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $new_filename = 'cover_' . $user_id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_path)) {
                $cover_image_path = 'assets/images/covers/' . $new_filename;
                $_SESSION['cover_image'] = $cover_image_path;
            } else {
                header('Location: ../profile.php?error=cover_upload');
                exit();
            }
        } else {
            error_log("Invalid cover file type or size. Type: " . $file_type . ", Size: " . $file_size);
            $errors[] = "Unsupported cover file type or image size is too large (max 5MB).";
        }
    } else if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        error_log("Cover file upload error: " . $_FILES['cover_image']['error']);
        $errors[] = "Cover file upload error: " . $_FILES['cover_image']['error'];
    }

    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_OK && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        error_log("Cover image upload error: " . $_FILES['cover_image']['error']);
    }

    if (isset($_POST['delete_profile_image'])) {
        $stmt = $pdo->prepare("UPDATE users SET profile_image = NULL WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['profile_image'] = '../assets/images/default-profile.png';
        header("Location: ../profile.php?success=1");
        exit();
    }
    if (isset($_POST['delete_cover_image'])) {
        $stmt = $pdo->prepare("UPDATE users SET cover_image = NULL WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['cover_image'] = '../assets/images/cover-default.jpg';
        header("Location: ../profile.php?success=1");
        exit();
    }

    if (empty($errors)) {
        try {
            // بناء جملة التحديث ديناميكيًا حسب الصور
            $fields = [
                'username = ?',
                'first_name = ?',
                'last_name = ?',
                'email = ?',
                'phone = ?',
                'date_of_birth = ?',
                'gender = ?',
                'address = ?'
            ];
            $params = [$username, $first_name, $last_name, $email, $phone, $date_of_birth, $gender, $address];
            if ($profile_image_path) {
                $fields[] = 'profile_image = ?';
                $params[] = $profile_image_path;
            }
            if ($cover_image_path) {
                $fields[] = 'cover_image = ?';
                $params[] = $cover_image_path;
            }
            $params[] = $user_id;
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            debug_log('UPDATE executed. RowCount: ' . $stmt->rowCount());

            if ($stmt->rowCount() == 0) {
                error_log("No rows updated for user_id: $user_id");
            } else {
                error_log("User updated successfully for user_id: $user_id");
            }

            // تحديث بيانات الجلسة
            $_SESSION['username'] = $username;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['date_of_birth'] = $date_of_birth;
            $_SESSION['gender'] = $gender;
            $_SESSION['address'] = $address;
            if ($profile_image_path) $_SESSION['profile_image'] = $profile_image_path;
            if ($cover_image_path) $_SESSION['cover_image'] = $cover_image_path;

            debug_log('Redirecting to ../profile.php?success=1');
            header("Location: ../profile.php?success=1");
            exit();

        } catch (PDOException $e) {
            debug_log('PDOException: ' . $e->getMessage());
            error_log("Profile update error: " . $e->getMessage());
            $errors[] = "Error updating data: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        debug_log('Errors: ' . json_encode($errors));
        $_SESSION['profile_errors'] = $errors;
        header("Location: ../profile.php?error=validation");
        exit();
    }
} else {
    header("Location: ../profile.php");
    exit();
} 