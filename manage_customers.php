<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login_page.html"); // Redirect to login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .nav-bar {
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .nav-bar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }

        .nav-bar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .nav-bar ul li {
            margin-left: 20px;
        }

        .nav-bar ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 1rem;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .nav-bar ul li a:hover {
            background: linear-gradient(135deg, #ff6a00, #ee0979);
            transform: scale(1.1);
        }

        .dashboard-container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            margin-top: 80px;
        }

        .dashboard-container h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            background: rgba(255, 255, 255, 0.2);
        }

        table th {
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
        }

        table tr:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        form {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        form input, form button {
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }

        form input {
            background: rgba(255, 255, 255, 0.8);
            color: #333;
        }

        form button {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        form button:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }

    </style>
</head>
<body>
    <div class="nav-bar">
        <div class="logo">Car Rental Admin</div>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="rented_car.php">Rented car</a></li>
            <li><a href="admin_dashboard.php">Manage Cars</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

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

    // Fetch customer data
    $sql_customers = "SELECT * FROM customers";
    $customers = $conn->query($sql_customers);

    if (!$customers) {
        die("Error fetching customers: " . $conn->error);
}

    ?>

    <div class="dashboard-container">
        <h2>Manage Customers</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>National ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $customers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['national_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td>
                            <form method="POST" action="customer_crud_operations.php" style="display:inline-block;">
                                <input type="hidden" name="customer_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_customer">Delete</button>
                            </form>
                            <form method="POST" action="customer_crud_operations.php" style="display:inline-block;">
                                <input type="hidden" name="customer_id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
                                <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
                                <input type="text" name="national_id" value="<?php echo $row['national_id']; ?>" required>
                                <input type="text" name="username" value="<?php echo $row['username']; ?>" required>
                                <button type="submit" name="update_customer">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
