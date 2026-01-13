<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: login.php");
    exit();
}

// Validate POST data
if (!isset($_POST['room'], $_POST['checkin'], $_POST['checkout'])) {
    echo "Missing booking data.";
    exit();
}

$room = $_POST['room']; // e.g., 401
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$users_id = $_SESSION['users_id'];

// Calculate number of nights
$checkin_date = new DateTime($checkin);
$checkout_date = new DateTime($checkout);
$nights = $checkin_date->diff($checkout_date)->days;

if ($nights < 1) {
    echo "Check-out must be at least one night after check-in.";
    exit();
}

// Get price per night from the room table
$conn = new mysqli("localhost", "root", "", "hotelsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT price_per_night FROM rooms WHERE room_number = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$stmt->bind_result($price_per_night);
if ($stmt->fetch()) {
    $total_price = $price_per_night * $nights;
} else {
    echo "Room not found.";
    exit();
}
$stmt->close();

// Generate booking ID
$booking_id = uniqid("BKG");

// Insert booking
$stmt = $conn->prepare("
    INSERT INTO bookings (Id, Users_ID, RoomNo, Check_In_Date, Check_Out_Date, Total_Price)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("sssssd", $booking_id, $users_id, $room, $checkin, $checkout, $total_price);
$stmt->execute();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Booking Confirmed</h2>
<nav>
    <a href="index.php">Home</a>
    <a href="rooms.php">View More Rooms</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>
</nav>

<p>Thank you! Your booking for Room <strong><?= htmlspecialchars($room) ?></strong> is confirmed.</p>
<p>Booking ID: <strong><?= $booking_id ?></strong></p>
<p>Check-in: <strong><?= $checkin ?></strong></p>
<p>Check-out: <strong><?= $checkout ?></strong></p>
<p>Total: <strong>RM<?= number_format($total_price, 2) ?></strong></p>

</body>
</html>
