<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $host = "localhost";
    $db = "car_rental_db";
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch user details
    $sql = "SELECT * FROM customers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Store user data in session
            $_SESSION['customer'] = [
                'id' => $row['id'],
                'username' => $row['username']
            ];
            header("Location: cars.html"); // Redirect to cars.html
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this username.";
    }

    $stmt->close();
    $conn->close();
}
?>
