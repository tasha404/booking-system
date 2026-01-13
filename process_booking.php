<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: login.php");
    exit();
}

$users_id = $_SESSION['users_id'];
$room_name = $_POST['room_name'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];

// Basic date check (optional)
if ($checkin >= $checkout) {
    echo "<p class='error'>Check-out date must be after check-in date.</p>";
    echo "<p><a href='rooms.php'>Go back</a></p>";
    exit();
}

// Save to database
$stmt = $conn->prepare("INSERT INTO bookings (user_id, room_name, checkin_date, checkout_date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $room_name, $checkin, $checkout);

if ($stmt->execute()) {
    echo "<div class='message success'>Booking successful!</div>";
} else {
    echo "<div class='message error'>Booking failed. Please try again.</div>";
}

$stmt->close();
$conn->close();

echo "<p><a href='rooms.php'>Back to Rooms</a></p>";
?>
