<?php
session_start();
include 'includes/db_connect.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if (empty($username) || empty($password)) {
    $error = "Please fill out all fields.";
  } else {
    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
      $error = "🚫 Username already taken.";
    } else {
      // Hash password and insert user
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
      $insert->bind_param("ss", $username, $hashed_password);
      
      if ($insert->execute()) {
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit;
      } else {
        $error = "❌ Registration failed. Please try again.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up - Hospital FunCare</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to bottom right, #fce4ec, #b3e5fc);
      font-family: 'Quicksand', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .signup-container {
      background: #fff7fb;
      padding: 40px;
      width: 400px;
      border-radius: 24px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
      border: 1px solid #f8bbd0;
    }

    h2 {
      margin-bottom: 10px;
      color: #ff4081;
      font-size: 26px;
    }

    label {
      display: block;
      text-align: left;
      font-weight: bold;
      margin: 14px 0 6px;
      color: #555;
    }

    input {
      width: 100%;
      padding: 12px;
      border: 2px solid #e1bee7;
      border-radius: 12px;
      font-size: 15px;
      background-color: #fff0f5;
    }

    button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #ff80ab;
      color: white;
      border: none;
      border-radius: 20px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    .login-link {
      margin-top: 20px;
      font-size: 14px;
    }

    .login-link a {
      color: #ec407a;
      text-decoration: none;
      font-weight: bold;
    }

    .error-message {
      color: red;
      font-weight: bold;
      margin-top: 10px;
    }

    .success-message {
      color: green;
      font-weight: bold;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="signup-container">
  <h2>📝 Create a LH Account</h2>

  <?php if ($error): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
  <?php elseif ($success): ?>
    <div class="success-message"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form action="signup.php" method="POST">
    <label for="username">👤 Username</label>
    <input type="text" id="username" name="username" required>

    <label for="password">🔐 Password</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">🎈 Sign Up</button>

    <div class="login-link">
      Already have an account? <a href="user_login.php">Log in</a>
    </div>
  </form>
</div>

</body>
</html>
