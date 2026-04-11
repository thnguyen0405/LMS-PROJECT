<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
$_SESSION['error_message'] = "Payment is cancelled.";
header('location: '.BASE_URL);
exit;
?>