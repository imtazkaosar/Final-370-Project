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
    <title>Rented Cars</title>
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

        .btn-available {
            background: #0fd53a;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-available:hover {
            background: #0cb729;
        }
    </style>
</head>
<body>
    <div class="nav-bar">
        <div class="logo">Car Rental Admin</div>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="admin_dashboard.php">Manage Cars</a></li>
            <li><a href="manage_customers.php">Manage Customers</a></li>
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

    // Handle "Available" button request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make_available'])) {
        $car_id = $_POST['car_id'];
        $update_query = "UPDATE cars SET status = 'available' WHERE id = '$car_id'";
        $conn->query($update_query);
    }

    // Fetch rented car data
    $sql_rented_cars = "
        SELECT r.id, r.car_id, c.model AS car_model, r.renter_name, r.rent_date, r.return_date, r.TrxID 
        FROM rentals r
        INNER JOIN cars c ON r.car_id = c.id
        WHERE c.status = 'rented'
    ";

    $rented_cars = $conn->query($sql_rented_cars);
    ?>

    <div class="dashboard-container">
        <h2>Rented Cars</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Car Model</th>
                    <th>Renter Name</th>
                    <th>Rent Date</th>
                    <th>Return Date</th>
                    <th>Transaction ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rented_cars && $rented_cars->num_rows > 0): ?>
                    <?php while ($row = $rented_cars->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['car_model']; ?></td>
                            <td><?php echo $row['renter_name']; ?></td>
                            <td><?php echo $row['rent_date']; ?></td>
                            <td><?php echo $row['return_date']; ?></td>
                            <td><?php echo $row['TrxID']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="car_id" value="<?php echo $row['car_id']; ?>">
                                    <button type="submit" name="make_available" class="btn-available">Make Available</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No rented cars found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
