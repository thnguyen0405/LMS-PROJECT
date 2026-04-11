<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
if(isset($_POST['form_update'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        if($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }
        if($_POST['phone'] == '') {
            throw new Exception("Phone can not be empty");
        }
        if($_POST['address'] == '') {
            throw new Exception("Address can not be empty");
        }
        if($_POST['country'] == '') {
            throw new Exception("Country can not be empty");
        }
        if($_POST['state'] == '') {
            throw new Exception("State can not be empty");
        }
        if($_POST['city'] == '') {
            throw new Exception("City can not be empty");
        }
        if($_POST['zip'] == '') {
            throw new Exception("Zip can not be empty");
        }
        $statement = $pdo->prepare("UPDATE students SET 
                                    name=?, 
                                    email=?,
                                    phone=?,
                                    address=?,
                                    country=?,
                                    state=?,
                                    city=?,
                                    zip=?
                                    WHERE id=?");
        $statement->execute([
                            $_POST['name'],
                            $_POST['email'],
                            $_POST['phone'],
                            $_POST['address'],
                            $_POST['country'],
                            $_POST['state'],
                            $_POST['city'],
                            $_POST['zip'],
                            $_SESSION['student']['id']
                        ]);


        // Update Password
        if($_POST['password']!='' || $_POST['confirm_password']!='') {
            if($_POST['password'] != $_POST['confirm_password']) {
                throw new Exception("Password does not match");
            } else {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $statement = $pdo->prepare("UPDATE students SET password=? WHERE id=?");
                $statement->execute([$password,$_SESSION['student']['id']]);
                $_SESSION['student']['password'] = $password;
            }
        }

        // Update Photo
        $path = $_FILES['photo']['name'];
        $path_tmp = $_FILES['photo']['tmp_name'];

        if($path!='') {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "student_".time().".".$extension;
    
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);
    
            if($mime == 'image/jpeg' || $mime == 'image/png' || $mime == 'image/gif') {
                if($_SESSION['student']['photo']!='') {
                    unlink('uploads/'.$_SESSION['student']['photo']);
                }
                move_uploaded_file($path_tmp, 'uploads/'.$filename);
                $statement = $pdo->prepare("UPDATE students SET photo=? WHERE id=?");
                $statement->execute([$filename,$_SESSION['student']['id']]);
                $_SESSION['student']['photo'] = $filename;
            } else {
                throw new Exception("Please upload a valid photo");
            }
        }

        $success_message = 'Profile data is updated successfully!';

        $_SESSION['student']['name'] = $_POST['name'];
        $_SESSION['student']['email'] = $_POST['email'];
        $_SESSION['student']['phone'] = $_POST['phone'];
        $_SESSION['student']['address'] = $_POST['address'];
        $_SESSION['student']['country'] = $_POST['country'];
        $_SESSION['student']['state'] = $_POST['state'];
        $_SESSION['student']['city'] = $_POST['city'];
        $_SESSION['student']['zip'] = $_POST['zip'];

        $_SESSION['success_message'] = $success_message;
        header('location: '.BASE_URL.'student-profile');
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'student-profile');
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Profile</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
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
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Existing Photo</label>
                            <div class="form-group">
                                <?php if($_SESSION['student']['photo'] == ''): ?>
                                    <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="" class="user-photo">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo $_SESSION['student']['photo']; ?>" alt="" class="user-photo">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Change Photo</label>
                            <div class="form-group">
                                <input type="file" name="photo">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Name *</label>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" value="<?php echo $_SESSION['student']['name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Email Address *</label>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" value="<?php echo $_SESSION['student']['email']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Phone *</label>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" value="<?php echo $_SESSION['student']['phone']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Address *</label>
                            <div class="form-group">
                                <input type="text" name="address" class="form-control" value="<?php echo $_SESSION['student']['address']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Country *</label>
                            <div class="form-group">
                                <input type="text" name="country" class="form-control" value="<?php echo $_SESSION['student']['country']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">State *</label>
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" value="<?php echo $_SESSION['student']['state']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">City *</label>
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" value="<?php echo $_SESSION['student']['city']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Zip Code *</label>
                            <div class="form-group">
                                <input type="text" name="zip" class="form-control" value="<?php echo $_SESSION['student']['zip']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Password</label>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Confirm Password</label>
                            <div class="form-group">
                                <input type="password" name="confirm_password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input name="form_update" type="submit" class="btn btn-primary" value="Update">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>