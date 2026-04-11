<?php
include "layouts/top.php";
unset($_SESSION['admin']);
$_SESSION['success_message'] = "You are logged out successfully";
header('location: '.ADMIN_URL.'login.php');
exit;