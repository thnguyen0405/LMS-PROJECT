<?php include "header.php"; ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
if(isset($_POST['form_submit'])) {
    try  {

        if($_POST['message'] == "") {
            throw new Exception("Message can not be empty.");
        }

        $statement = $pdo->prepare("INSERT INTO messages (course_id, student_id, instructor_id, sender, message, message_date_time) VALUES (?, ?, ?, ?, ?, ?)");
        $statement->execute(array($_REQUEST['course_id'], $_REQUEST['student_id'], $_REQUEST['instructor_id'], 'student', $_POST['message'], date('Y-m-d H:i:s')));

        // Getting instructor email
        $statement = $pdo->prepare("SELECT * FROM instructors WHERE id=?");
        $statement->execute([$_REQUEST['instructor_id']]);
        $instructor_data = $statement->fetch(PDO::FETCH_ASSOC);

        $link = BASE_URL.'instructor-message-detail.php?course_id='.$_REQUEST['course_id'].'&student_id='.$_REQUEST['student_id'].'&instructor_id='.$_REQUEST['instructor_id'];
        $email_message = 'Please click on this link below to see the message: <br>';
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
        $mail->addAddress($instructor_data['email']);
        $mail->addReplyTo(SMTP_FROM);
        $mail->isHTML(true);
        $mail->Subject = 'New Message Comes from Student';
        $mail->Body = $email_message;
        $mail->send();


        $_SESSION['success_message'] = "Message sent successfully.";
        header('location: '.BASE_URL.'student-message-detail.php?course_id='.$_REQUEST['course_id'].'&student_id='.$_REQUEST['student_id'].'&instructor_id='.$_REQUEST['instructor_id']);
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'student-message-detail.php?course_id='.$_REQUEST['course_id'].'&student_id='.$_REQUEST['student_id'].'&instructor_id='.$_REQUEST['instructor_id']);
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Message Detail</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Message Detail</li>
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
                    <?php include "student-sidebar.php"; ?>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <h3>All Messages</h3>
                <?php
                $statement = $pdo->prepare("SELECT t1.*,
                                    t2.name as student_name,
                                    t2.photo as student_photo,
                                    t3.name as instructor_name,
                                    t3.photo as instructor_photo
                                    FROM messages t1
                                    JOIN students t2
                                    ON t1.student_id = t2.id
                                    JOIN instructors t3
                                    ON t1.instructor_id = t3.id
                                    WHERE t1.course_id=? AND t1.student_id=? AND t1.instructor_id=?
                                    ORDER BY t1.id ASC");
                $statement->execute([$_REQUEST['course_id'], $_REQUEST['student_id'], $_REQUEST['instructor_id']]);
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $total = $statement->rowCount();
                if(!$total) {
                    echo "<span class='text-danger'>No message found.</span>";
                }
                foreach ($result as $row) {
                    ?>
                    <div class="message-item <?php if($row['sender'] == 'instructor') {echo 'message-item-admin-border';} ?>">
                        <div class="message-top">
                            <div class="left">
                                <?php if($row['sender'] == 'instructor'): ?>
                                    <?php if($row['instructor_photo']!=''): ?>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['instructor_photo']; ?>" alt="">
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="">
                                    <?php endif; ?>


                                <?php else: ?>

                                    <?php if($row['student_photo']!=''): ?>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['student_photo']; ?>" alt="">
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="">
                                    <?php endif; ?>        
                                <?php endif; ?>
                            </div>
                            <div class="right">
                                <?php if($row['sender'] == 'instructor'): ?>
                                <h4><?php echo $row['instructor_name']; ?></h4>
                                <?php else: ?>
                                <h4><?php echo $row['student_name']; ?></h4>
                                <?php endif; ?>
                                <h5><?php echo $row['sender']; ?></h5>
                                <div class="date-time"><?php echo $row['message_date_time']; ?></div>
                            </div>
                        </div>
                        <div class="message-bottom">
                            <p>
                                <?php echo nl2br($row['message']); ?>
                            </p>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="col-lg-4 col-md-12">
                <h3>Write a message</h3>
                <form action="" method="post">
                    <div class="mb-2">
                        <textarea name="message" class="form-control h-150" cols="30" rows="10" placeholder="Write your message here"></textarea>
                    </div>
                    <div class="mb-2">
                        <button type="submit" class="btn btn-primary" name="form_submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>