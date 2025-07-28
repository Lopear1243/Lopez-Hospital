<?php
// login.php - unified login form + backend logic
session_start();
include 'includes/db_connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if (empty($username) || empty($password)) {
    $error = "⚠️ Please fill out all the fields.";
  } else {
    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user['username'];
      header("Location: index.php?login=success");
      exit();
    } else {
      $error = "❌ Incorrect username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Hospital FunCare</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }

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

    .login-container {
      background: #fff7fb;
      padding: 40px;
      width: 380px;
      border-radius: 24px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: fadeIn 0.8s ease;
      border: 1px solid #f8bbd0;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      margin-bottom: 10px;
      color: #ff4081;
      font-size: 26px;
    }

    .emoji {
      font-size: 40px;
      margin-bottom: 10px;
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

    .error-message {
      color: red;
      font-weight: bold;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .signup-link {
      margin-top: 20px;
      font-size: 14px;
    }

    .signup-link a {
      color: #ec407a;
      text-decoration: none;
      font-weight: bold;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-container">
  <div class="emoji">🔐</div>
  <h2>Login to LH 🏥</h2>
  
  <?php if (!empty($error)): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

   <form action="user_login.php" method="POST">
    <label for="username">👤 Username</label>
    <input type="text" id="username" name="username" placeholder="Enter your username" required>

    <label for="password">🔑 Password</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>

    <button type="submit">🚪 Log In</button>

    <div class="signup-link">
      Don't have an account? <a href="signup.php">Sign up</a>
    </div>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const loginError = <?= json_encode($error) ?>;
  if (loginError) {
    Swal.fire({
      title: 'Login Failed',
      text: loginError,
      icon: 'error',
      confirmButtonText: 'Try Again'
    });
  }
</script>

</body>
</html>
