<?php
include "header.php";

if(!isset($_REQUEST['email'])||!isset($_REQUEST['token'])) {
    header('location: '.BASE_URL);
}

$statement = $pdo->prepare("SELECT * FROM instructors WHERE email=? AND token=?");
$statement->execute([$_REQUEST['email'],$_REQUEST['token']]);
$total = $statement->rowCount();

if($total) {
    $statement = $pdo->prepare("UPDATE instructors SET token=?, status=? WHERE email=? AND token=?");
    $statement->execute(['',1,$_REQUEST['email'],$_REQUEST['token']]);
    $_SESSION['success_message'] = 'Your registration is verified. You can login now.';
    header('location: '.BASE_URL.'login');
    exit;
} else {
    header('location: '.BASE_URL);
}