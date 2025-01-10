
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/navbar.css"> <!-- Added navbar CSS file -->
</head>
<body>
    <!-- Include Navbar -->
    <?php include('../includes/navbar.php'); ?>

    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <p>This is your dashboard.</p>
    <a href="../../src/controllers/logoutController.php" class="btn-primary">Logout</a>
</body>
</html>