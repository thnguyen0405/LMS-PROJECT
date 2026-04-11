<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['instructor'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM settings WHERE id=?");
$statement->execute([1]);
$setting_data = $statement->fetch(PDO::FETCH_ASSOC);
?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['withdraw_amount'] == '') {
            throw new Exception("Withdraw Amount can not be empty");
        }
        // Check number
        if(!is_numeric($_POST['withdraw_amount'])) {
            throw new Exception("Withdraw Amount must be a number.");
        }
        if($_POST['withdraw_amount'] > $_POST['final_instructor_revenue']) {
            throw new Exception("Withdraw Amount must not be larger than the balance.");
        }
        if($_POST['withdraw_amount'] < $setting_data['minimum_withdraw_amount']) {
            throw new Exception("You must have to withdraw at least $".$setting_data['minimum_withdraw_amount']);
        }
        if($_POST['withdraw_note'] == '') {
            throw new Exception("Withdraw Note can not be empty");
        }

        $statement = $pdo->prepare("INSERT INTO withdraws (instructor_id,withdraw_method,withdraw_amount,withdraw_note,withdraw_request_date,withdraw_approval_date,withdraw_status) VALUES (?,?,?,?,?,?,?)");
        $statement->execute([$_SESSION['instructor']['id'],$_POST['withdraw_method'],$_POST['withdraw_amount'],$_POST['withdraw_note'],date('Y-m-d H:i:s'),'','Pending']);

        $_SESSION['success_message'] = 'Withdraw Request is Accepted. Thank You!';
        header('location: '.BASE_URL.'instructor-withdraw');
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-withdraw');
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Withdraw Money</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Withdraw Money</li>
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
                
                <h3 style="color:#6440fb;border-bottom:1px solid #6440fb;padding-bottom:5px;margin-bottom:15px;">Balance</h3>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th class="w-300">Balance</th>
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
                            <td>$<?php echo $final_instructor_revenue; ?></td>
                        </tr>
                        <tr>
                            <th>Minimum Withdraw Amount</th>
                            <td>$<?php echo $setting_data['minimum_withdraw_amount']; ?></td>
                        </tr>
                        <tr>
                            <th>Withdraw Fee</th>
                            <td>$<?php echo $setting_data['withdraw_fee']; ?></td>
                        </tr>
                    </table>
                </div>
                
                <?php if($final_instructor_revenue < $setting_data['minimum_withdraw_amount']): ?>
                    <span class="text-danger">You do not have sufficient balance to withdraw the money.</span>

                <?php else: ?>
                <h3 style="color:#6440fb;border-bottom:1px solid #6440fb;padding-bottom:5px;margin-bottom:15px;margin-top:50px;">New Withdraw Request</h3>
                <form action="" method="post">
                    <input type="hidden" name="final_instructor_revenue" value="<?php echo $final_instructor_revenue; ?>">
                    <div class="mb-3">
                        <label for="">Withdraw Method *</label>
                        <div class="form-group">
                            <select name="withdraw_method" class="form-select">
                                <option value="PayPal">PayPal</option>
                                <option value="Stripe">Stripe</option>
                                <option value="Bank">Bank</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="">Withdraw Amount *</label>
                        <div class="form-group">
                            <input type="text" name="withdraw_amount" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="">Withdraw Note *</label>
                        <div class="form-group">
                            <textarea name="withdraw_note" class="form-control h-200"></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary btn-sm" name="form_submit">Submit</button>
                    </div>
                </form>
                <?php endif; ?>

                <h3 style="color:#6440fb;border-bottom:1px solid #6440fb;padding-bottom:5px;margin-bottom:15px;margin-top:50px;">Withdraw History</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>Withdraw Method</th>
                            <th>Withdraw Amount</th>
                            <th>Withdraw Note</th>
                            <th>Request Date</th>
                            <th>Approval Date</th>
                            <th>Status</th>
                        </tr>
                        <?php
                        $i=0;
                        $statement = $pdo->prepare("SELECT * FROM withdraws WHERE instructor_id=?");
                        $statement->execute([$_SESSION['instructor']['id']]);
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) {
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['withdraw_method']; ?></td>
                                <td>$<?php echo $row['withdraw_amount']; ?></td>
                                <td><?php echo $row['withdraw_note']; ?></td>
                                <td><?php echo $row['withdraw_request_date']; ?></td>
                                <td><?php echo $row['withdraw_approval_date']; ?></td>
                                <td>
                                    <?php if($row['withdraw_status'] == 'Pending'): ?>
                                        <span class="badge bg-danger"><?php echo $row['withdraw_status']; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?php echo $row['withdraw_status']; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>