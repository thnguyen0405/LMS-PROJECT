<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['instructor'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Revenues</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Revenues</li>
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
                                <th>Order No</th>
                                <th>Student</th>
                                <th>Course Title</th>
                                <th>Course Price</th>
                                <th>Coupone Name</th>
                                <th>Discount</th>
                                <th>Final Price</th>
                                <th>Revenue</th>
                            </tr>
                            <?php
                            $i=0;
                            $total_revenue = 0;
                            $statement = $pdo->prepare("SELECT t1.*,
                                            t2.name as student_name,
                                            t3.title as course_title,
                                            t3.instructor_id as instructor_id
                                            FROM order_details t1
                                            JOIN students t2
                                            ON t1.student_id = t2.id
                                            JOIN courses t3
                                            ON t1.course_id = t3.id
                                            ORDER BY t1.id DESC");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {

                                if($row['instructor_id'] != $_SESSION['instructor']['id']) {
                                    continue;
                                }

                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['order_no']; ?></td>
                                    <td><?php echo $row['student_name']; ?></td>
                                    <td><?php echo $row['course_title']; ?></td>
                                    <td>$<?php echo $row['course_price']; ?></td>
                                    <td><?php echo $row['coupon_name']; ?></td>
                                    <td>$<?php echo $row['discount']; ?></td>
                                    <td>$<?php echo $row['final_price']; ?></td>
                                    <td>$<?php echo $row['instructor_revenue']; ?></td>
                                </tr>
                                <?php
                                $total_revenue = $total_revenue + $row['instructor_revenue'];
                            }
                            ?>
                            <tr>
                                <td colspan="8" align="right"><strong>Total Revenue</strong></td>
                                <td><strong>$<?php echo $total_revenue; ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>