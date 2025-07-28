<?php
session_start();

if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - FunCare</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to bottom right, #fff0f5, #e0f7fa);
      padding: 30px;
      margin: 0;
    }
    h1 {
      text-align: center;
      color: #FF4081;
      margin-bottom: 30px;
    }
    .emoji { font-size: 38px; text-align: center; margin-bottom: 6px; }
    .logout {
      float: right;
      background-color: #f44336;
      color: white;
      border: none;
      padding: 10px 16px;
      font-size: 14px;
      border-radius: 16px;
      cursor: pointer;
      margin-bottom: 20px;
    }
    .logout:hover { background-color: #d32f2f; }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 16px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    th, td {
      padding: 14px 16px;
      text-align: left;
      border-bottom: 1px solid #eee;
      font-size: 14px;
      color: #444;
    }
    th {
      background-color: #FF80AB;
      color: white;
      font-size: 15px;
    }
    tr:last-child td { border-bottom: none; }
    .confirm {
      background-color: #2196F3;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 12px;
      cursor: pointer;
    }
    .confirm:hover { background-color: #1976D2; }
    .delete {
      background-color: #f44336;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 12px;
      cursor: pointer;
    }
    .confirmed-row { background-color: #e7fbe7; }

    .message-section {
      background: white;
      margin-top: 50px;
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .message-section h2 {
      color: #FF4081;
      margin-bottom: 20px;
    }

    .message-section input,
    .message-section textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-family: 'Quicksand', sans-serif;
      font-size: 14px;
    }

    .message-section button {
      background-color: #FF80AB;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      cursor: pointer;
    }

    .message-section button:hover {
      background-color: #ec407a;
    }

    #statusMessage {
      margin-top: 10px;
      color: green;
      font-weight: bold;
    }

    @media screen and (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        font-size: 12px;
      }
      .logout {
        font-size: 12px;
        padding: 8px 12px;
      }
    }
    .reject {
      background-color: #e74c3c;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 12px;
      cursor: pointer;
    }
    .reject:hover {
      background-color: #c0392b;
    }
  </style>
  <script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('confirmed') === 'success') {
      Swal.fire({
        title: '✅ Appointment Confirmed!',
        text: 'Message was sent to the patient.',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then(() => {
        window.history.replaceState(null, null, window.location.pathname);
      });
    }
  </script>
</head>
<body>
  <form method="POST" action="logout_admin.php">
    <button type="submit" class="logout">🚪 Logout</button>
  </form>
  <div class="emoji">📊</div>
  <h1>Admin Dashboard - FunCare 🩺</h1>

  <table id="appointmentsTable">
    <thead>
      <tr>
        <th>👤 Name</th>
        <th>🎂 Age</th>
        <th>📱 Contact</th>
        <th>📧 Email</th>
        <th>🏥 Department</th>
        <th>👨‍⚕️ Doctor</th>
        <th>📅 Date</th>
        <th>⏰ Time</th>
        <th>📝 Symptoms</th>
        <th>📌 Status</th>
        <th>⚙️ Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr><td colspan="11" style="text-align:center;">🔄 Loading appointments...</td></tr>
    </tbody>
  </table>

  <!-- Send Message to Patient -->
  <div style="display: flex; justify-content: center; margin-top: 30px;">
    <form method="POST" id="messageForm" style="background: linear-gradient(145deg, #ffe8f0, #e6f7ff); padding: 25px; border-radius: 20px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); width: 360px; max-width: 90%; font-family: 'Quicksand', sans-serif; border: 2px solid #ffd6e6;">
      <h3 style="text-align: center; color: #ff6f91; margin-bottom: 20px;">📨 Send Message to Patient</h3>
      <label for="username" style="color: #555; font-weight: 500;">👤 Patient Username:</label>
      <input type="text" name="username" id="username" required style="width: 100%; padding: 10px; margin: 8px 0 18px 0; border-radius: 10px; border: 1px solid #ccc; background-color: #fff; font-size: 14px;">
      <label for="message" style="color: #555; font-weight: 500;">💬 Message:</label>
      <textarea name="message" id="message" required rows="4" style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #fff; font-size: 14px;"></textarea>
      <button type="submit" style="background: #ff6f91; color: #fff; border: none; padding: 12px; border-radius: 12px; width: 100%; cursor: pointer; font-size: 16px; transition: background 0.3s ease;" onmouseover="this.style.background='#ff4f77'" onmouseout="this.style.background='#ff6f91'">
        🚀 Send Message
      </button>
      <div id="statusMessage" style="margin-top: 12px; text-align: center; font-weight: bold;"></div>
    </form>
  </div>

  <script>
    const tableBody = document.querySelector("#appointmentsTable tbody");

    async function loadAppointments() {
      try {
        const res = await fetch("fetch_appointments.php");
        const appointments = await res.json();

        tableBody.innerHTML = "";

        if (appointments.length === 0) {
          const row = document.createElement("tr");
          row.innerHTML = `<td colspan="11" style="text-align:center; color: gray;">No appointments found.</td>`;
          tableBody.appendChild(row);
          return;
        }

        appointments.forEach((appt) => {
          const row = document.createElement("tr");
          if (appt.status === "Confirmed") row.className = "confirmed-row";

          row.innerHTML = `
            <td>${appt.name}</td>
            <td>${appt.age}</td>
            <td>${appt.contact}</td>
            <td>${appt.email}</td>
            <td>${appt.department}</td>
            <td>${appt.doctor}</td>
            <td>${appt.date}</td>
            <td>${appt.time}</td>
            <td>${appt.symptoms}</td>
            <td>${appt.status}</td>
            <td>
              ${appt.status === "Confirmed"
                ? `<span style="color: green;">✔ Confirmed</span>`
                : appt.status === "Rejected"
                  ? `<span style="color: red;">❌ Rejected</span>`
                  : `<button class="confirm" onclick="confirmAppointment(${appt.id})">Confirm</button>
                     <button class="reject" onclick="rejectAppointment(${appt.id})">Reject</button>
                     <button class="delete" onclick="deleteAppointment(${appt.id})">Delete</button>`}
            </td>
          `;
          tableBody.appendChild(row);
        });
      } catch (error) {
        console.error("Error loading appointments:", error);
        tableBody.innerHTML = `<tr><td colspan="11" style="text-align:center; color: red;">⚠️ Error loading data</td></tr>`;
      }
    }

    function confirmAppointment(id) {
      fetch("confirm_appointment.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${id}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          loadAppointments();
          Swal.fire("Confirmed!", data.message, "success");
        } else {
          Swal.fire("Error", data.message, "error");
        }
      });
    }

    function rejectAppointment(id) {
      Swal.fire({
        title: "Reject Appointment?",
        text: "Are you sure you want to reject this appointment?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e74c3c",
        cancelButtonColor: "#aaa",
        confirmButtonText: "Yes, Reject"
      }).then((result) => {
        if (result.isConfirmed) {
          fetch("reject_appointment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}`
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire("Rejected!", data.message, "success");
              loadAppointments();
            } else {
              Swal.fire("Error", data.message, "error");
            }
          });
        }
      });
    }

    function deleteAppointment(id) {
      if (!confirm("Are you sure you want to delete this appointment?")) return;
      fetch("delete_appointment.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${id}`
      }).then(() => loadAppointments());
    }

    loadAppointments();

    document.getElementById("messageForm").addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch("send_notification.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.text())
      .then(response => {
        document.getElementById("statusMessage").innerText = response;
        this.reset();
      })
      .catch(err => {
        document.getElementById("statusMessage").innerText = "⚠️ Error sending message.";
      });
    });
  </script>
</body>
</html>
