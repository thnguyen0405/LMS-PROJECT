<?php include "header.php"; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM subscribers WHERE email=? AND token=?");
$statement->execute([$_GET['email'],$_GET['token']]);
$result = $statement->fetch(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if(!$total){
    header('location: '.BASE_URL);
    exit;
}

$statement = $pdo->prepare("UPDATE subscribers SET status=?,token=? WHERE email=? AND token=?");
$statement->execute([1,'',$_GET['email'],$_GET['token']]);

$_SESSION['success_message'] = 'Your email is verified. You will receive our newsletter.';
header('location: '.BASE_URL);
exit;