<?php
// Disable error display for AJAX/JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

header('Content-Type: application/json');

session_start();
require_once '../../config/pdo.php';
require_once '../../config/database.php';
require_once '../../classes/ImageManager.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $facebook_url = isset($_POST['facebook_url']) ? trim($_POST['facebook_url']) : '';
    $twitter_url = isset($_POST['twitter_url']) ? trim($_POST['twitter_url']) : '';
    $instagram_url = isset($_POST['instagram_url']) ? trim($_POST['instagram_url']) : '';
    $linkedin_url = isset($_POST['linkedin_url']) ? trim($_POST['linkedin_url']) : '';
    $website_url = isset($_POST['website_url']) ? trim($_POST['website_url']) : '';
    $skills = isset($_POST['skills']) ? trim($_POST['skills']) : '';
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';

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
            $errors[] = "Error checking duplicate username/email";
        }
    }
    if (!empty($facebook_url) && !filter_var($facebook_url, FILTER_VALIDATE_URL)) $errors[] = "Invalid Facebook URL";
    if (!empty($twitter_url) && !filter_var($twitter_url, FILTER_VALIDATE_URL)) $errors[] = "Invalid Twitter URL";
    if (!empty($instagram_url) && !filter_var($instagram_url, FILTER_VALIDATE_URL)) $errors[] = "Invalid Instagram URL";
    if (!empty($linkedin_url) && !filter_var($linkedin_url, FILTER_VALIDATE_URL)) $errors[] = "Invalid LinkedIn URL";
    if (!empty($website_url) && !filter_var($website_url, FILTER_VALIDATE_URL)) $errors[] = "Invalid Website URL";

    if (isset($_POST['delete_profile_image'])) {
        $stmt = $pdo->prepare("UPDATE users SET profile_image = NULL WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            $_SESSION['profile_image'] = 'assets/images/default-profile.png';
            echo json_encode(['success' => true]);
            exit;
        }
    }
    if (isset($_POST['delete_cover_image'])) {
        $stmt = $pdo->prepare("UPDATE users SET cover_image = NULL WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            $_SESSION['cover_image'] = 'assets/images/cover-default.jpg';
            echo json_encode(['success' => true]);
            exit;
        }
    }
    function handleImageUpload($file, $type, $user_id) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024;
        if (!in_array($file['type'], $allowed_types)) {
            return ['success' => false, 'message' => 'Invalid file type'];
        }
        if ($file['size'] > $max_size) {
            return ['success' => false, 'message' => 'File size too large (max 5MB)'];
        }
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/Booking-Hotel-Project/assets/images/profiles/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        if (!is_writable($upload_dir)) {
            return ['success' => false, 'message' => 'Upload directory is not writable'];
        }
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = $type . '_' . $user_id . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            return [
                'success' => true,
                'path' => "assets/images/profiles/" . $new_filename
            ];
        }
        return ['success' => false, 'message' => 'Failed to upload file'];
    }
    if (isset($_FILES['profile_image']) || isset($_FILES['cover_image'])) {
        $response = ['success' => false, 'message' => ''];
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $result = handleImageUpload($_FILES['profile_image'], 'profiles', $user_id);
            if ($result['success']) {
                $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                if ($stmt->execute([$result['path'], $user_id])) {
                    $_SESSION['profile_image'] = '../' . $result['path'];
                    $response = ['success' => true, 'message' => 'Profile image updated successfully', 'profile_image' => '../' . $result['path']];
                } else {
                    $response['message'] = 'Failed to update profile image in database';
                }
            } else {
                $response['message'] = $result['message'];
            }
        }
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $result = handleImageUpload($_FILES['cover_image'], 'covers', $user_id);
            if ($result['success']) {
                $stmt = $pdo->prepare("UPDATE users SET cover_image = ? WHERE id = ?");
                if ($stmt->execute([$result['path'], $user_id])) {
                    $_SESSION['cover_image'] = '../' . $result['path'];
                    $response = ['success' => true, 'message' => 'Cover image updated successfully', 'cover_image' => '../' . $result['path']];
                } else {
                    $response['message'] = 'Failed to update cover image in database';
                }
            } else {
                $response['message'] = $result['message'];
            }
        }
        echo json_encode($response);
        exit;
    }
    if (empty($errors)) {
        try {
            $sql = "UPDATE users SET 
                username = ?, 
                first_name = ?, 
                last_name = ?, 
                name = CONCAT(first_name, ' ', last_name),
                email = ?, 
                phone = ?, 
                date_of_birth = ?, 
                gender = ?, 
                address = ?,
                email_notifications = ?,
                sms_notifications = ?,
                marketing_emails = ?,
                updated_at = CURRENT_TIMESTAMP,
                is_active = ?,
                status = ?,
                last_login = ?,
                facebook_url = ?,
                twitter_url = ?,
                instagram_url = ?,
                linkedin_url = ?,
                website_url = ?,
                skills = ?,
                bio = ?";
            $params = [
                $username,
                $first_name,
                $last_name,
                $email,
                $phone,
                $date_of_birth,
                $gender,
                $address,
                $_POST['email_notifications'] ?? 1,
                $_POST['sms_notifications'] ?? 1,
                $_POST['marketing_emails'] ?? 0,
                1, // is_active
                'active', // status
                date('Y-m-d H:i:s'), // last_login
                $facebook_url,
                $twitter_url,
                $instagram_url,
                $linkedin_url,
                $website_url,
                $skills,
                $bio
            ];
            if (isset($_SESSION['profile_image'])) {
                $sql .= ", profile_image = ?";
                $params[] = str_replace('../', '', $_SESSION['profile_image']);
            }
            if (isset($_SESSION['cover_image'])) {
                $sql .= ", cover_image = ?";
                $params[] = str_replace('../', '', $_SESSION['cover_image']);
            }
            $sql .= " WHERE id = ?";
            $params[] = $user_id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $_SESSION['username'] = $username;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['user_email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['date_of_birth'] = $date_of_birth;
            $_SESSION['gender'] = $gender;
            $_SESSION['address'] = $address;
            $_SESSION['email_notifications'] = $_POST['email_notifications'] ?? 1;
            $_SESSION['sms_notifications'] = $_POST['sms_notifications'] ?? 1;
            $_SESSION['marketing_emails'] = $_POST['marketing_emails'] ?? 0;
            $_SESSION['facebook_url'] = $facebook_url;
            $_SESSION['twitter_url'] = $twitter_url;
            $_SESSION['instagram_url'] = $instagram_url;
            $_SESSION['linkedin_url'] = $linkedin_url;
            $_SESSION['website_url'] = $website_url;
            $_SESSION['skills'] = $skills;
            $_SESSION['bio'] = $bio;
            if (isset($_SESSION['profile_image'])) {
                if (strpos($_SESSION['profile_image'], '../') === false) {
                    $_SESSION['profile_image'] = '../' . $_SESSION['profile_image'];
                }
            }
            if (isset($_SESSION['cover_image'])) {
                if (strpos($_SESSION['cover_image'], '../') === false) {
                    $_SESSION['cover_image'] = '../' . $_SESSION['cover_image'];
                }
            }
            echo json_encode([
                'success' => true,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'profile_image' => $_SESSION['profile_image'] ?? null,
                'cover_image' => $_SESSION['cover_image'] ?? null
            ]);
            exit;
        } catch (PDOException $e) {
            $errors[] = "Error updating data: " . $e->getMessage();
        }
    }
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
} 