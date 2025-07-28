<?php
include 'includes/db_connect.php';

header('Content-Type: application/json');

// Fetch all appointments for the admin
$sql = "SELECT * FROM appointments ORDER BY date DESC";
$result = $conn->query($sql);

$appointments = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
    echo json_encode($appointments);
} else {
    echo json_encode([]);
}
?>
