<?php
// Start session to manage flash messages
session_start();
include('../../config/db.php');

// Function to fetch all communities
function fetch_all_communities() {
    global $conn;
    $query = "SELECT * FROM local_communities";
    $result = $conn->query($query);

    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        error_log("Error fetching communities: " . $conn->error);
        return [];
    }
}

// Fetch communities when the page is loaded
$communities = fetch_all_communities();

// Handle Add/Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $community_id = $_POST['community_id'] ?? null;
    $name = $_POST['name'];
    $description = $_POST['description'];
    $city = $_POST['city'];
    $area = $_POST['area'];
    $literacy_rate = $_POST['literacy_rate'];

    if ($_POST['action'] == 'add') {
        $query = "INSERT INTO local_communities (name, description, city, area, literacy_rate) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssd', $name, $description, $city, $area, $literacy_rate);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Community added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error adding community: " . $stmt->error;
            $_SESSION['message_type'] = "error";
        }
    } elseif ($_POST['action'] == 'update') {
        $query = "UPDATE local_communities 
                  SET name = ?, description = ?, city = ?, area = ?, literacy_rate = ?
                  WHERE community_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssdi', $name, $description, $city, $area, $literacy_rate, $community_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Community updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating community: " . $stmt->error;
            $_SESSION['message_type'] = "error";
        }
    }

    header('Location: ../views/addLocalCommunities.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $community_id = $_GET['delete'];
    $query = "DELETE FROM local_communities WHERE community_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $community_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Community deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting community: " . $stmt->error;
        $_SESSION['message_type'] = "error";
    }

    header('Location: ../views/addLocalCommunities.php');
    exit;
}

if (isset($_GET['edit'])) {
    $community_id = $_GET['edit'];
    $query = "SELECT * FROM local_communities WHERE community_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $community_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $community = $result->fetch_assoc();

    // Make the community data available in the session
    $_SESSION['edit_community'] = $community;

    header('Location: ../views/addLocalCommunities.php');
    exit;
}

?>
