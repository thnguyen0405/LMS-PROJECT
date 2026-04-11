<?php include 'layouts/top.php'; ?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['minimum_withdraw_amount'] == '') {
            throw new Exception("Minimum Withdraw Amount can not be empty");
        }
        // Check if it is integer
        if(!is_numeric($_POST['minimum_withdraw_amount'])) {
            throw new Exception("Minimum Withdraw Amount must be a number");
        }
        if($_POST['withdraw_fee'] == '') {
            throw new Exception("Withdraw Fee can not be empty");
        }

        $statement = $pdo->prepare("UPDATE settings SET minimum_withdraw_amount=?,withdraw_fee=? WHERE id=?");
        $statement->execute([$_POST['minimum_withdraw_amount'],$_POST['withdraw_fee'],1]);

        $success_message = "Data is updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."setting-withdraw.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."setting-withdraw.php");
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
            <h1>Edit Withdraw Setting</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Minimum Withdraw Amount *</label>
                                            <input type="text" class="form-control" name="minimum_withdraw_amount" value="<?php echo $setting_data[0]['minimum_withdraw_amount']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Withdraw Fee *</label>
                                            <input type="text" class="form-control" name="withdraw_fee" value="<?php echo $setting_data[0]['withdraw_fee']; ?>">
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
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>