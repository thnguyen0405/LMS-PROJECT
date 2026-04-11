<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM languages WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".ADMIN_URL."language-view.php");
    exit;
}
?>

<?php
$statement = $pdo->prepare("DELETE FROM languages WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$success_message = "Data is deleted successfully";
$_SESSION['success_message'] = $success_message;
header("location: ".ADMIN_URL."language-view.php");
exit;
?>