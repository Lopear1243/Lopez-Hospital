<?php
// confirm_appointment.php
header('Content-Type: application/json');
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Step 1: Update appointment status to Confirmed
    $stmt = $conn->prepare("UPDATE appointments SET status = 'Confirmed' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to update status."]);
        exit;
    }

    // Step 2: Get user info
    $info = $conn->prepare("SELECT username, name FROM appointments WHERE id = ?");
    $info->bind_param("i", $id);
    $info->execute();
    $result = $info->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo json_encode(["status" => "error", "message" => "Appointment not found."]);
        exit;
    }

    $username = $row['username'];
    $name = $row['name'];
    $message = "✅ Hello $name! Your appointment has been confirmed. Thank you for choosing FunCare!";

    // Step 3: Insert notification message
    $msg = $conn->prepare("INSERT INTO notifications (username, message, is_read) VALUES (?, ?, 0)");
    $msg->bind_param("ss", $username, $message);
    $msg->execute();

    // Step 4: Return success response
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
