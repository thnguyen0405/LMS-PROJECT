<?php include "header.php"; ?>

<?php
$fullUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$ex_result = explode("instructor-course-edit-basic", $fullUrl);
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
        $statement = $pdo->prepare("SELECT * FROM courses WHERE slug=? AND id!=?");
        $statement->execute([$_POST['slug'], $_REQUEST['id']]);
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

        $statement = $pdo->prepare("UPDATE courses SET 
                                title=?,
                                slug=?,
                                description=?,
                                price=?,
                                price_old=?,
                                category_id=?,
                                level_id=?,
                                language_id=?,
                                updated_at=?
                                
                                WHERE id=?");
        $statement->execute([
            $_POST['title'],
            $_POST['slug'],
            $_POST['description'],
            $_POST['price'],
            $_POST['price_old'],
            $_POST['category_id'],
            $_POST['level_id'],
            $_POST['language_id'],
            date('Y-m-d H:i:s'),
            $_REQUEST['id']
        ]);

        $success_message = 'Course Basic Info is updated successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-basic/'.$_REQUEST['id']);
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-basic/'.$_REQUEST['id']);
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
                <h2>Edit Course Basic Info</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit Course Basic Info</li>
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
                    <li class="active"><a href="<?php echo BASE_URL; ?>instructor-course-edit-basic/<?php echo $_REQUEST['id']; ?>">Basic Information</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-photo/<?php echo $_REQUEST['id']; ?>">Featured Photo</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-banner/<?php echo $_REQUEST['id']; ?>">Featured Banner</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-video/<?php echo $_REQUEST['id']; ?>">Featured Video</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-curriculum/<?php echo $_REQUEST['id']; ?>">Curriculum</a></li>
                </ul>

                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Title *</label>
                            <div class="form-group">
                                <input type="text" name="title" class="form-control" value="<?php echo $course_data[0]['title']; ?>">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Slug *</label>
                            <div class="form-group">
                                <input type="text" name="slug" class="form-control" value="<?php echo $course_data[0]['slug']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Price *</label>
                            <div class="form-group">
                                <input type="text" name="price" class="form-control" value="<?php echo $course_data[0]['price']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Old Price</label>
                            <div class="form-group">
                                <input type="text" name="price_old" class="form-control" value="<?php echo $course_data[0]['price_old']; ?>">
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
                                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $course_data[0]['category_id']) ? 'selected' : '' ?>><?php echo $row['name']; ?></option>
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
                                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $course_data[0]['level_id']) ? 'selected' : '' ?>><?php echo $row['name']; ?></option>
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
                                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $course_data[0]['language_id']) ? 'selected' : '' ?>><?php echo $row['name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Description *</label>
                            <div class="form-group">
                                <textarea name="description" class="form-control editor"><?php echo $course_data[0]['description']; ?></textarea>
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