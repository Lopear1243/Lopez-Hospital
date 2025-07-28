<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin'])) {
  header("Location: admin_dashboard.php");
  exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['admin'] = $username;
    header("Location: admin.php");
    exit;
  } else {
    $error = "❌ Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - FunCare</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to bottom right, #ffd4dc, #c6f5f8);
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-box {
      background: #ffffff;
      padding: 40px;
      border-radius: 24px;
      width: 340px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
      text-align: center;
      animation: fadeIn 0.8s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    h2 {
      color: #ec407a;
      margin-bottom: 20px;
    }

    .emoji {
      font-size: 42px;
      margin-bottom: 12px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 16px;
      border-radius: 10px;
      border: 1px solid #bbb;
      font-size: 15px;
      background-color: #fafafa;
      transition: border 0.2s;
    }

    input:focus {
      outline: none;
      border-color: #42a5f5;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #42a5f5;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #1e88e5;
    }

    .footer {
      font-size: 13px;
      color: #888;
      margin-top: 20px;
    }

    .error {
      color: red;
      font-weight: bold;
      margin-bottom: 12px;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="emoji">🔐</div>
    <h2>Admin Login</h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="username" placeholder="👤 Username" required>
      <input type="password" name="password" placeholder="🔑 Password" required>
      <button type="submit">Login</button>
    </form>

    <div class="footer">Default: <code>admin / admin123</code></div>
  </div>
</body>
</html>
