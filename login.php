<?php

session_start();
include 'dbconnect.php'; 
$message = ''; 

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = '<div class="message error">Both username and password are required.</div>';
    } else {
        // Prepare and execute statement to fetch user
        $stmt = $conn->prepare("SELECT users_id, users_name, users_password FROM users WHERE users_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
 
            if (password_verify($password, $user['users_password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['users_id'];
                $_SESSION['username'] = $user['users_name'];
                $_SESSION['message'] = '<div class="message success">Login successful!</div>';
                header("Location: rooms.php"); 
                exit();
            } else {
                $message = '<div class="message error">Invalid username or password.</div>';
            }
        } else {
            $message = '<div class="message error">Invalid username or password.</div>';
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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Login to Your Account</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="rooms.php">View Rooms</a>
    </nav>

    <?php echo $message; ?>

    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
