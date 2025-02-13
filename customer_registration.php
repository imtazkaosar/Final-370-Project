<?php
ob_start(); // Start output buffering

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $national_id = $_POST['national_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $host = "localhost";
    $db = "car_rental_db";
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check for duplicate email or username
    $check = $conn->prepare("SELECT * FROM customers WHERE email = ? OR username = ? OR national_id = ?");
    $check->bind_param("sss", $email, $username, $national_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Email, username, or national ID already exists!");
    }

    // Insert customer into database
    $sql = "INSERT INTO customers (name, email, national_id, username, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $national_id, $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: customer_login.html"); // Redirect to login page after successful registration
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

ob_end_flush(); // End output buffering
?>
