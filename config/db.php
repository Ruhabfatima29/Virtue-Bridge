<?php
// config/config.php
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    // Local development
    define('BASE_URL', 'http://localhost/Virtue Bridge/');
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Virtue Bridge/');
} else {
    // Production server
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    define('BASE_URL', $protocol . $host . '/');
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
$servername = "localhost";
$username = "root"; // default user for XAMPP
$password = ""; // default is empty for XAMPP
$dbname = "virtuebridge"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
}
?>
