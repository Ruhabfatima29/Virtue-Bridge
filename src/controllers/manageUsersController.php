<?php

require_once __DIR__ . '/../../config/db.php';

// Fetch all users
    $query = "SELECT * FROM users";
    $result = $conn->query($query);
    if (!$result) {
        die("Error fetching users: " . $conn->error);
    }


// Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $addQuery = "INSERT INTO users (username, email, role, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($addQuery);
    $stmt->bind_param("ssis", $username, $email, $role, $password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add user.";
    }
    header('Location: ../views/manageUsers.php');
    exit();
}

// Edit User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $editQuery = "UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("ssii", $username, $email, $role, $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update user.";
    }
    header('Location: ../views/manageUsers.php');
    exit();
}


// Delete User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];

    $deleteQuery = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete user.";
    }
    header('Location: ../views/manageUsers.php');
    exit();
}

?>
