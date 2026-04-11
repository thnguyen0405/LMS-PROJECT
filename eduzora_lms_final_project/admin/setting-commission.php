<?php include 'layouts/top.php'; ?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['sales_commission'] == '') {
            throw new Exception("Sales Commission can not be empty");
        }
        // Check if it is integer
        if(!is_numeric($_POST['sales_commission'])) {
            throw new Exception("Sales Commission must be a number");
        }
        // Check if it is greater than 0 and less than 100
        if($_POST['sales_commission'] <= 0 || $_POST['sales_commission'] >= 100) {
            throw new Exception("Sales Commission must be between 0 and 100");
        }

        $statement = $pdo->prepare("UPDATE settings SET sales_commission=? WHERE id=?");
        $statement->execute([$_POST['sales_commission'],1]);

        $success_message = "Data is updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."setting-commission.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."setting-commission.php");
        exit;
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM settings WHERE id=?");
$statement->execute([1]);
$setting_data = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Edit Sales Commission</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Sales Commision</label>
                                            <input type="text" class="form-control" name="sales_commission" value="<?php echo $setting_data[0]['sales_commission']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form_submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Admin Commission</td>
                                        <td><?php echo $setting_data[0]['sales_commission'].'%'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Instructor Commission</td>
                                        <td><?php echo (100-$setting_data[0]['sales_commission']).'%'; ?></td>
                                    </tr>
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