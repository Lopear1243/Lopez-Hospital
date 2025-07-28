<?php
session_start();
if (!isset($_SESSION['user'])) {
  echo "<script>alert('⛔ Please login first.'); window.location.href='user_login.php';</script>";
  exit;
}
$username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment - FunCare 🏥</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to bottom right, #fce4ec, #e0f7fa);
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 30px;
    }
    .notification-bell {
      position: absolute;
      top: 20px;
      right: 30px;
      font-size: 24px;
      cursor: pointer;
    }
    .notification-dropdown {
      display: none;
      position: absolute;
      top: 60px;
      right: 20px;
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 12px;
      width: 260px;
      max-height: 300px;
      overflow-y: auto;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      z-index: 1000;
    }
    .notification-dropdown ul {
      list-style: none;
      padding: 10px;
      margin: 0;
    }
    .notification-dropdown ul li {
      padding: 10px;
      border-bottom: 1px solid #f0f0f0;
      font-size: 14px;
      color: #333;
    }
    .notification-dropdown ul li:last-child {
      border-bottom: none;
    }

    .container {
      background: #ffffff;
      padding: 40px;
      border-radius: 24px;
      width: 520px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      border: 1px solid #f8bbd0;
      margin-bottom: 30px;
      position: relative;
    }
    h2 {
      text-align: center;
      margin-bottom: 16px;
      color: #ff4081;
      font-size: 28px;
    }
    .emoji {
      text-align: center;
      font-size: 40px;
      margin-bottom: 6px;
    }
    .welcome {
      text-align: center;
      font-size: 16px;
      margin-bottom: 20px;
      color: #444;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #555;
    }
    input, select, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 16px;
      border: 2px solid #e1bee7;
      border-radius: 12px;
      font-size: 15px;
      background-color: #fff0f5;
    }
    input:focus, textarea:focus, select:focus {
      outline: none;
      border-color: #ba68c8;
      box-shadow: 0 0 5px #ce93d8;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #ff80ab;
      color: white;
      border: none;
      border-radius: 20px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background-color: #f06292;
    }
    .logout {
      background-color: #f44336;
      margin-top: 10px;
    }
    #recommendation {
      text-align: center;
      font-weight: bold;
      color: #1976D2;
      margin-top: -12px;
      margin-bottom: 16px;
    }
  </style>
</head>
<body>

<!-- 🔔 Bell icon -->
<div class="notification-bell" onclick="toggleNotifications()">🔔</div>

<!-- 🔽 Notification Dropdown -->
<div class="notification-dropdown" id="notificationDropdown">
  <ul id="notificationList">
    <li>Loading notifications...</li>
  </ul>
</div>

<div class="container">
  <div class="emoji">📅</div>
  <h2>Book Your Appointment to Lopez Hospital</h2>
  <p class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</p>

  <form action="book_appointment.php" method="POST">
    <!-- form fields remain unchanged -->
    <label for="name">👤 Full Name</label>
    <input type="text" id="name" name="name" required>

    <label for="age">🎂 Age</label>
    <input type="number" id="age" name="age" required>

    <label for="contact">📱 Contact Number</label>
    <input type="text" id="contact" name="contact" required>

    <label for="email">📧 Email Address</label>
    <input type="email" id="email" name="email" required>

    <label for="department">🏥 Department</label>
    <select id="department" name="department" required onchange="updateDoctorOptions()">
      <option value="">-- Select Department --</option>
      <option value="Cardiology">Cardiology</option>
      <option value="Neurology">Neurology</option>
      <option value="Pediatrics">Pediatrics</option>
      <option value="Orthopedics">Orthopedics</option>
      <option value="Dermatology">Dermatology</option>
    </select>

    <label for="doctor">👨‍⚕️ Doctor</label>
    <select id="doctor" name="doctor" required>
      <option value="">-- Select Doctor --</option>
    </select>

    <label for="date">📅 Date</label>
    <input type="date" id="date" name="date" required>

    <label for="time">⏰ Time</label>
    <input type="time" id="time" name="time" required>

    <label for="symptoms">📝 Symptoms</label>
    <textarea id="symptoms" name="symptoms" rows="4" onblur="recommendDepartmentFromSymptoms()" required></textarea>

    <p id="recommendation"></p>

    <button type="submit">🎉 Book Appointment</button>
    <button class="logout" onclick="logout()" type="button">🚪 Logout</button>
  </form>
</div>

<script>
function logout() {
  window.location.href = "logout.php";
}

const doctorMap = {
  Cardiology: ["Dr. Heartwell", "Dr. Smith"],
  Neurology: ["Dr. Brainard", "Dr. Monroe"],
  Pediatrics: ["Dr. Kidd", "Dr. Lively"],
  Orthopedics: ["Dr. Bonez", "Dr. Carter"],
  Dermatology: ["Dr. Skinner", "Dr. Valez"]
};

function updateDoctorOptions() {
  const department = document.getElementById("department").value;
  const doctorSelect = document.getElementById("doctor");
  doctorSelect.innerHTML = '<option value="">-- Select Doctor --</option>';
  if (department && doctorMap[department]) {
    doctorMap[department].forEach(doctor => {
      const option = document.createElement("option");
      option.value = doctor;
      option.textContent = doctor;
      doctorSelect.appendChild(option);
    });
  }
}

function recommendDepartmentFromSymptoms() {
  const symptoms = document.getElementById("symptoms").value.toLowerCase();
  const departmentSelect = document.getElementById("department");
  const recommendationText = document.getElementById("recommendation");

  const symptomMap = {
    Cardiology: ["heart", "chest pain", "palpitations", "high blood pressure", "shortness of breath"],
    Neurology: ["headache", "seizure", "numbness", "dizziness", "stroke", "blurred vision"],
    Pediatrics: ["child", "baby", "fever", "cough", "cold", "crying"],
    Orthopedics: ["bone", "fracture", "joint", "knee", "back pain", "swelling"],
    Dermatology: ["skin", "rash", "acne", "itch", "eczema", "redness", "allergy"]
  };

  let matched = false;
  for (const [dept, keywords] of Object.entries(symptomMap)) {
    if (keywords.some(k => symptoms.includes(k))) {
      departmentSelect.value = dept;
      updateDoctorOptions();
      recommendationText.textContent = `📌 Recommended Department: ${dept}`;
      matched = true;
      break;
    }
  }

  if (!matched) {
    recommendationText.textContent = `❓ No department match found for given symptoms.`;
  }
}

function toggleNotifications() {
  const dropdown = document.getElementById("notificationDropdown");
  dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

function loadNotifications() {
  fetch("fetch_notifications.php")
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById("notificationList");
      list.innerHTML = "";

      if (data.length === 0) {
        list.innerHTML = "<li>📭 No new notifications.</li>";
      } else {
        data.forEach(note => {
          const li = document.createElement("li");
          li.textContent = `✅ ${note.message}`;
          list.appendChild(li);
        });
      }
    });
}
window.onload = loadNotifications;
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('login') === 'success') {
    Swal.fire({
      title: '🎉 Welcome!',
      text: 'You have successfully logged in.',
      icon: 'success',
      confirmButtonText: 'OK'
    }).then(() => {
      // Remove ?login=success from URL
      window.history.replaceState(null, null, window.location.pathname);
    });
  }
</script>

</body>
</html>
