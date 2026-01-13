<?php

session_start(); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Hotel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to Our Hotel Booking System</h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="rooms.php">View Rooms</a>
            <a href="my_bookings.php">My Bookings</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
            <a href="rooms.php">View Rooms</a>
        <?php endif; ?>
    </nav>
    <p style="text-align: center; margin-top: 50px;">
        Find the perfect room for your stay with ease.
    </p>
</body>
</html>
