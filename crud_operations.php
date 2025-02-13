<?php
// Database connection
$host = "localhost";
$db = "car_rental_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Car
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
    $model = $_POST['model'];
    $type = $_POST['type'];
    $license_plate = $_POST['license_plate'];
    $rental_price = $_POST['rental_price'];
    $status = "available";

    // Handle Image Upload
    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["car_image"]["name"]);
    $image_url = "";

    if (!empty($_FILES["car_image"]["tmp_name"]) && move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    } else {
        die("Error uploading image.");
    }

    // Insert into database
    $sql = "INSERT INTO cars (model, type, license_plate, rental_price, status, image_url) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdss", $model, $type, $license_plate, $rental_price, $status, $image_url);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error adding car: " . $conn->error;
    }
    $stmt->close();
}

// Handle Update Car
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_car'])) {
    $car_id = $_POST['car_id'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $license_plate = $_POST['license_plate'];
    $rental_price = $_POST['rental_price'];

    // Handle Image Upload
    $image_url = "";
    if (!empty($_FILES["car_image"]["name"])) {
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES["car_image"]["name"]);
        if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        } else {
            die("Error uploading image.");
        }
    }

    // Update Query
    if ($image_url) {
        $sql = "UPDATE cars SET model = ?, type = ?, license_plate = ?, rental_price = ?, image_url = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdsi", $model, $type, $license_plate, $rental_price, $image_url, $car_id);
    } else {
        $sql = "UPDATE cars SET model = ?, type = ?, license_plate = ?, rental_price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdi", $model, $type, $license_plate, $rental_price, $car_id);
    }

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating car: " . $conn->error;
    }
    $stmt->close();
}

// Handle Delete Car
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_car'])) {
    $car_id = $_POST['car_id'];

    // Delete Query
    $sql = "DELETE FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting car: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>
