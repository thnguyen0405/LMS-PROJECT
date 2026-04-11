<?php include "header.php"; ?>

<?php
$fullUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$ex_result = explode("instructor-course-edit-featured-video", $fullUrl);
$slashCount = substr_count($ex_result[1], '/');
if($slashCount > 1) {
    header("location: ".BASE_URL."instructor-courses.php");
    exit;
}
?>

<?php
if(!isset($_SESSION['instructor'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM courses WHERE id=? AND instructor_id=?");
$statement->execute([$_REQUEST['id'], $_SESSION['instructor']['id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".BASE_URL."instructor-courses.php");
    exit;
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM courses WHERE id=? AND status=?");
$statement->execute([$_REQUEST['id'],'In Review']);
$total = $statement->rowCount();
if($total) {
    header("location: ".BASE_URL."instructor-courses.php");
    exit;
}
?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['featured_video_type'] == '') {
            throw new Exception("Please select a featured video type");
        }
        // If video type is youtube then youtube content is mandatory
        if($_POST['featured_video_type'] == 'youtube') {
            if($_POST['featured_video_content_youtube'] == '') {
                throw new Exception("Please enter YouTube video content");
            }
        }
        // If video type is vimeo then vimeo content is mandatory
        elseif($_POST['featured_video_type'] == 'vimeo') {
            if($_POST['featured_video_content_vimeo'] == '') {
                throw new Exception("Please enter Vimeo video content");
            }
        }
        // If video type is mp4 then mp4 content is mandatory
        else {
            $path_mp4 = $_FILES['featured_video_content_mp4']['name'];
            $path_tmp_mp4 = $_FILES['featured_video_content_mp4']['tmp_name'];
            if($path_mp4=='') {
                throw new Exception("Please choose a featured video");
            }
            $extension_mp4 = pathinfo($path_mp4, PATHINFO_EXTENSION);
            $filename_mp4 = "course_featured_video_".time().".".$extension_mp4;
            $finfo_mp4 = finfo_open(FILEINFO_MIME_TYPE);
            $mime_mp4 = finfo_file($finfo_mp4, $path_tmp_mp4);
            if($mime_mp4 != 'video/mp4') {
                throw new Exception("Please upload a valid video. Only mp4 is allowed");
            }
        }

        if($_POST['current_featured_video_type'] == 'mp4') {
            unlink('uploads/'.$_POST['current_featured_video_content']);
        }

        if($_POST['featured_video_type'] == 'youtube')  {
            $featured_video_content = $_POST['featured_video_content_youtube'];
        }
        if($_POST['featured_video_type'] == 'vimeo') {
            $featured_video_content = $_POST['featured_video_content_vimeo'];
        }
        if($_POST['featured_video_type'] == 'mp4') {
            $featured_video_content = $filename_mp4;
            move_uploaded_file($path_tmp_mp4, 'uploads/'.$filename_mp4);
        }

        $statement = $pdo->prepare("UPDATE courses SET 
                                featured_video_type=?,
                                featured_video_content=?
                                WHERE id=?");
        $statement->execute([
            $_POST['featured_video_type'],
            $featured_video_content,
            $_REQUEST['id']
        ]);
        

        $success_message = 'Course Featured Video is updated successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-featured-video/'.$_REQUEST['id']);
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-featured-video/'.$_REQUEST['id']);
        exit;
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$course_data = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Course Featured Video</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit Course Featured Video</li>
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
                    <?php include "instructor-sidebar.php"; ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">

                <ul class="nav-course-edit">
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-basic/<?php echo $_REQUEST['id']; ?>">Basic Information</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-photo/<?php echo $_REQUEST['id']; ?>">Featured Photo</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-banner/<?php echo $_REQUEST['id']; ?>">Featured Banner</a></li>
                    <li class="active"><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-video/<?php echo $_REQUEST['id']; ?>">Featured Video</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-curriculum/<?php echo $_REQUEST['id']; ?>">Curriculum</a></li>
                </ul>

                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="current_featured_video_type" value="<?php echo $course_data[0]['featured_video_type']; ?>">
                    <input type="hidden" name="current_featured_video_content" value="<?php echo $course_data[0]['featured_video_content']; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="">Existing Featured Video Type</label>
                                <div class="form-group">
                                    <?php echo $course_data[0]['featured_video_type']; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="">Existing Featured Video Content</label>
                                <div class="form-group video-container">
                                    <?php if($course_data[0]['featured_video_type'] == 'youtube'): ?>
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $course_data[0]['featured_video_content']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                                    <?php elseif($course_data[0]['featured_video_type'] == 'vimeo'): ?>
                                        <iframe src="https://player.vimeo.com/video/<?php echo $course_data[0]['featured_video_content']; ?>" width="560" height="315" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                    <?php else: ?>
                                        <video width="560" height="315" controls>
                                            <source src="<?php echo BASE_URL; ?>uploads/<?php echo $course_data[0]['featured_video_content']; ?>" type="video/mp4">
                                        </video>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="">Change Featured Video Type *</label>
                                <div class="form-group">
                                    <select name="featured_video_type" class="form-select" id="featured_video_type">
                                        <option value="">Select Video Type</option>
                                        <option value="youtube">YouTube</option>
                                        <option value="vimeo">Vimeo</option>
                                        <option value="mp4">MP4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3" id="youtube" style="display:none;">
                                <label for="">Change Featured Video Content (YouTube) *</label>
                                <div class="form-group">
                                    <input type="text" name="featured_video_content_youtube" class="form-control" value="">
                                </div>
                            </div>
                            <div class="mb-3" id="vimeo" style="display:none;">
                                <label for="">Change Featured Video Content (Vimeo) *</label>
                                <div class="form-group">
                                    <input type="text" name="featured_video_content_vimeo" class="form-control" value="">
                                </div>
                            </div>
                            <div class="mb-3" id="mp4" style="display:none;">
                                <label for="">Change Featured Video Content (MP4) *</label>
                                <div class="form-group">
                                    <input type="file" name="featured_video_content_mp4">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <input name="form_submit" type="submit" class="btn btn-primary" value="Update">
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#featured_video_type').on('change',function(){
            // Hide all video content sections
            $('#youtube, #vimeo, #mp4').hide();
            
            // Get selected value
            var selectedValue = $(this).val();

            // Show corresponding section based on selected value
            if (selectedValue == 'youtube') {
                $('#youtube').show();
            } else if (selectedValue == 'vimeo') {
                $('#vimeo').show();
            } else if (selectedValue == 'mp4') {
                $('#mp4').show();
            }
        });
    });
</script>

<?php include "footer.php"; ?>