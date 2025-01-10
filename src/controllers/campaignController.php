<?php
//include('../../config/db.php');
require_once __DIR__ . '/../../config/db.php';

// Check if the function is not already defined
if (!function_exists('fetchRecentProjects')) {
    function fetchRecentProjects($limit = 2) {
        global $conn;
        $query = "SELECT c.*, l.value as category_type 
                  FROM campaigns c 
                  LEFT JOIN lookup l ON c.campaign_type = l.id 
                  ORDER BY c.created_at DESC 
                  LIMIT ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

// Similarly wrap the new fetchTopDonors function
if (!function_exists('fetchTopDonors')) {
    function fetchTopDonors($limit = 5) {
        global $conn;
        $query = "SELECT 
            d.email,
            d.phone_number,
            COALESCE(SUM(don.amount), 0) as total_donated,
            COUNT(don.donation_id) as donation_count
        FROM donors d
        JOIN donations don ON d.donor_id = don.donor_table_id
        GROUP BY d.donor_id
        ORDER BY total_donated DESC
        LIMIT ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function fetchAllCampaigns()
{
    global $conn;
    $query = "SELECT c.campaign_id, c.title, c.description, c.goal_amount, c.current_amount, l.value AS category_type 
              FROM campaigns c 
              JOIN lookup l ON c.campaign_type = l.id 
              ORDER BY c.created_at DESC";
    $result = $conn->query($query);
    $campaigns = $result->fetch_all(MYSQLI_ASSOC);
    return $campaigns;
}
?>
