<?php include 'layouts/top.php'; ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<?php
if(isset($_POST['form_publish'])) {
    try {
        
        $statement = $pdo->prepare("UPDATE courses SET status=? WHERE id=?");
        $statement->execute(['Active',$_POST['course_id']]);

        // Send Email to Instructor
        $statement = $pdo->prepare("SELECT * FROM instructors WHERE id=?");
        $statement->execute([$_POST['instructor_id']]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $instructor_email = $row['email'];
        }

        // get the existing total_course value from instructors table
        $statement = $pdo->prepare("SELECT * FROM instructors WHERE id=?");
        $statement->execute([$_POST['instructor_id']]);
        $single_instructor = $statement->fetch(PDO::FETCH_ASSOC);
        $new_total_course = $single_instructor['total_course'] + 1;

        // Update the total_course in instructors table
        $statement = $pdo->prepare("UPDATE instructors SET total_course=? WHERE id=?");
        $statement->execute([$new_total_course, $_POST['instructor_id']]);

        $link = BASE_URL.'instructor-courses';
        $email_message = 'Congratulations! Your course is published and now live. Go to see your course here:<br>';
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
        $mail->addAddress($instructor_email);
        $mail->addReplyTo(SMTP_FROM);
        $mail->isHTML(true);
        $mail->Subject = 'Congratulations! Your course is accepted!';
        $mail->Body = $email_message;
        $mail->send();

        $success_message = 'Course is published successfully.';
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."course-view.php");
        exit;
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."course-view.php");
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>All Courses</h1>
            <!-- <div>
                <a href="<?php echo ADMIN_URL; ?>course-add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            </div> -->
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Featured Photo</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Instructor</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i=0;
                                        $statement = $pdo->prepare("SELECT 
                                                    t1.*,
                                                    t2.name as category_name,
                                                    t3.id as instructor_id,
                                                    t3.name as instructor_name
                                                    FROM courses t1
                                                    JOIN categories t2
                                                    ON t1.category_id = t2.id
                                                    JOIN instructors t3
                                                    ON t1.instructor_id = t3.id
                                                    ORDER BY t1.id DESC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            if($row['status'] == 'Pending') {
                                                continue;
                                            }
                                            $i++;
                                            if($row['status'] == 'In Review') {
                                                $style = 'background:hsl(8, 100.00%, 89.20%);';
                                            } else {
                                                $style = '';
                                            }
                                            ?>
                                            <tr>
                                                <td style="<?php echo $style; ?>"><?php echo $i; ?></td>
                                                <td style="<?php echo $style; ?>">
                                                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['featured_photo']; ?>" alt="" class="w_200">
                                                </td>
                                                <td style="<?php echo $style; ?>">
                                                    <?php echo $row['title']; ?>
                                                    <?php if($row['status'] == 'In Review'): ?>
                                                    <div class="mt_5">
                                                        <form action="" method="post">
                                                            <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="instructor_id" value="<?php echo $row['instructor_id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" name="form_publish" onClick="return confirm('Are you sure?');">Publish It</button>
                                                        </form>
                                                    </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="<?php echo $style; ?>">
                                                    <a href="<?php echo ADMIN_URL; ?>category-edit.php?id=<?php echo $row['category_id']; ?>">
                                                    <?php echo $row['category_name']; ?>
                                                    </a>
                                                </td>
                                                <td style="<?php echo $style; ?>">
                                                    <a href="<?php echo ADMIN_URL; ?>instructor-edit.php?id=<?php echo $row['instructor_id']; ?>">
                                                    <?php echo $row['instructor_name']; ?>
                                                    </a>
                                                </td>
                                                <td style="<?php echo $style; ?>">$<?php echo $row['price']; ?></td>
                                                <td class="pt_10 pb_10" style="<?php echo $style; ?>">
                                                    <a href="<?php echo ADMIN_URL; ?>course-detail.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="<?php echo ADMIN_URL; ?>course-delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>