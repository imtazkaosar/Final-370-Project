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

// Fetch all cars including the type column
$sql = "SELECT id, model, type, license_plate, rental_price, status, image_url FROM cars";
$result = $conn->query($sql);

$cars = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($cars);

$conn->close();
?>
