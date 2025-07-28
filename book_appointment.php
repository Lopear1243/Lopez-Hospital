<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user'])) {
  echo "<script>alert('⛔ Please login first.'); window.location.href='user_login.php';</script>";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION['user'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $symptoms = $_POST['symptoms'];
    $status = "Pending";

    $stmt = $conn->prepare("INSERT INTO appointments (username, name, age, contact, email, department, doctor, date, time, symptoms, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissssssss", $username, $name, $age, $contact, $email, $department, $doctor, $date, $time, $symptoms, $status);

    if ($stmt->execute()) {
        // ✅ Save receipt data to session
        $_SESSION['receipt'] = [
            'name' => $name,
            'age' => $age,
            'contact' => $contact,
            'email' => $email,
            'department' => $department,
            'doctor' => $doctor,
            'date' => $date,
            'time' => $time,
            'symptoms' => $symptoms,
            'status' => $status
        ];

        echo "<script>window.location.href='receipt.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Failed to book appointment.'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request'); window.history.back();</script>";
    exit;
}
?>
