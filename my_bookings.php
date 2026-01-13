<?php

session_start(); 
include 'dbconnect.php'; 

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = '<div class="message error">You must be logged in to view your bookings.</div>';
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';


if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>My Booking History</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="rooms.php">View Rooms</a>
        <span>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        <a href="logout.php">Logout</a>
    </nav>

    <?php echo $message; ?>

    <div class="booking-list">
        <?php
        $stmt = $conn->prepare("
            SELECT
                b.Id,
                b.RoomNo,
                r.room_type,
                b.Check_In_Date,
                b.Check_Out_Date,
                b.Total_Price,
                b.Booking_Status,
                b.Booked_At
            FROM
                Bookings b
            JOIN
                rooms r ON b.RoomNo = r.room_number
            WHERE
                b.Users_ID = ?
            ORDER BY
                b.Booked_At DESC
        ");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($booking = $result->fetch_assoc()) {
                $status_class = 'status-' . strtolower($booking['Booking_Status']);
                echo '<div class="booking-card">';
                echo '<h3>Booking ID: ' . htmlspecialchars($booking['Id']) . '</h3>';
                echo '<p><strong>Room:</strong> ' . htmlspecialchars($booking['RoomNo']) . ' (' . htmlspecialchars($booking['room_type']) . ')</p>';
                echo '<p><strong>Check-in:</strong> ' . htmlspecialchars($booking['Check_In_Date']) . '</p>';
                echo '<p><strong>Check-out:</strong> ' . htmlspecialchars($booking['Check_Out_Date']) . '</p>';
                echo '<p class="price"><strong>Total Price:</strong> $' . htmlspecialchars(number_format($booking['Total_Price'], 2)) . '</p>';
                echo '<p><strong>Status:</strong> <span class="' . $status_class . '">' . htmlspecialchars(ucfirst($booking['Booking_Status'])) . '</span></p>';
                echo '<p><strong>Booked On:</strong> ' . htmlspecialchars($booking['Booked_At']) . '</p>';

                // Allow cancellation only for 'pending' or 'confirmed' bookings that are in the future
                $current_date = date('Y-m-d');
                if (($booking['Booking_Status'] == 'pending' || $booking['Booking_Status'] == 'confirmed') && $booking['Check_In_Date'] > $current_date) {
                    echo '<form action="cancel_booking.php" method="POST" onsubmit="return confirm(\'Are you sure you want to cancel this booking?\');">';
                    echo '<input type="hidden" name="booking_id" value="' . htmlspecialchars($booking['Id']) . '">';
                    echo '<button type="submit" class="cancel-button">Cancel Booking</button>';
                    echo '</form>';
                } else if ($booking['Booking_Status'] == 'cancelled') {
                    echo '<p style="color: #dc3545; font-weight: bold;">This booking has been cancelled.</p>';
                } else if ($booking['Check_In_Date'] <= $current_date) {
                    echo '<p style="color: #6c757d;">This booking cannot be cancelled (past check-in date).</p>';
                }
                echo '</div>';
            }
        } else {
            echo '<p style="text-align: center;">You have no bookings yet. <a href="rooms.php">Book a room now!</a></p>';
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
