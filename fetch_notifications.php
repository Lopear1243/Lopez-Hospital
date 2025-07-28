<?php
session_start();
include 'includes/db_connect.php';

$username = $_SESSION['user'] ?? '';

$results = [];

if (!empty($username)) {
  $stmt = $conn->prepare("SELECT message FROM notifications WHERE username = ? ORDER BY created_at DESC LIMIT 10");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $results[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($results);
?>
