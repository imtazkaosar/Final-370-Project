<?php
session_start();

// Redirect to login if customer is not logged in
if (!isset($_SESSION['customer'])) {
    header("Location: customer_login.html");
    exit();
}

$customerId = $_SESSION['customer']['id'];

// Database connection
$host = "localhost";
$db = "car_rental_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    // Insert rating into the database
    $stmt = $conn->prepare("INSERT INTO ratings (customer_id, car_id, rating, review) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $customerId, $carId, $rating, $review);

    if ($stmt->execute()) {
        $successMessage = "Thank you for your feedback!";
    } else {
        $errorMessage = "An error occurred. Please try again.";
    }

    $stmt->close();
}

// Fetch cars rented by the customer
$sql = "SELECT rentals.car_id, cars.model FROM rentals
        JOIN cars ON rentals.car_id = cars.id
        WHERE rentals.renter_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['customer']['username']); // Assuming username is stored in the session
$stmt->execute();
$rentedCars = $stmt->get_result();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Your Rental</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        select, textarea, input[type="submit"] {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        textarea {
            height: 100px;
        }

        input[type="submit"] {
            background: #2575fc;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        input[type="submit"]:hover {
            background: #1b5bbf;
        }

        .stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .stars input {
            display: none;
        }

        .stars label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            margin-left: 5px;
        }

        .stars input:checked ~ label,
        .stars label:hover,
        .stars label:hover ~ label {
            color: #ff9800;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #28a745;
        }

        .error {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rate Your Rental</h1>

        <?php if (isset($successMessage)): ?>
            <p class="message"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if ($rentedCars->num_rows > 0): ?>
            <form method="POST" action="">
                <label for="car_id">Select Car:</label>
                <select name="car_id" id="car_id" required>
                    <?php while ($car = $rentedCars->fetch_assoc()): ?>
                        <option value="<?php echo $car['car_id']; ?>">
                            <?php echo $car['model']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label>Rating:</label>
                <div class="stars">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                </div>

                <label for="review">Review (optional):</label>
                <textarea name="review" id="review" placeholder="Write your feedback here..."></textarea>

                <input type="submit" value="Submit">
            </form>
        <?php else: ?>
            <p>No cars available for rating.</p>
        <?php endif; ?>
    </div>
</body>
</html>
