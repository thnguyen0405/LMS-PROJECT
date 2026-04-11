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
                <h2>Coupons</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Coupons</li>
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
                                <th>Course</th>
                                <th>Title</th>
                                <th>Price</th>
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
                                                <a href="<?php echo BASE_URL; ?>instructor-coupon-setup/<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Coupon Setup</a>
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