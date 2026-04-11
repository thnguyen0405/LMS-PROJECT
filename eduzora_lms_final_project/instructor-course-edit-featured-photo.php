<?php include "header.php"; ?>

<?php
$fullUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$ex_result = explode("instructor-course-edit-featured-photo", $fullUrl);
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
        $path = $_FILES['featured_photo']['name'];
        $path_tmp = $_FILES['featured_photo']['tmp_name'];
        if($path=='') {
            throw new Exception("Please choose a featured photo");
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "course_featured_photo_".time().".".$extension;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);
            if($mime != 'image/jpeg' && $mime != 'image/png' && $mime != 'image/gif') {
                throw new Exception("Please upload a valid photo for featured photo. Only jpg, jpeg, png and gif are allowed");
            }
        }

        if($_POST['current_featured_photo'] != '') {
            unlink('uploads/'.$_POST['current_featured_photo']);
        }
        move_uploaded_file($path_tmp, 'uploads/'.$filename);

        $statement = $pdo->prepare("UPDATE courses SET 
                                featured_photo=?
                                WHERE id=?");
        $statement->execute([
            $filename,
            $_REQUEST['id']
        ]);

        $success_message = 'Course Featured Photo is updated successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-featured-photo/'.$_REQUEST['id']);
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-featured-photo/'.$_REQUEST['id']);
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
                <h2>Edit Course Featured Photo</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit Course Featured Photo</li>
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
                    <li class="active"><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-photo/<?php echo $_REQUEST['id']; ?>">Featured Photo</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-banner/<?php echo $_REQUEST['id']; ?>">Featured Banner</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-video/<?php echo $_REQUEST['id']; ?>">Featured Video</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-curriculum/<?php echo $_REQUEST['id']; ?>">Curriculum</a></li>
                </ul>

                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="current_featured_photo" value="<?php echo $course_data[0]['featured_photo']; ?>">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Existing Featured Photo</label>
                            <div class="form-group">
                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $course_data[0]['featured_photo']; ?>" alt="" class="w-200">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Change Featured Photo *</label>
                            <div class="form-group">
                                <input type="file" name="featured_photo">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input name="form_submit" type="submit" class="btn btn-primary" value="Update">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>