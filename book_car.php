<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture form data
$car_id = isset($_POST['car_id']) ? $_POST['car_id'] : null;
$car_name = isset($_POST['car_name']) ? $_POST['car_name'] : null;
$return_date = isset($_POST['return_date']) ? $_POST['return_date'] : null;

// Validate form data
if (!$car_id || !$car_name || !$return_date) {
    echo "All fields are required!";
    exit();
}

// Check car availability
$sql_check = "SELECT * FROM cars WHERE id='$car_id' AND `status`='available'";
$result = $conn->query($sql_check);

if ($conn->error) {
    echo "SQL Error: " . $conn->error;
    exit();
}

if ($result->num_rows > 0) {
    // Fetch car details
    $car_details = $result->fetch_assoc();

    // Retrieve renter's name from session
    if (isset($_SESSION['customer'])) {
        $renter_name = $_SESSION['customer']['username'];
    } else {
        echo "User not logged in.";
        exit();
    }

    // Generate a unique transaction ID
    $trx_id = "TRX" . strtoupper(uniqid());

    // Get current date as rent date
    $rent_date = date("Y-m-d");

    // Insert rental record into the `rentals` table
    $sql_rent = "INSERT INTO rentals (car_id, renter_name, rent_date, return_date, TrxID) 
                 VALUES ('$car_id', '$renter_name', '$rent_date', '$return_date', '$trx_id')";

    // Update car status in the `cars` table
    $sql_update = "UPDATE cars SET `status`='rented' WHERE id='$car_id'";

    if ($conn->query($sql_rent) === TRUE && $conn->query($sql_update) === TRUE) {
        // Redirect to success page or display a success message
        header('Location: payment_success.html'); // Replace with your success page
        exit();
    } else {
        echo "Error processing your request: " . $conn->error;
        exit();
    }
} else {
    echo "Car is not available!";
    exit();
}

// Close the database connection
$conn->close();
?>
