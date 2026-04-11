<?php include "header.php"; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM students WHERE email=? AND token=?");
$statement->execute([$_REQUEST['email'],$_REQUEST['token']]);
$total = $statement->rowCount();
if(!$total) {
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        
        if($_POST['password'] == '' || $_POST['confirm_password'] == '') {
            throw new Exception("Password can not be empty");
        }

        if($_POST['password'] != $_POST['confirm_password']) {
            throw new Exception("Passwords do not match");
        }

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $statement = $pdo->prepare("UPDATE students SET token=?, password=? WHERE email=? AND token=?");
        $statement->execute(['',$password,$_REQUEST['email'],$_REQUEST['token']]);

        $_SESSION['success_message'] = 'Your password is reset successfully. You can login now.';
        header('location: '.BASE_URL.'login');
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'student-reset-password.php?email='.$_REQUEST['email'].'&token='.$_REQUEST['token']);
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Student Reset Password</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Student Reset Password</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content pt_70 pb_70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                <!-- form content -->
                <form action="" method="post">
                    <div class="login-form">
                        <div class="mb-3">
                            <label for="" class="form-label">New Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary bg-website" name="form_submit">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
                <!-- // form content -->
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>