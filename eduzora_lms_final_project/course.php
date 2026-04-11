<?php include "header.php"; ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<?php
if(isset($_POST['form_remove_coupon'])) {
    unset($_SESSION['coupon'][$_POST['course_id']]);
    $success_message = "Coupon has been removed successfully.";
    $_SESSION['success_message'] = $success_message;
    header("location: ".BASE_URL."course/".$_REQUEST['slug']);
    exit;
}

if(isset($_POST['form_coupon_apply'])) {
    try {
        if($_POST['coupon_name'] == '') {
            throw new Exception("Coupon code can not be empty.");
        }
        $statement = $pdo->prepare("SELECT * FROM coupons WHERE coupon_name=?");
        $statement->execute([$_POST['coupon_name']]);
        $coupon_data = $statement->fetch(PDO::FETCH_ASSOC);

        if($coupon_data['course_id'] != $_POST['course_id']) {
            throw new Exception("This coupon is not valid for this course.");
        }

        // Check if after applied coupon the price becomes negetive
        $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
        $statement->execute([$coupon_data['course_id']]);
        $course_data = $statement->fetch(PDO::FETCH_ASSOC);

        if($course_data['price'] == 0) {
            throw new Exception("This course is already free. So coupon can not be applied.");
        }

        $current_date = date('Y-m-d');
        if($current_date < $coupon_data['start_date']) {
            throw new Exception("This coupon is not started yet.");
        }
        if($current_date > $coupon_data['end_date']) {
            throw new Exception("This coupon is expired.");
        }

        $course_id = $coupon_data['course_id'];
        $_SESSION['coupon'][$course_id] = $coupon_data['coupon_name'];

        $success_message = "Coupon has been applied successfully.";
        $_SESSION['success_message'] = $success_message;
        header("location: ".BASE_URL."course/".$_REQUEST['slug']);
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".BASE_URL."course/".$_REQUEST['slug']);
        exit;
    }
}
?>

<?php
if(isset($_POST['form_review_submit'])) {
    try {
        if($_POST['rating'] == '') {
            throw new Exception("Rating can not be empty.");
        }
        if($_POST['comment'] == '') {
            throw new Exception("Comment can not be empty.");
        }

        $statement = $pdo->prepare("INSERT INTO reviews (student_id, course_id, rating, comment) VALUES (?,?,?,?)");
        $statement->execute([$_SESSION['student']['id'],$_POST['course_id'],$_POST['rating'],$_POST['comment']]);


        // Get data from courses table
        $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
        $statement->execute([$_POST['course_id']]);
        $course = $statement->fetch(PDO::FETCH_ASSOC);
        $existing_total_rating = $course['total_rating'];
        $existing_total_rating_score = $course['total_rating_score'];
        $new_total_rating = $existing_total_rating + 1;
        $new_total_rating_score = $existing_total_rating_score + $_POST['rating'];
        $average_rating = $new_total_rating_score / $new_total_rating;

        // Update courses table
        $statement = $pdo->prepare("UPDATE courses SET total_rating=?, total_rating_score=?, average_rating=? WHERE id=?");
        $statement->execute([$new_total_rating,$new_total_rating_score,$average_rating,$_POST['course_id']]);

        // Get data from instructors table
        $statement = $pdo->prepare("SELECT * FROM instructors WHERE id=?");
        $statement->execute([$course['instructor_id']]);
        $instructor = $statement->fetch(PDO::FETCH_ASSOC);
        $existing_total_rating = $instructor['total_rating'];
        $existing_total_rating_score = $instructor['total_rating_score'];
        $new_total_rating = $existing_total_rating + 1;
        $new_total_rating_score = $existing_total_rating_score + $_POST['rating'];
        $average_rating = $new_total_rating_score / $new_total_rating;

        // Update instructors table
        $statement = $pdo->prepare("UPDATE instructors SET total_rating=?, total_rating_score=?, average_rating=? WHERE id=?");
        $statement->execute([$new_total_rating,$new_total_rating_score,$average_rating,$course['instructor_id']]);

        $success_message = "Review has been submitted successfully.";
        $_SESSION['success_message'] = $success_message;
        header("location: ".BASE_URL."course/".$_REQUEST['slug']);
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".BASE_URL."course/".$_REQUEST['slug']);
        exit;
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT t1.*,
                            t2.name as category_name,
                            t3.name as instructor_name,
                            t3.email as instructor_email,
                            t3.photo as instructor_photo,
                            t3.designation as instructor_designation,
                            t3.average_rating as instructor_average_rating,
                            t3.total_rating as instructor_total_rating,
                            t3.biography as instructor_biography,
                            t3.facebook as instructor_facebook,
                            t3.twitter as instructor_twitter,
                            t3.linkedin as instructor_linkedin,
                            t3.instagram as instructor_instagram,
                            t3.id as instructor_id,
                            t4.name as level_name,
                            t5.name as language_name

                            FROM courses t1
                            JOIN categories t2  
                            ON t1.category_id = t2.id
                            JOIN instructors t3
                            ON t1.instructor_id = t3.id
                            JOIN levels t4
                            ON t1.level_id = t4.id
                            JOIN languages t5
                            ON t1.language_id = t5.id
                            WHERE t1.slug=? AND t1.status=?");
$statement->execute([$_REQUEST['slug'],'Active']);
$course = $statement->fetch(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".BASE_URL);
    exit;
}
?>

<?php
if(isset($_POST['form_enquery'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty.");
        }
        if($_POST['email'] == '') {
            throw new Exception("Email can not be empty.");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        if($_POST['phone'] == '') {
            throw new Exception("Phone can not be empty.");
        }
        if($_POST['message'] == '') {
            throw new Exception("Message can not be empty.");
        }

        $email_message = '<h2>Course Enquery Sender Information</h2>';
        $email_message .= '<p><b>Name: </b>'.$_POST['name'].'</p>';
        $email_message .= '<p><b>Email: </b>'.$_POST['email'].'</p>';
        $email_message .= '<p><b>Phone: </b>'.$_POST['phone'].'</p>';
        $email_message .= '<p><b>Message: </b>'.$_POST['message'].'</p>';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(SMTP_FROM);
        $mail->addAddress($_POST['instructor_email']);
        $mail->addReplyTo(SMTP_FROM);
        $mail->isHTML(true);
        $mail->Subject = 'Enquery About Course: ' . $_POST['course_title'];
        $mail->Body = $email_message;
        $mail->send();

        $success_message = "Enquery has been sent successfully.";
        $_SESSION['success_message'] = $success_message;
        header("location: ".BASE_URL."course/".$_REQUEST['slug']);
        exit;
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".BASE_URL."course/".$_REQUEST['slug']);
        exit;
    }
}
?>

<div class="page-top page-course" style="background-image: url('<?php echo BASE_URL; ?>uploads/<?php echo $course['featured_banner']; ?>')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h2><?php echo $course['title']; ?></h2>
                <div class="category"><a href=""><?php echo $course['category_name']; ?></a></div>
                <div class="rating">
                    <span><?php echo number_format($course['average_rating'],1); ?></span>
                    <span>
                    <?php
                    if($course['average_rating'] == 5) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <?php
                    } elseif($course['average_rating'] == 4.5) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <?php
                    } elseif($course['average_rating'] == 4) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <?php
                    } elseif($course['average_rating'] == 3.5) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <?php
                    } elseif($course['average_rating'] == 3) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <?php
                    } elseif($course['average_rating'] == 2.5) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <?php
                    } elseif($course['average_rating'] == 2) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <?php
                    } elseif($course['average_rating'] == 1.5) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <?php
                    } elseif($course['average_rating'] == 1) {
                        ?>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <?php
                    } elseif($course['average_rating'] == 0) {
                        ?>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <?php
                    }
                    ?>
                    </span>
                    <span class="tot">(<?php echo $course['total_rating']; ?> Ratings)</span>
                    <span class="std"><?php echo $course['total_student']; ?> Students</span>
                </div>
                <div class="author">
                    Created by <a href="<?php echo BASE_URL; ?>instructor/<?php echo $course['instructor_id']; ?>"><?php echo $course['instructor_name']; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="course-detail pt_50 pb_50">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12">

                <div class="main-item mb_50">

                    <ul class="nav nav-tabs d-flex justify-content-center" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-overview" data-bs-toggle="tab" data-bs-target="#tab-overview-pane" type="button" role="tab" aria-controls="tab-overview-pane" aria-selected="true">Overview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-curriculum" data-bs-toggle="tab" data-bs-target="#tab-curriculum-pane" type="button" role="tab" aria-controls="tab-curriculum-pane" aria-selected="false">Curriculum</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-instructor" data-bs-toggle="tab" data-bs-target="#tab-instructor-pane" type="button" role="tab" aria-controls="tab-instructor-pane" aria-selected="false">Instructor</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-review" data-bs-toggle="tab" data-bs-target="#tab-review-pane" type="button" role="tab" aria-controls="tab-review-pane" aria-selected="false">Reviews</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-enquery" data-bs-toggle="tab" data-bs-target="#tab-enquery-pane" type="button" role="tab" aria-controls="tab-enquery-pane" aria-selected="false">Enquery</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        
                        <div class="tab-pane fade show active description-data" id="tab-overview-pane" role="tabpanel" aria-labelledby="tab-overview" tabindex="0">
                            <!-- Detail -->
                            <?php echo $course['description']; ?>
                            <!-- // Detail -->
                        </div>

                        <div class="tab-pane fade" id="tab-curriculum-pane" role="tabpanel" aria-labelledby="tab-curriculum" tabindex="0">


                            <!-- Curriculum -->
                            <div class="curriculum">
                                <div class="accordion" id="accordionExample">

                                    <?php
                                    $i=0;
                                    $statement = $pdo->prepare("SELECT * FROM modules WHERE course_id=?");
                                    $statement->execute([$course['id']]);
                                    $modules = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($modules as $module) {
                                        $i++;
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading_<?php echo $i; ?>">
                                                <button class="accordion-button <?php if($i!=1) {echo 'collapsed';} ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $i; ?>" aria-expanded="<?php if($i==1) {echo 'true';} else {echo 'false';} ?>" aria-controls="collapse_<?php echo $i; ?>">
                                                    <?php echo $module['name']; ?>
                                                </button>
                                                <div class="timing">
                                                    <?php echo ($module['total_video']+$module['total_resource']); ?> Lectures - <?php echo convert_seconds_to_minutes_hours($module['total_video_second']); ?>
                                                </div>
                                            </h2>
                                            <div id="collapse_<?php echo $i; ?>" class="accordion-collapse collapse <?php if($i==1) {echo 'show';} ?>" aria-labelledby="heading_<?php echo $i; ?>">
                                                <div class="accordion-body">
                                                    <?php
                                                    $statement1 = $pdo->prepare("SELECT * FROM lessons WHERE module_id=?");
                                                    $statement1->execute([$module['id']]);
                                                    $lessons = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($lessons as $lesson) {
                                                        if($lesson['lesson_type'] == 'video') {
                                                            $icon = 'far fa-play-circle';
                                                        } else {
                                                            $icon = 'far fa-file';
                                                        }
                                                        ?>
                                                        <!-- Lecture Item -->
                                                        <div class="acb-item">
                                                            <div class="left">
                                                                <div class="icon">
                                                                    <i class="<?php echo $icon; ?>"></i> 
                                                                </div>
                                                                <div class="title">
                                                                    <?php if($lesson['video_type'] == 'youtube'): ?>
                                                                        <?php if($lesson['is_preview'] == 1): ?>
                                                                        <a class="video-button" href="http://www.youtube.com/watch?v=<?php echo $lesson['video_content']; ?>">
                                                                            <?php echo $lesson['name']; ?>
                                                                        </a>
                                                                        <?php else: ?>
                                                                            <?php echo $lesson['name']; ?>
                                                                        <?php endif; ?>

                                                                    <?php elseif($lesson['video_type'] == 'vimeo'): ?>
                                                                        <?php if($lesson['is_preview'] == 1): ?>
                                                                        <a class="video-button" href="https://vimeo.com/<?php echo $lesson['video_content']; ?>">
                                                                            <?php echo $lesson['name']; ?>
                                                                        </a>
                                                                        <?php else: ?>
                                                                            <?php echo $lesson['name']; ?>
                                                                        <?php endif; ?>

                                                                    <?php elseif($lesson['video_type'] == 'mp4'): ?>
                                                                        <?php if($lesson['is_preview'] == 1): ?>
                                                                        <a class="video-button" href="<?php echo BASE_URL; ?>uploads/<?php echo $lesson['video_content']; ?>">
                                                                            <?php echo $lesson['name']; ?>
                                                                        </a>
                                                                        <?php else: ?>
                                                                            <?php echo $lesson['name']; ?>
                                                                        <?php endif; ?>

                                                                    <?php else: ?>

                                                                        <?php if($lesson['is_preview'] == 1): ?>
                                                                        <a href="<?php echo BASE_URL; ?>uploads/<?php echo $lesson['resource_content']; ?>">
                                                                            <?php echo $lesson['name']; ?>
                                                                        </a>
                                                                        <?php else: ?>
                                                                            <?php echo $lesson['name']; ?>
                                                                        <?php endif; ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="right">
                                                                <div class="preview">
                                                                    <?php if($lesson['video_type'] == 'youtube'): ?>
                                                                        <?php if($lesson['is_preview'] == 1): ?>
                                                                        <a class="video-button" href="http://www.youtube.com/watch?v=<?php echo $lesson['video_content']; ?>">
                                                                            Preview
                                                                        </a>
                                                                        <?php endif; ?>
                                                                    <?php elseif($lesson['video_type'] == 'vimeo'): ?>
                                                                        <?php if($lesson['is_preview'] == 1): ?>
                                                                        <a class="video-button" href="https://vimeo.com/<?php echo $lesson['video_content']; ?>">
                                                                        Preview
                                                                        </a>
                                                                        <?php endif; ?>
                                                                    <?php elseif($lesson['video_type'] == 'mp4'): ?>
                                                                        <?php if($lesson['is_preview'] == 1): ?>
                                                                        <a class="video-button" href="<?php echo BASE_URL; ?>uploads/<?php echo $lesson['video_content']; ?>">
                                                                        Preview
                                                                        </a>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="time">
                                                                    <?php if($lesson['lesson_type'] == 'video'): ?>
                                                                    <?php echo convert_seconds_to_minutes_hours($lesson['duration_second']); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- // Lecture Item -->
                                                        <?php
                                                    }
                                                    ?>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    
                                </div>
                            </div>
                            <!-- // Curriculum -->



                        </div>



                        <div class="tab-pane fade" id="tab-instructor-pane" role="tabpanel" aria-labelledby="tab-instructor" tabindex="0">
                            <!-- Instructor -->
                            <h2 class="top">Instructors</h2>
                            <div class="instructor">
                                <div class="instructor-section">
                                    <div class="instructor-box d-flex justify-content-start">
                                        <div class="left">
                                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $course['instructor_photo']; ?>" alt="">
                                        </div>
                                        <div class="right">
                                            <div class="name"><?php echo $course['instructor_name']; ?></div>
                                            <div class="designation"><?php echo $course['instructor_designation']; ?></div>
                                            <div class="rating">
                                                <i class="fas fa-star"></i>
                                                <?php echo number_format($course['instructor_average_rating'],1); ?> (<?php echo $course['instructor_total_rating']; ?> Ratings)
                                            </div>
                                            <div class="text">
                                                <?php echo nl2br($course['instructor_biography']); ?>
                                            </div>
                                            <?php if($course['instructor_facebook']!='' || $course['instructor_twitter']!='' || $course['instructor_linkedin']!='' || $course['instructor_instagram']!=''): ?>
                                            <div class="social">
                                                <ul>
                                                    <?php if($course['instructor_facebook']!=''): ?>
                                                    <li><a href="<?php echo $course['instructor_facebook']; ?>"><i class="fab fa-facebook-f"></i></a></li>
                                                    <?php endif; ?>
                                                    <?php if($course['instructor_twitter']!=''): ?>
                                                    <li><a href="<?php echo $course['instructor_twitter']; ?>"><i class="fab fa-twitter"></i></a></li>
                                                    <?php endif; ?>
                                                    <?php if($course['instructor_linkedin']!=''): ?>
                                                    <li><a href="<?php echo $course['instructor_linkedin']; ?>"><i class="fab fa-linkedin-in"></i></a></li>
                                                    <?php endif; ?>
                                                    <?php if($course['instructor_instagram']!=''): ?>
                                                    <li><a href="<?php echo $course['instructor_instagram']; ?>"><i class="fab fa-instagram"></i></a></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                            <!-- // Instructor -->
                        </div>



                        <div class="tab-pane fade" id="tab-review-pane" role="tabpanel" aria-labelledby="tab-review" tabindex="0">
                            <!-- Review -->
                            <h2 class="top">Reviews</h2>
                            <h2 class="total-rating"><?php echo $course['average_rating']; ?> (<?php echo $course['total_rating']; ?> Ratings)</h2>

                            <div class="review">

                                <?php
                                $statement = $pdo->prepare("SELECT t1.*,
                                                    t2.name as student_name,
                                                    t2.photo as student_photo 
                                                    FROM reviews t1
                                                    JOIn students t2
                                                    ON t1.student_id = t2.id
                                                    WHERE t1.course_id=?");
                                $statement->execute([$course['id']]);
                                $all_reviews = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($all_reviews as $row) {
                                    ?>
                                    <div class="review-section">
                                        <div class="review-box d-flex justify-content-start">
                                            <div class="left">
                                                <?php if($row['student_photo'] != ''): ?>
                                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['student_photo']; ?>" alt="">
                                                <?php else: ?>
                                                <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="">
                                                <?php endif; ?>
                                            </div>
                                            <div class="right">
                                                <div class="name"><?php echo $row['student_name']; ?></div>
                                                <div class="rating">
                                                    <?php
                                                    if($row['rating'] == 5) {
                                                        ?>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <?php
                                                    } elseif($row['rating'] == 4) {
                                                        ?>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <?php
                                                    } elseif($row['rating'] == 3) {
                                                        ?>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <?php
                                                    } elseif($row['rating'] == 2) {
                                                        ?>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <?php
                                                    } elseif($row['rating'] == 1) {
                                                        ?>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    ?>
                                                    <?php
                                                }
                                                ?>
                                                </div>
                                                <div class="text">
                                                    <?php echo nl2br($row['comment']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>



                                <?php
                                $show = 1;
                                if(!isset($_SESSION['student'])) {
                                    $show = 0;
                                }
                                if(isset($_SESSION['student'])) {
                                    // If he has access to this course
                                    $statement = $pdo->prepare("SELECT * FROM order_details WHERE course_id=? AND student_id=?");
                                    $statement->execute([$course['id'],$_SESSION['student']['id']]);
                                    $total = $statement->rowCount();
                                    if($total == 0) {
                                        $show = 2;
                                    }

                                    $q = $pdo->prepare("SELECT * FROM reviews WHERE course_id=? AND student_id=?");
                                    $q->execute([$course['id'],$_SESSION['student']['id']]);
                                    $total1 = $q->rowCount();
                                    if($total1) {
                                        $show = 3;
                                    }
                                }
                                ?>

                                <?php if($show == 0): ?>
                                    <a href="<?php echo BASE_URL; ?>login" class="text-danger text-decoration-underline">Please login as student to give a review</a>
                                <?php endif; ?>
                                
                                <?php if($show == 2): ?>
                                    <span class="text-danger">
                                        You need to enroll this course to give a review.
                                    </span>
                                <?php endif; ?>

                                <?php if($show == 3): ?>
                                    <span class="text-danger">
                                        You have already given a review.
                                    </span>
                                <?php endif; ?>

                                <?php if($show == 1): ?>
                                <div class="mt_40"></div>
                                <h2>Leave Your Review</h2>
                                <form action="" method="post">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                <div class="mb-3">
                                    <div class="give-review-auto-select">
                                        <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', (event) => {
                                            const stars = document.querySelectorAll('.star-rating label');
                                            stars.forEach(star => {
                                                star.addEventListener('click', function() {
                                                    stars.forEach(s => s.style.color = '#ccc');
                                                    this.style.color = '#f5b301';
                                                    let previousStar = this.previousElementSibling;
                                                    while(previousStar) {
                                                        if (previousStar.tagName === 'LABEL') {
                                                            previousStar.style.color = '#f5b301';
                                                        }
                                                        previousStar = previousStar.previousElementSibling;
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Comment"></textarea>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary" name="form_review_submit">Submit</button>
                                </div>
                                </form>
                                <?php endif; ?>


                            </div>
                            <!-- // Review -->
                        </div>



                        <div class="tab-pane fade" id="tab-enquery-pane" role="tabpanel" aria-labelledby="tab-enquery" tabindex="0">
                            <!-- Enquery -->
                            <h2 class="top">Ask Your Question</h2>
                            <div class="enquery-form">
                                <form action="" method="post">
                                    <input type="hidden" name="course_title" value="<?php echo $course['title']; ?>">
                                    <input type="hidden" name="instructor_email" value="<?php echo $course['instructor_email']; ?>">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="name" placeholder="Full Name">
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" name="email" placeholder="Email Address">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="phone" placeholder="Phone Number">
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control h-150" rows="3" name="message" placeholder="Message"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary" name="form_enquery">
                                            Send Message
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- // Enquery -->
                        </div>

                    </div>
                    
                </div>
                    
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="course-detail-sidebar">
                    <div class="sidebar-video" style="background-image: url(<?php echo BASE_URL; ?>uploads/<?php echo $course['featured_photo'] ?>);">
                        <?php if($course['featured_video_type'] == 'youtube'): ?>
                            <a class="video-button" href="https://www.youtube.com/watch?v=<?php echo $course['featured_video_content'] ?>"><span></span></a>
                        <?php elseif($course['featured_video_type'] == 'vimeo'): ?>
                            <a class="video-button" href="https://www.vimeo.com/<?php echo $course['featured_video_content'] ?>"><span></span></a>
                        <?php else: ?>
                            <a class="video-button" href="<?php echo BASE_URL; ?>uploads/<?php echo $course['featured_video_content'] ?>"><span></span></a>
                        <?php endif; ?>
                    </div>
                    <div class="price">
                        <?php if(isset($_SESSION['coupon'][$course['id']])): ?>
                            <?php
                            $q = $pdo->prepare("SELECT * FROM coupons WHERE coupon_name=?");
                            $q->execute([$_SESSION['coupon'][$course['id']]]);
                            $coupon_data = $q->fetch(PDO::FETCH_ASSOC);
                            $coupon_discount = $coupon_data['discount_percentage'];
                            // Now calculate this discount with course price
                            $price = $course['price'];
                            $discount = ($price*$coupon_discount)/100;
                            $final_price = $price - $discount;
                            $final_price = floor($final_price);
                            ?>
                            $<?php echo $final_price; ?> <del>$<?php echo $course['price']; ?></del>
                        <?php else: ?>
                            $<?php echo $course['price']; ?> <?php if($course['price_old']!=''): ?><del>$<?php echo $course['price_old']; ?></del><?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="cart-button">
                        <form action="<?php echo BASE_URL; ?>cart-add.php" method="post">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" class="btn btn-primary" name="form_cart_add">Add to Cart</button>
                        </form>
                    </div>
                    <div class="buy-now-button">
                        <a href="" class="btn btn-primary">Buy Now</a>
                    </div>
                    <div class="include">
                        <h3>Course Includes:</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th><i class="far fa-clock"></i> Total Video Hours:</th>
                                    <td>
                                        <?php echo convert_seconds_to_minutes_hours($course['total_video_second']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="far fa-play-circle"></i> Total Videos:</th>
                                    <td><?php echo $course['total_video']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="far fa-file"></i> Total Resources:</th>
                                    <td><?php echo $course['total_resource']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-sync-alt"></i> Level:</th>
                                    <td><?php echo $course['level_name']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="far fa-question-circle"></i> Access:</th>
                                    <td>Lifetime</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-award"></i>Certificate:</th>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-globe"></i> Language:</th>
                                    <td><?php echo $course['language_name']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-th"></i> Category:</th>
                                    <td><?php echo $course['category_name']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="far fa-clock"></i> Last Updated At:</th>
                                    <td><?php echo date('F d, Y', strtotime($course['updated_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="coupon">
                        <h3>Have any coupon?</h3>
                    
                        <?php if(isset($_SESSION['coupon'][$course['id']])): ?>
                        <?php
                            echo 'Applied Coupon = '.$_SESSION['coupon'][$course['id']];
                        ?>
                        <form action="" method="post">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="form_remove_coupon">Remove Coupon</button>
                        </form>
                        <?php else: ?>
                        <form action="" method="post">
                            <div class="input-group">
                                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                <input type="text" name="coupon_name" class="form-control" placeholder="Enter Coupon Code">
                                <button class="btn btn-primary" type="Submit" name="form_coupon_apply">Apply</button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                    <div class="share">
                        <h3>Share On:</h3>
                        <div class="social">
                            <ul>
                                <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href=""><i class="fab fa-twitter"></i></a></li>
                                <li><a href=""><i class="fab fa-linkedin-in"></i></a></li>
                                <li><a href=""><i class="fab fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>