<?php require_once __DIR__ . '/../../config/db.php'; ?>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fundraiser Dashboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        </link>
</head>

<div class="sidebar">
<h2><i class="fas fa-tools"></i> Fundraiser Panel</h2>
    <a href="<?php echo BASE_URL; ?>src/views/creatorDashboard.php#hero"><i class="fas fa-home"></i>  Home</a>
    <a href="<?php echo BASE_URL; ?>src/views/creatorprojects.php"><i class="fas fa-project-diagram"></i>  My Campaigns</a> <!-- Example for "My Projects" -->
    <a href="<?php echo BASE_URL; ?>src/controllers/AddCampaignController.php"><i class="fas fa-plus-circle"></i>  Add New Campaign</a>
    <a href="<?php echo BASE_URL; ?>src/views/creatorProjectDonations.php"><i class="fas fa-donate"></i>  Donations</a>
    <a href="<?php echo BASE_URL; ?>src/controllers/logoutController.php"><i class="fas fa-sign-out-alt"></i>  Logout</a>

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