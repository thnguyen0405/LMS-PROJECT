<?php include "header.php"; ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['form_student'])) {
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
        if($_POST['password'] == '' || $_POST['confirm_password'] == '') {
            throw new Exception("Password can not be empty");
        }
        if($_POST['password'] != $_POST['confirm_password']) {
            throw new Exception("Password does not match");
        }

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token = hash('sha256', time() . random_bytes(16));
        $statement = $pdo->prepare("INSERT INTO students (name,email,password,token,status) VALUES (?,?,?,?,?)");
        $statement->execute([$_POST['name'],$_POST['email'],$password,$token,0]);

        $link = BASE_URL.'student-registration-verify.php?email='.$_POST['email'].'&token='.$token;
        $email_message = 'Please click on this link to verify the registration: <br>';
        $email_message .= '<a href="'.$link.'">Click Here</a>';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(SMTP_FROM);
        $mail->addAddress($_POST['email']);
        $mail->addReplyTo(SMTP_FROM);
        $mail->isHTML(true);
        $mail->Subject = 'Registration Verify';
        $mail->Body = $email_message;
        $mail->send();

        $success_message = 'An email is sent to your email address. Please check and verify the registration.';
        $_SESSION['success_message'] = $success_message;
        header('location: '.BASE_URL.'registration');
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'registration');
        exit;
    }
}

if(isset($_POST['form_instructor'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        if($_POST['designation'] == '') {
            throw new Exception("Designation can not be empty");
        }
        if($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }
        if($_POST['password'] == '' || $_POST['confirm_password'] == '') {
            throw new Exception("Password can not be empty");
        }
        if($_POST['password'] != $_POST['confirm_password']) {
            throw new Exception("Password does not match");
        }

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token = hash('sha256', time() . random_bytes(16));
        $statement = $pdo->prepare("INSERT INTO instructors (name,designation,email,password,total_course,total_rating,total_rating_score,average_rating,token,status) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $statement->execute([$_POST['name'],$_POST['designation'],$_POST['email'],$password,0,0,0,0,$token,0]);

        $link = BASE_URL.'instructor-registration-verify.php?email='.$_POST['email'].'&token='.$token;
        $email_message = 'Please click on this link to verify the registration: <br>';
        $email_message .= '<a href="'.$link.'">Click Here</a>';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(SMTP_FROM);
        $mail->addAddress($_POST['email']);
        $mail->addReplyTo(SMTP_FROM);
        $mail->isHTML(true);
        $mail->Subject = 'Registration Verify';
        $mail->Body = $email_message;
        $mail->send();

        $success_message = 'An email is sent to your email address. Please check and verify the registration.';
        $_SESSION['success_message'] = $success_message;
        header('location: '.BASE_URL.'registration');
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'registration');
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Create Account</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Create Account</li>
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

                <ul class="nav nav-pills mb-3 nav-login-register" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="student-tab" data-bs-toggle="pill" data-bs-target="#student" type="button" role="tab" aria-controls="student" aria-selected="true">Student</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="instructor-tab" data-bs-toggle="pill" data-bs-target="#instructor" type="button" role="tab" aria-controls="instructor" aria-selected="false">Instructor</button>
                    </li>
                </ul>
                <div class="tab-content tab-login-register" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="student" role="tabpanel" aria-labelledby="student-tab" tabindex="0">
                        <!-- form content -->
                        <form action="" method="post">
                            <div class="login-form">
                                <div class="mb-3">
                                    <label for="" class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Email Address *</label>
                                    <input type="text" class="form-control" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Password *</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary bg-website" name="form_student">
                                        Create Account
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <a href="<?php echo BASE_URL; ?>login" class="primary-color">Existing User? Login Now</a>
                                </div>
                            </div>
                        </form>
                        <!-- // form content -->
                    </div>
                    <div class="tab-pane fade" id="instructor" role="tabpanel" aria-labelledby="instructor-tab" tabindex="0">
                        <!-- form content -->
                        <form action="" method="post">
                            <div class="login-form">
                                <div class="mb-3">
                                    <label for="" class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Designation *</label>
                                    <input type="text" class="form-control" name="designation">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Email Address *</label>
                                    <input type="text" class="form-control" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Password *</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary bg-website" name="form_instructor">
                                        Create Account
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <a href="<?php echo BASE_URL; ?>login" class="primary-color">Existing User? Login Now</a>
                                </div>
                            </div>
                        </form>
                        <!-- // form content -->
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>