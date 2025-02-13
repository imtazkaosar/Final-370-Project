<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['customer'])) {
    echo json_encode([
        "loggedIn" => true,
        "customer" => [
            "id" => $_SESSION['customer']['id'],
            "username" => $_SESSION['customer']['username']
        ]
    ]);
} else {
    echo json_encode(["loggedIn" => false]);
}
?>
