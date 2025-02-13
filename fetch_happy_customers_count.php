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

// Count happy customers
$sql = "SELECT COUNT(DISTINCT customer_id) AS happy_customers FROM ratings WHERE rating > 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['happy_customers' => $row['happy_customers']]);
} else {
    echo json_encode(['happy_customers' => 0]);
}

$conn->close();
?>
