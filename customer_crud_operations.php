<?php
// Database connection
$host = "localhost";
$db = "car_rental_db"; // Replace with your database name
$user = "root"; // Replace with your database username
$pass = ""; // Replace with your database password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Customer
if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prevent duplicate email or username
    $check = $conn->prepare("SELECT * FROM customers WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Error: Email or Username already exists.";
    } else {
        $sql = "INSERT INTO customers (name, email, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $username, $password);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
        } else {
            echo "Error adding customer: " . $conn->error;
        }
        $stmt->close();
    }
    $check->close();
}

// Delete Customer
if (isset($_POST['delete_customer'])) {
    $customer_id = $_POST['customer_id'];

    $sql = "DELETE FROM customers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error deleting customer: " . $conn->error;
    }
    $stmt->close();
}

// Update Customer
if (isset($_POST['update_customer'])) {
    $customer_id = $_POST['customer_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Update customer information
    $sql = "UPDATE customers SET name = ?, email = ?, username = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $username, $customer_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error updating customer: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>
