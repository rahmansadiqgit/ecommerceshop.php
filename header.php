<?php session_start(); ?>
<div style="text-align:right; padding:10px; border-bottom:1px solid #ccc;">
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="profile.php">Profile</a> | <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a> | <a href="register.php">Register</a>
    <?php endif; ?>

</div>
