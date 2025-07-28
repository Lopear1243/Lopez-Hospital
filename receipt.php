<?php
session_start();
if (!isset($_SESSION['receipt'])) {
  echo "<h2>❌ No appointment data found.</h2>";
  exit;
}

$receipt = $_SESSION['receipt'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🧾 Appointment Receipt</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #a8edea, #fed6e3);
      margin: 0;
      padding: 2rem;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .receipt-container {
      background: white;
      padding: 2rem 3rem;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      max-width: 600px;
      width: 100%;
    }
    .receipt-container h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 1.5rem;
    }
    .receipt-table {
      width: 100%;
      border-collapse: collapse;
    }
    .receipt-table td {
      padding: 10px 0;
      vertical-align: top;
    }
    .receipt-table td:first-child {
      font-weight: bold;
      color: #555;
      width: 35%;
    }
    .back-button {
      display: block;
      text-align: center;
      margin-top: 2rem;
    }
    .back-button a {
      background-color: #3498db;
      color: white;
      padding: 10px 20px;
      border-radius: 30px;
      text-decoration: none;
      transition: background 0.3s ease;
    }
    .back-button a:hover {
      background-color: #2980b9;
    }
  </style>
</head>
<body>
  <div class="receipt-container">
    <h2>🧾 Appointment Receipt</h2>
    <table class="receipt-table">
      <tr><td>Name:</td><td><?= htmlspecialchars($receipt['name']) ?></td></tr>
      <tr><td>Age:</td><td><?= htmlspecialchars($receipt['age']) ?></td></tr>
      <tr><td>Contact:</td><td><?= htmlspecialchars($receipt['contact']) ?></td></tr>
      <tr><td>Email:</td><td><?= htmlspecialchars($receipt['email']) ?></td></tr>
      <tr><td>Department:</td><td><?= htmlspecialchars($receipt['department']) ?></td></tr>
      <tr><td>Doctor:</td><td><?= htmlspecialchars($receipt['doctor']) ?></td></tr>
      <tr><td>Date:</td><td><?= htmlspecialchars($receipt['date']) ?></td></tr>
      <tr><td>Time:</td><td><?= htmlspecialchars($receipt['time']) ?></td></tr>
      <tr><td>Symptoms:</td><td><?= htmlspecialchars($receipt['symptoms']) ?></td></tr>
      <tr><td>Status:</td><td><?= htmlspecialchars($receipt['status']) ?></td></tr>
    </table>
    <div class="back-button">
      <a href="index.php">🔙 Back to Home</a>
    </div>
  </div>
</body>
</html>
