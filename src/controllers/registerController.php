<?php
// Include database connection
//include '../../config/db.php';
require_once __DIR__ . '/../../config/db.php';

function registerUser($conn, $username, $email, $password) {
    try {
        // Check if the username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            return "Username or email already exists!";
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $role = 1; // Default role for a new user is 'creator'
        $stmt->bind_param("sssi", $username, $email, $hashedPassword, $role);
        $stmt->execute();

        return "Registration successful!";
    } catch (mysqli_sql_exception $e) {
        return "Error: " . $e->getMessage();
    }
}

// Handle POST request for registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($email) || empty($password)) {
            echo "All fields are required!";
        } else {
            $response = registerUser($conn, $username, $email, $password);
            echo $response;
        }
    } else {
        echo "Invalid input!";
    }
}
?>
