<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<header class="header" id="header">
        <nav class="navbar container">
            <a href="index.php" class="logo">
                <img src="../pages/images/Logo.png" alt="Egypt Hotels Logo" class="logo-img">
            </a>
            <ul class="nav-links">
                <li><a href="explore.php">Explore</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="nav-icons">
                <div class="nav-icon" id="openSidebarBtn" tabindex="0">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </nav>
    </header>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar-menu" id="sidebarMenu">
        <button class="sidebar-close" id="closeSidebarBtn" aria-label="Close Menu"><i class="fas fa-times"></i></button>
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="user-profile">
            <div class="user-info">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="profile-image-container">
                        <?php
                            $profile_image_path = isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image']) 
                                ? (strpos($_SESSION['profile_image'], '../') === 0 ? htmlspecialchars($_SESSION['profile_image']) : '../' . htmlspecialchars($_SESSION['profile_image'])) 
                                : '../images/default-avatar.png';
                        ?>
                        <img src="<?php echo $profile_image_path; ?>" alt="Profile Image" class="profile-image">
                    </div>
                    <span class="username" style="display: flex; flex-direction: column; align-items: center; text-align: center; width: 100%;">
                        <h3 style="margin-bottom: 0;">
                            <?php
                            if (isset($_SESSION['first_name']) && isset($_SESSION['last_name']) && $_SESSION['first_name'] && $_SESSION['last_name']) {
                                echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);
                            } elseif (isset($_SESSION['username'])) {
                                echo htmlspecialchars($_SESSION['username']);
                            } else {
                                echo 'User';
                            }
                            ?>
                        </h3>
                        <p class="user-email" style="margin-top: 0; font-size: 0.95em; color: #888;">
                            <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>
                        </p>
                    </span>
                <?php endif; ?>
            </div>
            <div class="profile-actions">
                    <a href="profile.php" class="profile-link"><i class="fas fa-user"></i> My Profile</a>
            </div>
        </div>
        <hr class="sidebar-divider">
        <?php endif; ?>
        <nav class="sidebar-links">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="explore.php"><i class="fas fa-compass"></i> Explore</a>
            <a href="gallery.php"><i class="fas fa-images"></i> Gallery</a>
            <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
            <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <hr class="sidebar-divider">
            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <a href="admin_dashboard/index.php"><i class="fas fa-user-shield"></i>Dashboard</a>
            <?php endif; ?>
                <a href="login/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <hr class="sidebar-divider">
                <a href="login/login.html" class="login-link"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </nav>
    </aside>