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
                <h2>Enrolled Courses</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Enrolled Courses</li>
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
                                <th>Photo</th>
                                <th>Course Title</th>
                                <th>Curriculum</th>
                                <th class="w-150">
                                    Detail
                                </th>
                            </tr>
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT t1.*,
                                            t2.id as course_id,
                                            t2.title as course_title,
                                            t2.slug as course_slug,
                                            t2.featured_photo as course_featured_photo
                                            FROM order_details t1
                                            JOIN courses t2
                                            ON t1.course_id = t2.id
                                            WHERE t1.student_id=? 
                                            ORDER BY t1.id ASC");
                            $statement->execute([$_SESSION['student']['id']]);
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['course_featured_photo']; ?>" alt="" class="w-200">
                                    </td>
                                    <td><?php echo $row['course_title']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>student-course/<?php echo $row['course_id']; ?>" class="btn btn-primary btn-sm w-100-p">Curriculum</a>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>course/<?php echo $row['course_slug']; ?>" class="btn btn-success btn-sm w-100-p">See Detail</a>
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