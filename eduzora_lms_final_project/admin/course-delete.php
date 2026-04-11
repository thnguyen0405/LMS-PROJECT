<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".ADMIN_URL."course-view.php");
    exit;
}
?>

<?php

// Get data from lessons table
$statement = $pdo->prepare("SELECT * FROM lessons WHERE course_id=?");
$statement->execute([$_REQUEST['id']]);
$lessons = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($lessons as $row) {
    if($row['lesson_type'] == 'video') {
        if($row['video_type'] == 'mp4') {
            unlink('../uploads/'.$row['video_content']);
        }
    } else {
        unlink('../uploads/'.$row['resource_content']);
    }
}

// Delete data from lessons table
$statement = $pdo->prepare("DELETE FROM lessons WHERE course_id=?");
$statement->execute([$_REQUEST['id']]);

// Delete data from modules table
$statement = $pdo->prepare("DELETE FROM modules WHERE course_id=?");
$statement->execute([$_REQUEST['id']]);


// Get data from courses table
$statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$course_data = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($course_data as $row) {
    unlink('../uploads/'.$row['featured_photo']);
    unlink('../uploads/'.$row['featured_banner']);
    if($row['featured_video_type'] == 'mp4') {
        unlink('../uploads/'.$row['featured_video_content']);
    }
}

// Delete data from courses table
$statement = $pdo->prepare("DELETE FROM courses WHERE id=?");
$statement->execute([$_REQUEST['id']]);

// Delete data from wishlists table
$statement = $pdo->prepare("DELETE FROM wishlists WHERE course_id=?");
$statement->execute([$_REQUEST['id']]);

$success_message = "Data is deleted successfully";
$_SESSION['success_message'] = $success_message;
header("location: ".ADMIN_URL."course-view.php");
exit;
?>