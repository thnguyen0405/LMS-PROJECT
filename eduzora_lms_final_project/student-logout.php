<?php
include "header.php";
unset($_SESSION['student']);
$_SESSION['success_message'] = "You are logged out successfully";
header('location: '.BASE_URL.'login');
exit;