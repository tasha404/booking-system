<?php
$servername = "fdb1028.awardspace.net"; 
$username = "4637824_hotelbookingdb"; 
$password = "z@1jm@7K0%RF!4WB";
$dbname = "4637824_hotelbookingdb"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}
?>