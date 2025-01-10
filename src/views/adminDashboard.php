<?php
session_start();

// Check if the user is an admin
if ($_SESSION['role'] !== 3) {
    header('Location: home.php');
    exit();
}

require_once('../controllers/adminDashboardController.php');
$stats = getDashboardStats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/adminDashboard.css">
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include('../includes/adminNavBar.php'); ?>
    
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-content">
                <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Platform Overview</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e3f2fd;">
                    <i class="fas fa-users" style="color: #1976d2;"></i>
                </div>
                <h3>Total Users</h3>
                <div class="stat-value"><?php echo number_format($stats['users']); ?></div>
                <p>Active platform users</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9;">
                    <i class="fas fa-hand-holding-heart" style="color: #43a047;"></i>
                </div>
                <h3>Total Donations</h3>
                <div class="stat-value">$<?php echo number_format($stats['donations'], 2); ?></div>
                <p>Lifetime donations</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3e0;">
                    <i class="fas fa-project-diagram" style="color: #f57c00;"></i>
                </div>
                <h3>Active Projects</h3>
                <div class="stat-value"><?php echo number_format($stats['campaigns']); ?></div>
                <p>Ongoing campaigns</p>
            </div>
        </div>

        
    <div class="quick-actions">
        <div class="action-card">
            <i class="fas fa-user-plus action-icon"></i>
            <h3>Manage Users</h3>
            <p>Add, edit, or remove users</p>
        </div>
        
        <div class="action-card">
            <i class="fas fa-tasks action-icon"></i>
            <h3>Projects</h3>
            <p>Review and manage projects</p>
        </div>
        
        <div class="action-card">
            <i class="fas fa-chart-line action-icon"></i>
            <h3>Analytics</h3>
            <p>View platform statistics</p>
        </div>
        
        <div class="action-card">
            <i class="fas fa-cog action-icon"></i>
            <h3>Settings</h3>
            <p>Configure platform settings</p>
        </div>
    </div>
    </div>
</body>
</html>