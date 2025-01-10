<?php
// dashboardController.php
include('../../config/db.php');

/**
 * Initialize and validate user session
 * @return int|bool Returns user_id if valid, false if not
 */
function initializeSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    return $_SESSION['user_id'];
}

/**
 * Get campaign statistics for a creator
 * @param mysqli $conn Database connection
 * @param int $creator_id Creator's user ID
 * @return array|null Campaign statistics
 */
function getCampaignStats($conn, $creator_id) {
    try {
        $query = "SELECT 
            COUNT(*) as total_campaigns,
            SUM(CASE WHEN status = 4 THEN 1 ELSE 0 END) as active_campaigns,
            SUM(current_amount) as total_raised,
            SUM(goal_amount) as total_goals
            FROM campaigns 
            WHERE user_id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $creator_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    } catch (Exception $e) {
        error_log('Error fetching campaign stats: ' . $e->getMessage());
        return null;
    }
}

/**
 * Get recent donations for a creator's campaigns
 * @param mysqli $conn Database connection
 * @param int $creator_id Creator's user ID
 * @param int $limit Number of recent donations to fetch
 * @return array Recent donations
 */
function getRecentDonations($conn, $creator_id, $limit = 5) {
    try {
        $query = "SELECT d.*, c.title as campaign_title 
            FROM donations d 
            JOIN campaigns c ON d.campaign_id = c.campaign_id 
            WHERE c.user_id = ? 
            ORDER BY d.donation_date DESC 
            LIMIT ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $creator_id, $limit);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log('Error fetching recent donations: ' . $e->getMessage());
        return [];
    }
}?>