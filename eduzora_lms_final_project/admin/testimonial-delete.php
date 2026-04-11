<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM testimonials WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".ADMIN_URL."testimonial-view.php");
    exit;
}
?>

<?php
// Unlink photo
$statement = $pdo->prepare("SELECT * FROM testimonials WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$testimonial = $statement->fetch(PDO::FETCH_ASSOC);

unlink('../uploads/'.$testimonial['photo']);

$statement = $pdo->prepare("DELETE FROM testimonials WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$success_message = "Data is deleted successfully";
$_SESSION['success_message'] = $success_message;
header("location: ".ADMIN_URL."testimonial-view.php");
exit;
?>