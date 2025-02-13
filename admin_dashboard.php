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
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('image/background.jpg') no-repeat center center fixed;
            background-size: cover;
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

        header {
            width: 100%;
            max-width: 1200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        header h1 {
            font-size: 2rem;
        }

        header a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            background: linear-gradient(135deg, #ff6a00, #ee0979);
            padding: 10px 20px;
            border-radius: 5px;
            transition: 0.3s;
        }

        header a:hover {
            background: linear-gradient(135deg, #ee0979, #ff6a00);
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

        img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="nav-bar">
        <div class="logo">Car Rental Admin</div>
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="rented_car.php">Rented car</a></li>
            <li><a href="manage_customers.php">Manage Customer</a></li>
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

    // Fetch car data
    $sql_cars = "SELECT * FROM cars";
    $cars = $conn->query($sql_cars);
    ?>

    <div class="dashboard-container">
        <h2>Manage Cars</h2>

        <!-- Add Car Form -->
        <form method="POST" action="crud_operations.php" enctype="multipart/form-data">
            <h3>Add New Car</h3>
            <input type="text" name="model" placeholder="Model" required>
            <select name="type" required>
                <option value="">Select Type</option>
                <option value="Sedan">Sedan</option>
                <option value="SUV">SUV</option>
                <option value="Convertible">Convertible</option>
                <option value="Hatchback">Hatchback</option>
            </select>
            <input type="text" name="license_plate" placeholder="License Plate" required>
            <input type="number" step="0.01" name="rental_price" placeholder="Rental Price" required>
            <input type="file" name="car_image" accept="image/*" required>
            <button type="submit" name="add_car">Add Car</button>
        </form>

        <!-- Cars Table -->
        <h3>Cars List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Model</th>
                    <th>Type</th>
                    <th>License Plate</th>
                    <th>Rental Price</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $cars->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['model']; ?></td>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $row['license_plate']; ?></td>
                        <td><?php echo $row['rental_price']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['model']; ?>"></td>
                        <td>
                            <!-- Delete Car Form -->
                            <form method="POST" action="crud_operations.php" style="display:inline-block;">
                                <input type="hidden" name="car_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_car">Delete</button>
                            </form>

                            <!-- Update Car Form -->
                            <form method="POST" action="crud_operations.php" enctype="multipart/form-data" style="display:inline-block;">
                                <input type="hidden" name="car_id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="model" value="<?php echo $row['model']; ?>" required>
                                <select name="type" required>
                                    <option value="Sedan" <?php echo $row['type'] === 'Sedan' ? 'selected' : ''; ?>>Sedan</option>
                                    <option value="SUV" <?php echo $row['type'] === 'SUV' ? 'selected' : ''; ?>>SUV</option>
                                    <option value="Convertible" <?php echo $row['type'] === 'Convertible' ? 'selected' : ''; ?>>Convertible</option>
                                    <option value="Hatchback" <?php echo $row['type'] === 'Hatchback' ? 'selected' : ''; ?>>Hatchback</option>
                                </select>
                                <input type="text" name="license_plate" value="<?php echo $row['license_plate']; ?>" required>
                                <input type="number" step="0.01" name="rental_price" value="<?php echo $row['rental_price']; ?>" required>
                                <input type="file" name="car_image" accept="image/*">
                                <button type="submit" name="update_car">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
