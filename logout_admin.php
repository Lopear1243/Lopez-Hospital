<?php
session_start();
session_unset();
session_destroy();
header("Location: admin_login.php"); // ✅ Make sure this matches your real admin login file
exit;
?>
