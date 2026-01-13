<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Available Rooms</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Our Available Rooms</h2>
<nav>
    <a href="index.php">Home</a>
    <span>Hello, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
    <a href="my_bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="room-container">

    <!-- Room 401 -->
    <div class="room-card">
        <h3>Room 401 - standard</h3>
        <p>Capacity: 3 people</p>
        <p>single bed</p>
        <p class="price">RM60.00 / night</p>
        <form action="book_rooms.php" method="get">
            <input type="hidden" name="room" value="Room 401">
            <button type="submit" class="button">Book Now</button>
        </form>
    </div>

    <!-- Room 737 -->
    <div class="room-card">
        <h3>Room 737 - Family</h3>
        <p>Capacity: 6 people</p>
        <p>1 queen bed, 2 single beds</p>
        <p class="price">RM100.00 / night</p>
        <form action="book_rooms.php" method="get">
            <input type="hidden" name="room" value="Room 737">
            <button type="submit" class="button">Book Now</button>
        </form>
    </div>

</div>

</body>
</html>
