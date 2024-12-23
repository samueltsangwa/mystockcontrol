<?php
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Only start the session if it's not already started
}
?>
<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="home.php" class="navbar-brand"><b>SALES INVENTORY</b></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="user user-menu">
                        <a href="#">
                            <!-- Display profile photo -->
                            <img src="<?php echo isset($_SESSION['profile_photo']) ? htmlspecialchars($_SESSION['profile_photo']) : 'path/to/default/profile.jpg'; ?>" 
                                 alt="Profile Photo" 
                                 class="user-image" 
                                 style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;">
                            
                            <!-- Fetching the username from the session -->
                            <span class="hidden-xs"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></span>
                        </a>
                    </li>
                    <li><a href="logout.php"><i class="fa fa-sign-out"></i> LOGOUT</a></li>  
                </ul>
            </div>
        </div>
    </nav>
</header>
<?php include 'includes/user_profile_modal.php'; ?>
