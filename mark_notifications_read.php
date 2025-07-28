<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "your_database_name";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$sql = "UPDATE notifications SET is_read = 1 WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

echo json_encode(["status" => "success"]);
$conn->close();
?>
