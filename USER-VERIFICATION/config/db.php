<?php
require 'constants.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if($conn->connect_error) {
  die("Database error : ".$conn->connect_error);
}
?>
