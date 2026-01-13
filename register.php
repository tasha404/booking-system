<?php
session_start(); 
include 'dbconnect.php'; 

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $users_id = trim($_POST['users_id']);
    $users_name = trim($_POST['username']);
    $users_phone = trim($_POST['phone']);
    $users_email = trim($_POST['email']);
    $users_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($users_id) || empty($users_name) || empty($users_phone) || empty($users_email) || empty($users_password) || empty($confirm_password)) {
        $message = '<div class="message error">All fields are required.</div>';
    } elseif ($users_password !== $confirm_password) {
        $message = '<div class="message error">Passwords do not match.</div>';
    } elseif (strlen($users_password) < 6) {
        $message = '<div class="message error">Password must be at least 6 characters long.</div>';
    } else {
        $stmt = $conn->prepare("SELECT users_id FROM users WHERE users_id = ? OR users_name = ? OR users_email = ?");
        $stmt->bind_param("sss", $users_id, $users_name, $users_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = '<div class="message error">User  ID, Username, or Email already exists.</div>';
        } else {
            $hashed_password = password_hash($users_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (users_id, users_name, users_phone, users_email, users_password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $users_id, $users_name, $users_phone, $users_email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['message'] = '<div class="message success">Registration successful! Please log in.</div>';
                header("Location: login.php");
                exit();
            } else {
                $message = '<div class="message error">Error: ' . $stmt->error . '</div>';
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Register for an Account</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="rooms.php">View Rooms</a>
    </nav>

    <?php echo $message; ?>

    <form action="register.php" method="POST">
        <input type="text" name="users_id" placeholder="User  ID" required> <!-- New input for users_id -->
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
