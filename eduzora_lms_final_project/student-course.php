<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php

// Check if this student has access to this course 
$statement = $pdo->prepare("SELECT * FROM order_details WHERE course_id=? AND student_id=?");
$statement->execute([$_REQUEST['id'], $_SESSION['student']['id']]);
$total = $statement->rowCount();
if($total == 0) {
    $_SESSION['error_message'] = "You do not have access to this course";
    header('location: '.BASE_URL.'student-courses');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$course_data = $statement->fetch(PDO::FETCH_ASSOC);
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Course: <?php echo $course_data['title']; ?></h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $course_data['title']; ?></li>
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
            <div class="col-lg-9 col-md-12 course-detail">
                <!-- Curriculum -->
                <div class="curriculum">
                    <div class="accordion" id="accordionExample">

                        <?php
                        $i=0;
                        $statement = $pdo->prepare("SELECT * FROM modules WHERE course_id=?");
                        $statement->execute([$_REQUEST['id']]);
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
                                                            <a class="video-button" href="http://www.youtube.com/watch?v=<?php echo $lesson['video_content']; ?>">
                                                                <?php echo $lesson['name']; ?>
                                                            </a>
                                                        <?php elseif($lesson['video_type'] == 'vimeo'): ?>
                                                            <a class="video-button" href="https://vimeo.com/<?php echo $lesson['video_content']; ?>">
                                                                <?php echo $lesson['name']; ?>
                                                            </a>
                                                        <?php elseif($lesson['video_type'] == 'mp4'): ?>
                                                            <a class="video-button" href="<?php echo BASE_URL; ?>uploads/<?php echo $lesson['video_content']; ?>">
                                                                <?php echo $lesson['name']; ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?php echo BASE_URL; ?>uploads/<?php echo $lesson['resource_content']; ?>">
                                                                <?php echo $lesson['name']; ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="right">
                                                    <div class="preview">
                                                        
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
        </div>
    </div>
</div>

<?php include "footer.php"; ?>