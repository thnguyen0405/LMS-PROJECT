<?php include 'layouts/top.php'; ?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        // Email empty check
        if($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        // Email format check. Use regular expression to check email validation. Do not use filter validation for email.
        $regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        if (!preg_match($regex, $_POST['email'])) {
            throw new Exception("Email format is not valid.");
        }
        // Duplicate email check
        $statement = $pdo->prepare("SELECT * FROM students WHERE email=?");
        $statement->execute([$_POST['email']]);
        $total = $statement->rowCount();
        if($total) {
            throw new Exception("Email already exists.");
        }

        $path = $_FILES['photo']['name'];
        $path_tmp = $_FILES['photo']['tmp_name'];
        if($path == '') {
            throw new Exception("Photo can not be empty");
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "student_".time().".".$extension;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);
            if($mime != 'image/jpeg' && $mime != 'image/png' && $mime != 'image/gif') {
                throw new Exception("Please upload a valid photo");
            }
        }

        move_uploaded_file($path_tmp, '../uploads/'.$filename);

        $password = password_hash("1234", PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO students (
                                name,
                                email,
                                photo,
                                password,
                                phone, 
                                address, 
                                country, 
                                state, 
                                city, 
                                zip,
                                token,
                                status
                            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute([
            $_POST['name'],
            $_POST['email'],
            $filename,
            $password,
            $_POST['phone'],
            $_POST['address'],
            $_POST['country'],
            $_POST['state'],
            $_POST['city'],
            $_POST['zip'],
            '',
            $_POST['status']
        ]);

        $success_message = "Data is inserted successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."student-active.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;

        $_SESSION['name'] = $_POST['name'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['phone'] = $_POST['phone'];
        $_SESSION['address'] = $_POST['address'];
        $_SESSION['country'] = $_POST['country'];
        $_SESSION['state'] = $_POST['state'];
        $_SESSION['city'] = $_POST['city'];
        $_SESSION['zip'] = $_POST['zip'];

        header("location: ".ADMIN_URL."student-add.php");
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Add Student</h1>
            <div>
                <a href="<?php echo ADMIN_URL; ?>student-active.php" class="btn btn-primary"><i class="fas fa-plus"></i> Active Students</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Photo *</label>
                                            <div>
                                                <input type="file" name="photo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name" value="<?php if(isset($_SESSION['name'])) {echo $_SESSION['name']; unset($_SESSION['name']); } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Email Address *</label>
                                            <input type="text" class="form-control" name="email" value="<?php if(isset($_SESSION['email'])) {echo $_SESSION['email']; unset($_SESSION['email']); } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Phone</label>
                                            <input type="text" class="form-control" name="phone" value="<?php if(isset($_SESSION['phone'])) {echo $_SESSION['phone']; unset($_SESSION['phone']); } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Address</label>
                                            <input type="text" class="form-control" name="address" value="<?php if(isset($_SESSION['address'])) {echo $_SESSION['address']; unset($_SESSION['address']); } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Country</label>
                                            <input type="text" class="form-control" name="country" value="<?php if(isset($_SESSION['country'])) {echo $_SESSION['country']; unset($_SESSION['country']); } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>State</label>
                                            <input type="text" class="form-control" name="state" value="<?php if(isset($_SESSION['state'])) {echo $_SESSION['state']; unset($_SESSION['state']); } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>City</label>
                                            <input type="text" class="form-control" name="city" value="<?php if(isset($_SESSION['city'])) {echo $_SESSION['city']; unset($_SESSION['city']); } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Zip</label>
                                            <input type="text" class="form-control" name="zip" value="<?php if(isset($_SESSION['zip'])) {echo $_SESSION['zip']; unset($_SESSION['zip']); } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Status</label>
                                            <select name="status" class="form-select">
                                                <option value="0">Pending</option>
                                                <option value="1">Active</option>
                                            </select>
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