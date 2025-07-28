<?php
include 'includes/db_connect.php';

$username = $_POST['username'] ?? ''; // <-- match the form name
$message = $_POST['message'] ?? '';

if ($username && $message) {
    $stmt = $conn->prepare("INSERT INTO notifications (username, message, is_read) VALUES (?, ?, 0)");
    $stmt->bind_param("ss", $username, $message);

    if ($stmt->execute()) {
        echo "✅ Message sent to $username.";
    } else {
        echo "❌ Failed to send message.";
    }
} else {
    echo "❗ Both fields are required.";
}
?>
