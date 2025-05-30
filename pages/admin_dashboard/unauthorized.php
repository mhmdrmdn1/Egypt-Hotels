<?php
session_start();
$page_title = 'Unauthorized Access';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .unauth-box { max-width: 400px; margin: 80px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 32px 24px; text-align: center; }
        .unauth-box h1 { font-size: 2.2rem; color: #dc3545; margin-bottom: 1rem; }
        .unauth-box p { color: #555; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <div class="unauth-box">
        <h1><i class="fas fa-ban"></i> Unauthorized</h1>
        <p>You are not authorized to access this page.</p>
        <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html> 