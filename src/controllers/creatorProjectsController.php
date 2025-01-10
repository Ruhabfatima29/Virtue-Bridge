<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 1) {
    header('Location: ../../src/views/home.php');
    exit();
}

require_once __DIR__ . '/../../config/db.php';

// Declare $conn as global inside functions that need it
function getCampaignById($campaign_id)
{
    global $conn; // Make $conn global

    // Prepare the SQL query to fetch the campaign details
    $stmt = $conn->prepare("SELECT * FROM campaigns WHERE campaign_id = ?");
    if ($stmt === false) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    // Bind the campaign_id parameter
    $stmt->bind_param("i", $campaign_id);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch and return the campaign details
    if ($row = $result->fetch_assoc()) {
        return $row;
    } else {
        return null;
    }
}

// Function to get donations for a specific campaign
function getDonationsByCampaignId($campaign_id)
{
    global $conn; // Make $conn global

    // Prepare the SQL query to fetch donations
    $stmt = $conn->prepare("SELECT * FROM donations WHERE campaign_id = ?");
    if ($stmt === false) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    // Bind the campaign_id parameter
    $stmt->bind_param("i", $campaign_id);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch all donations and return them as an array
    $donations = [];
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }

    return $donations;
}

// Function to get campaigns by user ID
function getCampaignsByUserId()
{
    global $conn; // Make $conn global
    // Get the logged-in user's ID
    $userId = $_SESSION['user_id'];
    $query = "
        SELECT c.campaign_id, c.title, c.description, l.value AS status, c.goal_amount, c.current_amount
        FROM campaigns c
        LEFT JOIN lookup l ON c.status = l.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Function to get donations by campaign ID
function getDonationsByCampaignIdForResults($campaignId)
{
    global $conn; // Make $conn global

    $donationQuery = "
        SELECT d.donation_id, d.amount, donors.email AS donor_email, donors.phone_number AS donor_phone, d.donation_date
        FROM donations d
        LEFT JOIN donors ON d.donor_table_id = donors.donor_id
        WHERE d.campaign_id = ?
        ORDER BY d.donation_date DESC
    ";

    $donationStmt = $conn->prepare($donationQuery);
    $donationStmt->bind_param('i', $campaignId);

    if ($donationStmt->execute()) {
        $donationResult = $donationStmt->get_result();
        return $donationResult->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Get the logged-in user's ID
$userId = $_SESSION['user_id'];

// Fetch the projects for the logged-in creator with status text from the lookup table
$query = "
    SELECT c.campaign_id, c.title, c.description, l.value AS status, c.goal_amount, c.current_amount 
    FROM campaigns c
    LEFT JOIN lookup l ON c.status = l.id
    WHERE c.user_id = ? 
    ORDER BY c.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $projects = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $projects = [];
    $_SESSION['error'] = "Failed to fetch projects.";
}

// Close the statement after use
$stmt->close();
