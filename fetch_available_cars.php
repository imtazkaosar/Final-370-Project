<?php
$host = "localhost";
$db = "car_rental_db";
$user = "root";
$pass = "";

// Database connection
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to count available cars
$sql = "SELECT COUNT(*) AS available_cars FROM cars WHERE status = 'available'";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
