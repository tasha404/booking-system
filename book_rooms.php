<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['room'])) {
    echo "Room not specified.";
    exit();
}

$room = htmlspecialchars($_GET['room']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book <?= $room ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Book <?= $room ?></h2>
<nav>
    <a href="rooms.php">Back to Rooms</a>
    <a href="index.php">Home</a>
    <span>Hello, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
    <a href="logout.php">Logout</a>
</nav>

<form action="confirm_booking.php" method="post">
    <input type="hidden" name="room" value="<?= $room ?>">
    <label>Check-in:
        <input type="date" name="checkin" required>
    </label><br><br>
    <label>Check-out:
        <input type="date" name="checkout" required>
    </label><br><br>
    <button type="submit" class="button">Confirm Booking</button>
</form>

</body>
</html>
