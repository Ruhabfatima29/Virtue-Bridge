

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creator Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include(ROOT_PATH . 'src/includes/creatorNavBar.php'); ?>

    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Your journey of making a difference continues here.</p>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e3f2fd;">
                    <i class="fas fa-hand-holding-heart" style="color: #1976d2;"></i>
                </div>
                <h3>Total Raised</h3>
                <div class="stat-value">$<?php echo number_format($stats['total_raised'] ?? 0, 2); ?></div>
                <p>Across all campaigns</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9;">
                    <i class="fas fa-bullseye" style="color: #43a047;"></i>
                </div>
                <h3>Goal Progress</h3>
                <div class="stat-value"><?php 
                    $progress = ($stats['total_goals'] > 0) ? 
                        round(($stats['total_raised'] / $stats['total_goals']) * 100) : 0;
                    echo $progress . '%';
                ?></div>
                <p>Overall completion rate</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3e0;">
                    <i class="fas fa-project-diagram" style="color: #f57c00;"></i>
                </div>
                <h3>Active Campaigns</h3>
                <div class="stat-value"><?php echo $stats['active_campaigns'] ?? 0; ?></div>
                <p>Currently running</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="action-section">
            <h2>Quick Actions</h2>
            <div class="quick-actions">
                <a href="createCampaign.php" class="action-card">
                    <i class="fas fa-plus-circle action-icon"></i>
                    <h3>New Campaign</h3>
                    <p>Start a new fundraising campaign</p>
                </a>

                <a href="manageCampaigns.php" class="action-card">
                    <i class="fas fa-tasks action-icon"></i>
                    <h3>Manage Campaigns</h3>
                    <p>View and edit your campaigns</p>
                </a>

                <a href="analytics.php" class="action-card">
                    <i class="fas fa-chart-line action-icon"></i>
                    <h3>Analytics</h3>
                    <p>Track your campaign performance</p>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2>Recent Donations</h2>
            <div class="activity-list">
                <?php if (!empty($recent_donations)): ?>
                    <?php foreach ($recent_donations as $donation): ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div class="activity-details">
                                <h4>New donation of $<?php echo number_format($donation['amount'], 2); ?></h4>
                                <p>Campaign: <?php echo htmlspecialchars($donation['campaign_title']); ?></p>
                                <small><?php echo date('F j, Y', strtotime($donation['donation_date'])); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-activity">No recent donations to display.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>