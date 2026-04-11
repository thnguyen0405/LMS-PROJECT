<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM instructors WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".ADMIN_URL."instructor-active.php");
    exit;
}
?>

<?php
try {
    $statement = $pdo->prepare("SELECT * FROM courses WHERE instructor_id=?");
    $statement->execute([$_REQUEST['id']]);
    $total = $statement->rowCount();
    if($total) {
        throw new Exception("This instructor has courses. So, it can not be deleted.");
    }
    
    $statement = $pdo->prepare("SELECT * FROM instructors WHERE id=?");
    $statement->execute([$_REQUEST['id']]);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        if($row['photo'] != '') {
            unlink('../uploads/'.$row['photo']);
        }
    }
    $statement = $pdo->prepare("DELETE FROM instructors WHERE id=?");
    $statement->execute([$_REQUEST['id']]);
    $success_message = "Data is deleted successfully";
    $_SESSION['success_message'] = $success_message;
    header("location: ".ADMIN_URL."instructor-active.php");
    exit;
} catch (Exception $e) {
    $error_message = $e->getMessage();
    $_SESSION['error_message'] = $error_message;
    header("location: ".ADMIN_URL."instructor-active.php");
    exit;
}
?>