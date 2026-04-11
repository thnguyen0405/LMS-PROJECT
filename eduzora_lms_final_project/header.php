<?php
ob_start();
session_start();
include 'config/config.php';
include 'config/functions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
$statement = $pdo->prepare("SELECT * FROM settings WHERE id=?");
$statement->execute([1]);
$setting_data = $statement->fetch(PDO::FETCH_ASSOC);
?>
<?php
if(isset($_POST['form1'])) {
    $title = $_POST['title'];
    header("location: ".BASE_URL."courses.php?title=$title&price=&language=&category=&review=&level=");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>EduZora</title>

        <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>uploads/<?php echo $setting_data['favicon']; ?>">

        <!-- All CSS -->
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/animate.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/magnific-popup.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/owl.carousel.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/select2-bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/iziToast.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/all.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/meanmenu.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/spacing.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/style.css">
        
        <!-- All Javascripts -->
        <script src="<?php echo BASE_URL; ?>dist-front/js/jquery-3.7.1.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/bootstrap.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/bootstrap-datepicker.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/jquery.magnific-popup.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/owl.carousel.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/wow.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/select2.full.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/jquery.waypoints.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/moment.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/counterup.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/multi-countdown.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/jquery.meanmenu.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/iziToast.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/tinymce/tinymce.min.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 left-side">
                        <ul>
                            <li class="phone-text"><i class="fas fa-phone"></i> 111-222-3333</li>
                            <li class="email-text"><i class="fas fa-envelope"></i> contact@example.com</li>
                        </ul>
                    </div>
                    <div class="col-md-6 right-side">
                        <ul class="right">
                            <?php if(isset($_SESSION['instructor'])): ?>
                            <li class="menu">
                                <a href="<?php echo BASE_URL; ?>instructor-dashboard"><i class="fas fa-sign-in-alt"></i> Instructor Dashboard</a>
                            </li>
                            <?php elseif(isset($_SESSION['student'])): ?>
                            <li class="menu">
                                <a href="<?php echo BASE_URL; ?>student-dashboard"><i class="fas fa-sign-in-alt"></i> Student Dashboard</a>
                            </li>
                            <?php else: ?>
                            <li class="menu">
                                <a href="<?php echo BASE_URL; ?>login"><i class="fas fa-sign-in-alt"></i> Login</a>
                            </li>
                            <li class="menu">
                                <a href="<?php echo BASE_URL; ?>registration"><i class="fas fa-user"></i> Sign Up</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="navbar-area" id="stickymenu">
            <!-- Menu For Mobile Device -->
            <div class="mobile-nav">
                <a href="<?php echo BASE_URL; ?>" class="logo">
                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo $setting_data['logo']; ?>" alt="">
                </a>
            </div>

            <!-- Menu For Desktop Device -->
            <div class="main-nav">
                <div class="container">
                    <nav class="navbar navbar-expand-md navbar-light">
                        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $setting_data['logo']; ?>" alt="">
                        </a>
                        <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item <?php echo ($cur_page == 'index.php') ? 'active' : ''; ?>">
                                    <a href="<?php echo BASE_URL; ?>" class="nav-link">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=&review=&level=" class="nav-link">Courses</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo BASE_URL; ?>instructors" class="nav-link">Instructors</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo BASE_URL; ?>blog" class="nav-link">Blog</a>
                                </li>
                                <li class="nav-item dropdown <?php echo ($cur_page == 'about.php') ? 'active' : ''; ?>">
                                    <a class="nav-link dropdown-toggle" href="javascript:void;" id="galleryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pages
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="galleryDropdown">
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>about">About Us</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>privacy">Privacy Policy</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>terms">Terms and Conditions</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>faq">FAQ</a></li>
                                        <li><a class="dropdown-item" href="page.php">Custom Page 1</a></li>
                                        <li><a class="dropdown-item" href="page.php">Custom Page 2</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo BASE_URL; ?>contact" class="nav-link">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                        <div class="right-side">
                            <div class="search">
                                <form action="" method="post">
                                    <div class="search-icon">
                                        <input name="title" type="text" placeholder="Search courses...">
                                        <button type="submit" name="form1"><i class="fas fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="cart">
                                <a href="<?php echo BASE_URL; ?>cart">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                                <span class="number">
                                    <?php
                                    if(isset($_SESSION['cart_course_id'])) {
                                        echo count($_SESSION['cart_course_id']);
                                    }
                                    else {
                                        echo '0';
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="wishlist">
                                <a href="<?php echo BASE_URL; ?>student-wishlist">
                                    <i class="far fa-heart"></i>
                                </a>
                                <span class="number">
                                    <?php
                                    if(isset($_SESSION['student'])) {
                                        $statement = $pdo->prepare("SELECT * FROM wishlists WHERE student_id=?");
                                        $statement->execute([$_SESSION['student']['id']]);
                                        $total = $statement->rowCount();
                                        echo $total;
                                    }
                                    else {
                                        echo '0';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>