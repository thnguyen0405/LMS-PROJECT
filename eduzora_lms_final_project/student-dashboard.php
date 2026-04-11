<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM orders WHERE student_id=? AND payment_status=?");
$statement->execute([$_SESSION['student']['id'], 'Completed']);
$total_completed_orders = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM order_details WHERE student_id=?");
$statement->execute([$_SESSION['student']['id']]);
$total_enrolled_courses = $statement->rowCount();

?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Dashboard</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                <h3 class="mb_20">Hello, <?php echo $_SESSION['student']['name']; ?></h3>
                <div class="row box-items">
                    <div class="col-md-4">
                        <div class="box1">
                            <h4><?php echo $total_completed_orders; ?></h4>
                            <p>Completed Orders</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box2">
                            <h4><?php echo $total_enrolled_courses; ?></h4>
                            <p>Enrolled Courses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>