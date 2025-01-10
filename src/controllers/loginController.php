<?php
session_start();
//include('../../config/db.php');
require_once __DIR__ . '/../../config/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a query to check if the user exists
    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Redirect based on user role
            if ($role == 3) {
                header("Location: ../views/adminDashboard.php");
            } elseif ($role == 1) {
                header("Location: ../views/creatorDashboard.php");
            } else {
                echo "Invalid user role.";
            }
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with that username.";
    }
    $stmt->close();
    $conn->close();
}
?>