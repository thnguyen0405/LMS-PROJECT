<?php include "header.php"; ?>

<?php
$fullUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$ex_result = explode("instructor-course-edit-curriculum", $fullUrl);
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
$statement->execute([$_REQUEST['id'],$_SESSION['instructor']['id']]);
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
if(isset($_POST['form_module_add'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        if($_POST['item_order'] == '') {
            throw new Exception("Item Order can not be empty");
        }
        // Check if item order is numeric
        if(!is_numeric($_POST['item_order'])) {
            throw new Exception("Item Order must be a number");
        }

        $statement = $pdo->prepare("INSERT INTO modules (name,course_id,total_video,total_resource,total_video_second,item_order) VALUES (?,?,?,?,?,?)");
        $statement->execute([$_POST['name'],$_REQUEST['id'],0,0,0,$_POST['item_order']]);

        $success_message = 'Module is added successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-curriculum/'.$_REQUEST['id']);
        exit;
    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-curriculum/'.$_REQUEST['id']);
        exit;
    }
}
?>


<?php
if(isset($_POST['form_module_update'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        if($_POST['item_order'] == '') {
            throw new Exception("Item Order can not be empty");
        }
        // Check if item order is numeric
        if(!is_numeric($_POST['item_order'])) {
            throw new Exception("Item Order must be a number");
        }

        $statement = $pdo->prepare("UPDATE modules SET name=?,item_order=? WHERE id=?");
        $statement->execute([$_POST['name'],$_POST['item_order'],$_POST['module_id']]);

        $success_message = 'Module is updated successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-curriculum/'.$_POST['course_id']);
        exit;
    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-curriculum/'.$_POST['course_id']);
        exit;
    }
}
?>


<?php
if(isset($_POST['form_module_delete'])) {
    try {

        // Get data from lessons table 
        $statement = $pdo->prepare("SELECT * FROM lessons WHERE module_id=?");
        $statement->execute([$_POST['module_id']]);
        $all_lessons = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($all_lessons as $single_lesson) {
            if($single_lesson['lesson_type'] == 'video') {
                if($single_lesson['video_type'] == 'mp4') {
                    unlink('uploads/'.$single_lesson['video_content']);
                }
            } else {
                unlink('uploads/'.$single_lesson['resource_content']);
            }
        }

        // Delete data from lessons table
        $statement = $pdo->prepare("DELETE FROM lessons WHERE module_id=?");
        $statement->execute([$_POST['module_id']]);

        // Get data from modules table 
        $statement = $pdo->prepare("SELECT * FROM modules WHERE id=?");
        $statement->execute([$_POST['module_id']]);
        $single_module = $statement->fetch(PDO::FETCH_ASSOC);

        // Get data from courses table
        $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
        $statement->execute([$single_module['course_id']]);
        $single_course = $statement->fetch(PDO::FETCH_ASSOC);
        $new_total_video = $single_course['total_video'] - $single_module['total_video'];
        $new_total_resource = $single_course['total_resource'] - $single_module['total_resource'];
        $new_total_video_second = $single_course['total_video_second'] - $single_module['total_video_second'];

        // Update the courses table
        $statement = $pdo->prepare("UPDATE courses SET total_video=?,total_resource=?,total_video_second=? WHERE id=?");
        $statement->execute([$new_total_video,$new_total_resource,$new_total_video_second,$single_module['course_id']]);

        // Delete data from modules table
        $statement = $pdo->prepare("DELETE FROM modules WHERE id=?");
        $statement->execute([$_POST['module_id']]);

        $success_message = 'Module is deleted successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-curriculum/'.$_POST['course_id']);
        exit;
    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-curriculum/'.$_POST['course_id']);
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
                <h2>Edit Course Curriculum</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit Course Curriculum</li>
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
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-video/<?php echo $_REQUEST['id']; ?>">Featured Video</a></li>
                    <li class="active"><a href="<?php echo BASE_URL; ?>instructor-course-edit-curriculum/<?php echo $_REQUEST['id']; ?>">Curriculum</a></li>
                </ul>

                <div class="module-top">
                    <h4>Modules</h4>
                    <div class="right">
                        <a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModule"><i class="fas fa-plus"></i> Add Module</a>
                    </div>
                </div>
                <!-- addModule Modal -->
                <div class="modal fade" id="addModule" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Module</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="total_video" class="form-label">Order</label>
                                        <input type="text" class="form-control" name="item_order">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary" name="form_module_add">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- // addModule Modal -->

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Total Video</th>
                            <th>Total Resource</th>
                            <th>Total Hour</th>
                            <th>Order</th>
                            <th>Lesson</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        $i=0;
                        $statement = $pdo->prepare("SELECT * FROM modules WHERE course_id=? ORDER BY item_order ASC");
                        $statement->execute([$_REQUEST['id']]);
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        $total = $statement->rowCount();
                        if($total==0) {
                            ?>
                            <tr>
                                <td colspan="6" class="text-danger">No data found</td>
                            </tr>
                            <?php
                        } else {
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['total_video']; ?></td>
                                    <td><?php echo $row['total_resource']; ?></td>
                                    <td><?php echo convert_seconds_to_minutes_hours($row['total_video_second']); ?></td>
                                    <td><?php echo $row['item_order']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>instructor-course-edit-curriculum-lesson/<?php echo $_REQUEST['id']; ?>/<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Lesson</a>
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModule<?php echo $i; ?>">Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModule<?php echo $i; ?>">Delete</a>
                                    </td>
                                </tr>
                                <!-- editModule Modal -->
                                <div class="modal fade" id="editModule<?php echo $i; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Module</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <input type="hidden" name="module_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="total_video" class="form-label">Order</label>
                                                        <input type="text" class="form-control" name="item_order" value="<?php echo $row['item_order']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <button type="submit" class="btn btn-primary" name="form_module_update">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- // editModule Modal -->

                                <!-- deleteModule Modal -->
                                <div class="modal fade" id="deleteModule<?php echo $i; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Module</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <input type="hidden" name="module_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="total_video" class="form-label">
                                                            Are you sure want to delete this module?
                                                        </label>
                                                    </div>
                                                    <div class="mb-3">
                                                        <button type="submit" class="btn btn-primary" name="form_module_delete">Yes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- // deleteModule Modal -->
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>

                
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>