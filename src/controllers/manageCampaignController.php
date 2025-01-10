<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 3) {
    header('Location: ../../src/views/home.php');
    exit();
}

//include('../../config/db.php');
require_once __DIR__ . '/../../config/db.php';

// Determine the filter based on the request
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Prepare the query dynamically based on the filter
if ($statusFilter === 'active') {
    $campaignsQuery = "
        SELECT campaigns.*, users.username AS creator_name 
        FROM campaigns 
        LEFT JOIN users ON campaigns.user_id = users.user_id 
        WHERE campaigns.status = 4"; // Active campaigns
} elseif ($statusFilter === 'paused') {
    $campaignsQuery = "
        SELECT campaigns.*, users.username AS creator_name 
        FROM campaigns 
        LEFT JOIN users ON campaigns.user_id = users.user_id 
        WHERE campaigns.status = 5"; // Paused campaigns
} elseif ($statusFilter === 'completed') {
    $campaignsQuery = "
        SELECT campaigns.*, users.username AS creator_name 
        FROM campaigns 
        LEFT JOIN users ON campaigns.user_id = users.user_id 
        WHERE campaigns.status = 6
        "; // Completed campaigns
} else {
    $campaignsQuery = "
        SELECT campaigns.*, users.username AS creator_name 
        FROM campaigns 
        LEFT JOIN users ON campaigns.user_id = users.user_id"; // All campaigns
}

// Execute the query
$allCampaignsResult = $conn->query($campaignsQuery);

if (!$allCampaignsResult) {
    die("Error fetching campaigns: " . $conn->error);
}

// Activate a campaign if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campaign_id'], $_POST['action'])) {
    $campaignId = intval($_POST['campaign_id']);
    $action = $_POST['action'];

    // Determine the new status based on the action
    $newStatus = ($action === 'activate') ? 4 : 5; // 4 = Active, 5 = Paused

    $updateQuery = "UPDATE campaigns SET status = ? WHERE campaign_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $newStatus, $campaignId);

    if ($stmt->execute()) {
        $_SESSION['message'] = $action === 'activate'
            ? "Campaign activated successfully!"
            : "Campaign paused successfully!";
    } else {
        $_SESSION['error'] = "Failed to update the campaign status.";
    }

    $stmt->close();
    header('Location: ../views/manageCampaigns.php');
    exit();
}


$conn->close();
?>
