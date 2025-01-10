<?php
include('../../config/db.php');
// adminDashboardController.php
function getDashboardStats() {
    global $conn;
    
    try {
        // Get total users
        $userQuery = "SELECT COUNT(*) as total_users FROM users";
        $result = $conn->query($userQuery);
        $totalUsers = $result->fetch_assoc()['total_users'];

        // Get total donations
        $donationQuery = "SELECT SUM(amount) as total_donations FROM donations";
        $result = $conn->query($donationQuery);
        $totalDonations = $result->fetch_assoc()['total_donations'] ?? 0;

        // Get active campaigns
        $campaignQuery = "SELECT COUNT(*) as active_campaigns FROM campaigns WHERE status = 4";
        $result = $conn->query($campaignQuery);
        $activeCampaigns = $result->fetch_assoc()['active_campaigns'];

        return [
            'users' => $totalUsers,
            'donations' => $totalDonations,
            'campaigns' => $activeCampaigns
        ];
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

?>