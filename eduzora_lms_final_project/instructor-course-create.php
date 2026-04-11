<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['instructor'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['title'] == '') {
            throw new Exception("Title can not be empty");
        }
        if($_POST['slug'] == '') {
            throw new Exception("Slug can not be empty");
        }
        // Slug format check. Only allow hiphen, underscore, alphanumeric characters
        if(!preg_match('/^[a-zA-Z0-9_-]+$/', $_POST['slug'])) {
            throw new Exception("Slug can only contain alphanumeric characters, hiphen and underscore");
        }
        // Slug duplicate check
        $statement = $pdo->prepare("SELECT * FROM courses WHERE slug = ?");
        $statement->execute([$_POST['slug']]);
        $total = $statement->rowCount();
        if($total) {
            throw new Exception("Slug already exists");
        }

        // Price should be integer that will start from 0 and limit to 1000
        if($_POST['price'] == '') {
            throw new Exception("Price can not be empty");
        }
        if(!is_numeric($_POST['price'])) {
            throw new Exception("Price should be numeric");
        }
        if($_POST['price'] < 0 || $_POST['price'] > 1000) {
            throw new Exception("Price should be between 0 and 1000");
        }

        // Old Price is not mandatory and if someone gives it then it must be integer and it will start from 0 and limit to 1000
        if($_POST['price_old'] != '') {
            if(!is_numeric($_POST['price_old'])) {
                throw new Exception("Old Price should be numeric");
            }
            if($_POST['price_old'] < 0 || $_POST['price_old'] > 1000) {
                throw new Exception("Old Price should be between 0 and 1000");
            }
        }

        if($_POST['description'] == '') {
            throw new Exception("Description can not be empty");
        }

        if($_POST['category_id'] == '') {
            throw new Exception("You must select a category");
        }
        if($_POST['level_id'] == '') {
            throw new Exception("You must select a level");
        }
        if($_POST['language_id'] == '') {
            throw new Exception("You must select a language");
        }

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

        $path1 = $_FILES['featured_banner']['name'];
        $path1_tmp = $_FILES['featured_banner']['tmp_name'];
        if($path1=='') {
            throw new Exception("Please choose a featured banner");
        } else {
            $extension1 = pathinfo($path1, PATHINFO_EXTENSION);
            $filename1 = "course_featured_banner_".time().".".$extension1;
            $finfo1 = finfo_open(FILEINFO_MIME_TYPE);
            $mime1 = finfo_file($finfo1, $path1_tmp);
            if($mime1 != 'image/jpeg' && $mime1 != 'image/png' && $mime1 != 'image/gif') {
                throw new Exception("Please upload a valid photo for featured banner. Only jpg, jpeg, png and gif are allowed");
            }
        }

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



        move_uploaded_file($path_tmp, 'uploads/'.$filename);
        move_uploaded_file($path1_tmp, 'uploads/'.$filename1);

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

        $statement = $pdo->prepare("INSERT INTO courses (title, slug, description, price, price_old, category_id, level_id, language_id, instructor_id, total_student, total_rating, total_rating_score, average_rating, featured_photo, featured_banner, featured_video_type, featured_video_content, total_video_second, total_video, total_resource, updated_at, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute([
            $_POST['title'],
            $_POST['slug'],
            $_POST['description'],
            $_POST['price'],
            $_POST['price_old'],
            $_POST['category_id'],
            $_POST['level_id'],
            $_POST['language_id'],
            $_SESSION['instructor']['id'],
            0,
            0,
            0,
            0,
            $filename,
            $filename1,
            $_POST['featured_video_type'],
            $featured_video_content,
            0,
            0,
            0,
            date('Y-m-d H:i:s'),
            'Pending'
        ]);
        

        $success_message = 'Course is created successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-create');
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;

        $_SESSION['title'] = $_POST['title'];
        $_SESSION['slug'] = $_POST['slug'];
        $_SESSION['price'] = $_POST['price'];
        $_SESSION['price_old'] = $_POST['price_old'];
        $_SESSION['description'] = $_POST['description'];
        $_SESSION['category_id'] = $_POST['category_id'];
        $_SESSION['level_id'] = $_POST['level_id'];
        $_SESSION['language_id'] = $_POST['language_id'];
        
        header('location: '.BASE_URL.'instructor-course-create');
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Create Course</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Create Course</li>
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
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Title *</label>
                            <div class="form-group">
                                <input type="text" name="title" class="form-control" value="<?php if(isset($_SESSION['title'])) {echo $_SESSION['title']; unset($_SESSION['title']); } ?>">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Slug *</label>
                            <div class="form-group">
                                <input type="text" name="slug" class="form-control" value="<?php if(isset($_SESSION['slug'])) {echo $_SESSION['slug']; unset($_SESSION['slug']); } ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Price *</label>
                            <div class="form-group">
                                <input type="text" name="price" class="form-control" value="<?php if(isset($_SESSION['price'])) {echo $_SESSION['price']; unset($_SESSION['price']); } ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Old Price</label>
                            <div class="form-group">
                                <input type="text" name="price_old" class="form-control" value="<?php if(isset($_SESSION['price_old'])) {echo $_SESSION['price_old']; unset($_SESSION['price_old']); } ?>">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Select Category *</label>
                            <div class="form-group">
                                <select name="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>" <?php if(isset($_SESSION['category_id'])) {if($row['id'] == $_SESSION['category_id']) {echo 'selected'; unset($_SESSION['category_id']);}} ?>><?php echo $row['name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Select Level *</label>
                            <div class="form-group">
                                <select name="level_id" class="form-select">
                                    <option value="">Select Level</option>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM levels ORDER BY id ASC");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>"  <?php if(isset($_SESSION['level_id'])) {if($row['id'] == $_SESSION['level_id']) {echo 'selected'; unset($_SESSION['level_id']);}} ?>><?php echo $row['name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Select Language *</label>
                            <div class="form-group">
                                <select name="language_id" class="form-select">
                                    <option value="">Select Language</option>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM languages ORDER BY name ASC");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>"  <?php if(isset($_SESSION['language_id'])) {if($row['id'] == $_SESSION['language_id']) {echo 'selected'; unset($_SESSION['language_id']);}} ?>><?php echo $row['name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Description *</label>
                            <div class="form-group">
                                <textarea name="description" class="form-control editor"><?php if(isset($_SESSION['description'])) {echo $_SESSION['description']; unset($_SESSION['description']); } ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Featured Photo *</label>
                            <div class="form-group">
                                <input type="file" name="featured_photo">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Featured Banner *</label>
                            <div class="form-group">
                                <input type="file" name="featured_banner">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Featured Video Type *</label>
                            <div class="form-group">
                                <select name="featured_video_type" class="form-select" id="featured_video_type">
                                    <option value="">Select Video Type</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="vimeo">Vimeo</option>
                                    <option value="mp4">MP4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="youtube" style="display:none;">
                            <label for="">Featured Video Content (YouTube) *</label>
                            <div class="form-group">
                                <input type="text" name="featured_video_content_youtube" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="vimeo" style="display:none;">
                            <label for="">Featured Video Content (Vimeo) *</label>
                            <div class="form-group">
                                <input type="text" name="featured_video_content_vimeo" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="mp4" style="display:none;">
                            <label for="">Featured Video Content (MP4) *</label>
                            <div class="form-group">
                                <input type="file" name="featured_video_content_mp4">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input name="form_submit" type="submit" class="btn btn-primary" value="Submit">
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