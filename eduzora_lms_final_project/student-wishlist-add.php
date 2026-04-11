<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
try {
    $statement = $pdo->prepare("SELECT * FROM wishlists WHERE student_id=? AND course_id=?");
    $statement->execute([$_SESSION['student']['id'], $_REQUEST['id']]);
    $total = $statement->rowCount();
    if($total) {
        throw new Exception("This course is already in your wishlist");
    }
    
    $statement = $pdo->prepare("INSERT INTO wishlists (student_id, course_id) VALUES (?, ?)");
    $statement->execute([$_SESSION['student']['id'], $_REQUEST['id']]);

    $_SESSION['success_message'] = "Course has been added to your wishlist";
    header('location: '.BASE_URL.'student-wishlist');
    exit;
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('location: '.BASE_URL.'student-wishlist');
    exit;
}

?>