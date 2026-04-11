<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM students WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".ADMIN_URL."student-active.php");
    exit;
}
?>

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
        $statement = $pdo->prepare("SELECT * FROM students WHERE email=? AND id!=?");
        $statement->execute([$_POST['email'],$_REQUEST['id']]);
        $total = $statement->rowCount();
        if($total) {
            throw new Exception("Email already exists.");
        }

        $path = $_FILES['photo']['name'];
        $path_tmp = $_FILES['photo']['tmp_name'];
        if($path == '') {
            $filename = $_POST['current_photo'];
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "student_".time().".".$extension;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);
            if($mime != 'image/jpeg' && $mime != 'image/png' && $mime != 'image/gif') {
                throw new Exception("Please upload a valid photo");
            }
        }

        if($path != '') {
            if($_POST['current_photo'] != '') {
                unlink('../uploads/'.$_POST['current_photo']);
            }
            move_uploaded_file($path_tmp, '../uploads/'.$filename);
        }


        $statement = $pdo->prepare("SELECT * FROM students WHERE id=?");
        $statement->execute([$_REQUEST['id']]);
        $current_student = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($_POST['password'] != '') {
            if($_POST['password'] != $_POST['confirm_password']) {
                throw new Exception("Password and Confirm Password did not match.");
            }
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            $password = $current_student[0]['password'];
        }

        $statement = $pdo->prepare("UPDATE students SET 
                            name=?,
                            email=?,
                            photo=?,
                            password=?,
                            phone=?, 
                            address=?, 
                            country=?, 
                            state=?, 
                            city=?, 
                            zip=?,
                            token=?,
                            status=?
                            
                            WHERE id=?");
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
            $_POST['status'],
            $_REQUEST['id']
        ]);

        $success_message = "Data is updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."student-active.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."student-edit.php?id=".$_REQUEST['id']);
        exit;
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM students WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$student_data = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Edit Student</h1>
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
                                <input type="hidden" name="current_photo" value="<?php echo $student_data[0]['photo']; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Existing Photo</label>
                                            <div>
                                                <?php if($student_data[0]['photo']!=''): ?>
                                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $student_data[0]['photo']; ?>" alt="" class="w_150">
                                                <?php else: ?>
                                                <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="" class="w_150">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Change Photo</label>
                                            <div>
                                                <input type="file" name="photo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo $student_data[0]['name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Email Address *</label>
                                            <input type="text" class="form-control" name="email" value="<?php echo $student_data[0]['email']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Phone</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo $student_data[0]['phone']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Address</label>
                                            <input type="text" class="form-control" name="address" value="<?php echo $student_data[0]['address']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Country</label>
                                            <input type="text" class="form-control" name="country" value="<?php echo $student_data[0]['country']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>State</label>
                                            <input type="text" class="form-control" name="state" value="<?php echo $student_data[0]['state']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>City</label>
                                            <input type="text" class="form-control" name="city" value="<?php echo $student_data[0]['city']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Zip</label>
                                            <input type="text" class="form-control" name="zip" value="<?php echo $student_data[0]['zip']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Confirm Password</label>
                                            <input type="password" class="form-control" name="confirm_password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Status</label>
                                            <select name="status" class="form-select">
                                                <option value="0" <?php if($student_data[0]['status'] == 0) {echo 'selected';} ?>>Pending</option>
                                                <option value="1" <?php if($student_data[0]['status'] == 1) {echo 'selected';} ?>>Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form_submit">Update</button>
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