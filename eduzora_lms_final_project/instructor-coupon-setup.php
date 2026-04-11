<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['instructor'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$course = $statement->fetch(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if(!$total) {
    header('location: '.BASE_URL.'instructor-coupon-view');
    exit;
}
?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if(empty($_POST['coupon_name'])) {
            throw new Exception("Coupon Name can not be empty.");
        }
        // Duplicate name check for coupon
        $statement = $pdo->prepare("SELECT * FROM coupons WHERE course_id=? AND coupon_name=?");
        $statement->execute([$_REQUEST['id'], $_POST['coupon_name']]);
        $total = $statement->rowCount();
        if($total) {
            throw new Exception("Coupon name already exists.");
        }

        if(empty($_POST['discount_percentage'])) {
            throw new Exception("Discount Percentage can not be empty.");
        }
        // Check number
        if(!is_numeric($_POST['discount_percentage'])) {
            throw new Exception("Discount Percentage must be a number.");
        }

        if(empty($_POST['start_date'])) {
            throw new Exception("Start Date can not be empty.");
        }
        if(empty($_POST['end_date'])) {
            throw new Exception("End Date can not be empty.");
        }

        $statement = $pdo->prepare("INSERT INTO coupons (course_id, coupon_name, discount_percentage, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        $statement->execute([$_REQUEST['id'], $_POST['coupon_name'], $_POST['discount_percentage'], $_POST['start_date'], $_POST['end_date']]);


        $_SESSION['success_message'] = "Coupon has been added successfully";
        header('location: '.BASE_URL.'instructor-coupon-view');
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('location: '.BASE_URL.'instructor-coupon-view');
        exit;
    }
}
?>

<?php
if(isset($_POST['form_delete'])) {
    try {
        $statement = $pdo->prepare("DELETE FROM coupons WHERE id=?");
        $statement->execute([$_POST['coupon_id']]);

        $_SESSION['success_message'] = "Coupon has been deleted successfully";
        header('location: '.BASE_URL.'instructor-coupon-view');
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('location: '.BASE_URL.'instructor-coupon-view');
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Setup Coupons for <?php echo $course['title']; ?></h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Coupon Setup</li>
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
                <h2 style="font-size:24px;border-bottom: 1px solid #333;padding-bottom:10px;margin-bottom:15px;">Create Coupon</h2>
                <form action="" method="post">    
                    <div class="mb-3">
                        <label for="">Coupon Name *</label>
                        <div class="form-group">
                            <input type="text" name="coupon_name" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="">Discount Percentage *</label>
                        <div class="form-group">
                            <input type="text" name="discount_percentage" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="">Start Date *</label>
                        <div class="form-group">
                            <input type="text" name="start_date" class="form-control datepicker">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="">End Date *</label>
                        <div class="form-group">
                            <input type="text" name="end_date" class="form-control datepicker">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="form_submit">Submit</button>
                </form>

                <h2 class="mt_40" style="font-size:24px;border-bottom: 1px solid #333;padding-bottom:10px;margin-bottom:15px;">Show Coupons</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>SL</th>
                                <th>Coupon Name</th>
                                <th>Discount Percentage</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th class="w-200">Action</th>
                            </tr>
                            <?php
                                $i=0;
                                $statement = $pdo->prepare("SELECT * FROM coupons WHERE course_id=? ORDER BY id ASC");
                                $statement->execute([$_REQUEST['id']]);
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td>
                                            <?php echo $row['coupon_name']; ?>
                                        </td>
                                        <td>
                                            $<?php echo $row['discount_percentage']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['start_date']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['end_date']; ?>
                                        </td>
                                        <td>
                                            <form action="" method="post">
                                                <input type="hidden" name="coupon_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" name="form_delete" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                            
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