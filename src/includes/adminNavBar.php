<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
</head>
    
        <div class="sidebar">
            <h1><i class="fas fa-tools"></i> Admin Panel</h1>
            <a href="../views/adminDashboard.php#hero"><i class="fas fa-tachometer-alt"></i> Admin Panel</a>
            <a href="../views/manageUsers.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="../views/manageCampaigns.php"><i class="fas fa-bullhorn"></i> Manage Campaigns</a>
            <a href="../views/eduFundraiser.php"><i class="fas fa-graduation-cap"></i> Education Fundraisers</a>
            <a href="../views/addLocalCommunities.php"><i class="fas fa-users"></i> Local Community</a>
            <a href="../views/localCommunities.php"><i class="fas fa-book"></i> Education Analysis</a>
            <a href="../views/potentialDonors.php"><i class="fas fa-dollar-sign"></i> Potential Donors</a>            <a href="../views/adminReports.php"><i class="fas fa-chart-line"></i> Reports</a>
            <a href="../../src/controllers/logoutController.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    <style>
        @media (max-width: 768px) {
            .admin-navbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .nav-links {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</body>
</html>