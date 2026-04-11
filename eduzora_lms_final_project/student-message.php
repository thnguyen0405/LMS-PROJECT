<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Messages</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Messages</li>
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
            <div class="col-lg-9 col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>SL</th>
                                <th>Course Photo</th>
                                <th>Course Title</th>
                                <th>Instructor Photo</th>
                                <th>Instructor Name</th>
                                <th class="w-100">
                                    Action
                                </th>
                            </tr>
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT t1.*,
                                            t2.title as course_title,
                                            t2.featured_photo as course_featured_photo,
                                            t2.instructor_id as instructor_id
                                            FROM order_details t1
                                            JOIN courses t2 
                                            ON t1.course_id = t2.id
                                            WHERE t1.student_id=?");
                            $statement->execute([$_SESSION['student']['id']]);
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                                $q = $pdo->prepare("SELECT * FROM instructors WHERE id=?");
                                $q->execute([$row['instructor_id']]);
                                $instructor = $q->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['course_featured_photo']; ?>" alt="" class="w-150">
                                    </td>
                                    <td>
                                        <?php echo $row['course_title']; ?>
                                    </td>
                                    <td>
                                        <?php if($instructor['photo'] != ''): ?>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $instructor['photo']; ?>" alt="" class="w-100">
                                        <?php else: ?>
                                        <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="" class="w-100">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo $instructor['name']; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>student-message-detail.php?course_id=<?php echo $row['course_id'] ?>&student_id=<?php echo $_SESSION['student']['id']; ?>&instructor_id=<?php echo $instructor['id'] ?>" class="btn btn-secondary btn-sm w-100-p">Send Message</a>
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

<?php include "footer.php"; ?>