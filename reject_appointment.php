<?php
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];

  // First get the username
  $stmt = $conn->prepare("SELECT username FROM appointments WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->bind_result($username);
  $stmt->fetch();
  $stmt->close();

  if ($username) {
    // Update appointment status
    $stmt = $conn->prepare("UPDATE appointments SET status = 'Rejected' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      // Send rejection notification
      $msg = "We're sorry, your appointment has been rejected. Please try another schedule.";
      $note = $conn->prepare("INSERT INTO notifications (username, message, is_read) VALUES (?, ?, 0)");
      $note->bind_param("ss", $username, $msg);
      $note->execute();

      echo json_encode(['status' => 'success', 'message' => 'Appointment rejected and user notified.']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Failed to reject appointment.']);
    }
    $stmt->close();
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Appointment not found.']);
  }

  $conn->close();
}
?>
