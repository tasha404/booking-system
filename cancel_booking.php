<?php
session_start(); 
include 'dbconnect.php'; 
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = '<div class="message error">You must be logged in to cancel a booking.</div>';
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT Booking_Status, Check_In_Date FROM Bookings WHERE Id = ? AND Users_ID = ?");
    $stmt->bind_param("ss", $booking_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    if ($booking) {
        $current_date = date('Y-m-d');
        if (($booking['Booking_Status'] == 'pending' || $booking['Booking_Status'] == 'confirmed') && $booking['Check_In_Date'] > $current_date) {
            $update_stmt = $conn->prepare("UPDATE Bookings SET Booking_Status = 'cancelled' WHERE Id = ? AND Users_ID = ?");
            $update_stmt->bind_param("ss", $booking_id, $user_id);

            if ($update_stmt->execute()) {
                $_SESSION['message'] = '<div class="message success">Booking ' . htmlspecialchars($booking_id) . ' has been successfully cancelled.</div>';
            } else {
                $_SESSION['message'] = '<div class="message error">Error cancelling booking: ' . $update_stmt->error . '</div>';
            }
            $update_stmt->close();
        } else {
            $_SESSION['message'] = '<div class="message error">This booking cannot be cancelled (status: ' . htmlspecialchars($booking['Booking_Status']) . ' or check-in date has passed).</div>';
        }
    } else {
        $_SESSION['message'] = '<div class="message error">Booking not found or you do not have permission to cancel it.</div>';
    }
    $conn->close();
} else {
    $_SESSION['message'] = '<div class="message error">Invalid request to cancel booking.</div>';
}

header("Location: my_bookings.php"); 
exit();
?>
