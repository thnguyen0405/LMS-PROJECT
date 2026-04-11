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

<?php
$total_revenue = 0;
$statement = $pdo->prepare("SELECT t1.*,
                t2.instructor_id as instructor_id
                FROM order_details t1
                JOIN courses t2
                ON t1.course_id = t2.id");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    if($row['instructor_id'] != $_SESSION['instructor']['id']) {
        continue;
    }
    $total_revenue = $total_revenue + $row['instructor_revenue'];
}
?>

<?php
$final_instructor_revenue = 0;
$instructor_revenue_1 = 0;
$statement = $pdo->prepare("SELECT * FROM order_details");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $q = $pdo->prepare("SELECT * FROM courses WHERE id=?");
    $q->execute([$row['course_id']]);
    $course_data = $q->fetch(PDO::FETCH_ASSOC);
    if($course_data['instructor_id'] != $_SESSION['instructor']['id']) {
        continue;
    }
    $instructor_revenue_1 = $instructor_revenue_1 + $row['instructor_revenue'];
}

$instructor_revenue_2 = 0;
$statement = $pdo->prepare("SELECT * FROM withdraws WHERE instructor_id=?");
$statement->execute([$_SESSION['instructor']['id']]);
$withdraw_data = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($withdraw_data as $row) {
    $instructor_revenue_2 = $instructor_revenue_2 + $row['withdraw_amount'];
}
$final_instructor_revenue = $instructor_revenue_1 - $instructor_revenue_2;
?>

<div class="page-content user-panel pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="card">
                    <?php include "instructor-sidebar.php"; ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <h3 class="mb_20">Hello, <?php echo $_SESSION['instructor']['name']; ?></h3>
                <div class="row box-items">
                    <div class="col-md-4">
                        <div class="box1">
                            <p>Total Revenue</p>
                            <h4>$<?php echo $total_revenue; ?></h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box2">
                            <p>Current Balance</p>
                            <h4>$<?php echo $final_instructor_revenue; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>