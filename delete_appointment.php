<?php
include 'includes/db_connect.php';
$id = $_POST['id'];
$conn->query("DELETE FROM appointments WHERE id = $id");
?>
