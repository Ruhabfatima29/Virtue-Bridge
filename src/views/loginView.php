<?php
// Include the database configuration file
include('../../config/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/navbar.css"> <!-- Added navbar CSS file -->
    
</head>
<body>
    <!-- Include Navbar -->
    <?php include('../includes/navbar.php'); ?>

   <!-- Main content area -->
<div class="main-container">
    <div class="login-container">
        <h2>Login</h2>
        <form action="../controllers/loginController.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <!-- Additional message and link for registration -->
        <p class="register-link">Don't have an account? <a href="<?php echo BASE_URL; ?>src/views/registerView.php">Register now</a>.</p>
    </div>
</div>

</body>
</html>
