<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../src/views/home.php');
    exit();
}

require_once __DIR__ . '/../../config/db.php';

// Function to fetch community data
function getCommunityData($conn) {
    $query = "SELECT community_id, name, literacy_rate, area, city 
              FROM local_communities 
              WHERE literacy_rate IS NOT NULL 
              ORDER BY name";
    
    $result = $conn->query($query);
    $data = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'community_id' => $row['community_id'],
                'name' => $row['name'],
                'literacy_rate' => $row['literacy_rate'],
                'area' => $row['area'],
                'city' => $row['city']
            ];
        }
    }
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $campaignName = $_POST['campaignName'];
    $goalAmount = $_POST['goalAmount'];
    $status = 5; // Default status for "pending"
    $type = $_POST['type'];
    $community = $_POST['community'];
    $description = $_POST['description'];

    // Check if the campaign name already exists
    $checkQuery = $conn->prepare("SELECT campaign_id FROM campaigns WHERE title = ?");
    $checkQuery->bind_param("s", $campaignName);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        echo "<script>
                alert('Error: A campaign with this name already exists. Please choose a different name.');
                window.location.href = '../views/addCampaign.php';
              </script>";
    } else {
        // Insert campaign data into the database
        $stmt = $conn->prepare(
            "INSERT INTO campaigns (user_id, title, description, goal_amount, current_amount, status, campaign_type, local_community_id, created_at, updated_at)
             VALUES (?, ?, ?, ?, 0.00, ?, ?, ?, NOW(), NOW())"
        );
        $stmt->bind_param("issiiis", $userId, $campaignName, $description, $goalAmount, $status, $type, $community);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Campaign added successfully!');
                    window.location.href = '../views/addCampaign.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . addslashes($stmt->error) . "');
                  </script>";
        }

        $stmt->close();
    }

    $checkQuery->close();
    $conn->close();
} else {
    // Fetch campaign types
    $typeQuery = "SELECT id, value FROM lookup WHERE category = 'Campaign Type'";
    $typeResult = $conn->query($typeQuery);
    $types = [];
    if ($typeResult->num_rows > 0) {
        while ($row = $typeResult->fetch_assoc()) {
            $types[] = $row;
        }
    }

    // Fetch complete community data for both form and chart
    $communities = getCommunityData($conn);
    
    // Calculate statistics for the dashboard
    $totalCommunities = count($communities);
    $avgLiteracyRate = 0;
    
    if ($totalCommunities > 0) {
        $totalRate = array_reduce($communities, function($carry, $item) {
            return $carry + (float)($item['literacy_rate'] ?? 0);
        }, 0);
        $avgLiteracyRate = round($totalRate / $totalCommunities, 1);
    }

    // Add these variables to be used in the view
    $viewData = [
        'types' => $types,
        'communities' => $communities,
        'stats' => [
            'totalCommunities' => $totalCommunities,
            'avgLiteracyRate' => $avgLiteracyRate
        ]
    ];

    // Include the form view with the data
    include '../views/addCampaign.php';
}
?>