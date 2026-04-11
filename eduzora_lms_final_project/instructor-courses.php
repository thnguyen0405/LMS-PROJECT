<?php include "header.php"; ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<?php
if(!isset($_SESSION['instructor'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
if(isset($_POST['form_submit_for_review'])) {
    try {
        $statement = $pdo->prepare("SELECT * FROM lessons WHERE course_id=?");
        $statement->execute([$_POST['course_id']]);
        $total = $statement->rowCount();
        if($total == 0) {
            throw new Exception('Please add at least one lesson in this course to submit for review');
        }

        $statement = $pdo->prepare("UPDATE courses SET status=? WHERE id=?");
        $statement->execute(['In Review', $_POST['course_id']]);

        // Send Email to Admin
        $statement = $pdo->prepare("SELECT * FROM admins WHERE id=?");
        $statement->execute([1]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $admin_email = $row['email'];
        }

        $link = ADMIN_URL.'course-view.php';
        $email_message = 'A course is submitted for review. So please check your admin panel.<br>';
        $email_message .= '<a href="'.$link.'">Click Here</a>';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(SMTP_FROM);
        $mail->addAddress($admin_email);
        $mail->addReplyTo(SMTP_FROM);
        $mail->isHTML(true);
        $mail->Subject = 'A course is pending for review';
        $mail->Body = $email_message;
        $mail->send();

        $_SESSION['success_message'] = "Course has been submitted for review successfully";
        header('location: '.BASE_URL.'instructor-courses');
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('location: '.BASE_URL.'instructor-courses');
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Courses</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="card">
                    <?php include "instructor-sidebar.php"; ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>SL</th>
                                <th>Featured Photo</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="w-200">Action</th>
                            </tr>
                            <?php
                                $i=0;
                                $statement = $pdo->prepare("SELECT * FROM courses WHERE instructor_id=? ORDER BY id DESC");
                                $statement->execute([$_SESSION['instructor']['id']]);
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                $total = $statement->rowCount();
                                if($total==0) {
                                    echo '<tr><td colspan="6" class="text-danger">No courses found</td></tr>';
                                } else {
                                    foreach ($result as $row) {
                                        $i++;
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td>
                                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['featured_photo']; ?>" alt="" class="w-150">
                                            </td>
                                            <td>
                                                <?php echo $row['title']; ?>
                                            </td>
                                            <td>
                                                $<?php echo $row['price']; ?>
                                            </td>
                                            <td>
                                                <?php if($row['status'] == 'Pending'): ?>
                                                    <span class="badge bg-danger">Pending</span>
                                                <?php elseif($row['status'] == 'In Review'): ?>
                                                    <span class="badge bg-warning">In Review</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>

                                                <?php if($row['status'] != 'In Review'): ?>
                                                <a href="<?php echo BASE_URL; ?>instructor-course-edit-basic/<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="<?php echo BASE_URL; ?>instructor-course-delete/<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                <?php endif; ?>

                                                <?php if($row['status'] == 'Pending'): ?>
                                                <form action="" method="post">
                                                    <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn btn-primary btn-sm mt_5" name="form_submit_for_review" onclick="return confirm('Are you sure?')">Submit for Review</button>
                                                </form>
                                                <?php elseif($row['status'] == 'In Review'): ?>
                                                    <a href="javascript:void" class="btn btn-sm mt_5 submit-review-disabled" disabled>Submitted for Review</a>
                                                <?php endif; ?>

                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>