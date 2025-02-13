<?php
// Database connection
$host = "localhost";
$db = "car_rental_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Count rented cars
$sql = "SELECT COUNT(*) as rented_cars FROM rentals";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['rented_cars' => $row['rented_cars']]);
} else {
    echo json_encode(['rented_cars' => 0]);
}

$conn->close();
?>
