<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['instructor'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM modules WHERE id=? AND course_id=?");
$statement->execute([$_REQUEST['module_id'],$_REQUEST['course_id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".BASE_URL."instructor-courses.php");
    exit;
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM courses WHERE id=? AND status=?");
$statement->execute([$_REQUEST['course_id'],'In Review']);
$total = $statement->rowCount();
if($total) {
    header("location: ".BASE_URL."instructor-courses.php");
    exit;
}
?>

<?php
if(isset($_POST['form_lesson_add'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        if($_POST['lesson_type'] == '') {
            throw new Exception("Lesson Type can not be empty");
        }
        if($_POST['lesson_type'] == 'video') {
            if($_POST['video_type'] == '') {
                throw new Exception("Video Type can not be empty");
            }
            if($_POST['video_type'] == 'youtube') {
                if($_POST['video_content_youtube'] == '') {
                    throw new Exception("Video Content can not be empty");
                }
            }
            if($_POST['video_type'] == 'vimeo') {
                if($_POST['video_content_vimeo'] == '') {
                    throw new Exception("Video Content can not be empty");
                }
            }
            if($_POST['video_type'] == 'mp4') {
                $path_mp4 = $_FILES['video_content_mp4']['name'];
                $path_tmp_mp4 = $_FILES['video_content_mp4']['tmp_name'];
                if($path_mp4=='') {
                    throw new Exception("Please choose a video");
                }
                $extension_mp4 = pathinfo($path_mp4, PATHINFO_EXTENSION);
                $filename_mp4 = "lesson_video_".time().".".$extension_mp4;
                $finfo_mp4 = finfo_open(FILEINFO_MIME_TYPE);
                $mime_mp4 = finfo_file($finfo_mp4, $path_tmp_mp4);
                if($mime_mp4 != 'video/mp4') {
                    throw new Exception("Please upload a valid video. Only mp4 is allowed");
                }
            }
            if($_POST['duration_second'] == '') {
                throw new Exception("Duration can not be empty");
            }
        } else {
            $path = $_FILES['resource_content']['name'];
            $path_tmp = $_FILES['resource_content']['tmp_name'];
            if($path=='') {
                throw new Exception("Please choose a video");
            }
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "lesson_resource_".time().".".$extension;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);
            // check mime for pdf, doc, docx, txt and zip
            if($mime != 'application/pdf' && $mime != 'application/msword' && $mime != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' && $mime != 'text/plain' && $mime != 'application/zip') {
                throw new Exception("Please upload a valid file. Only pdf, doc, docx, txt and zip is allowed");
            }
        }
        if($_POST['item_order'] == '') {
            throw new Exception("Item Order can not be empty");
        }
        // Check if item order is numeric
        if(!is_numeric($_POST['item_order'])) {
            throw new Exception("Item Order must be a number");
        }

        if($_POST['lesson_type'] == 'video') {
            if($_POST['video_type'] == 'youtube') {
                $video_content = $_POST['video_content_youtube'];
            } else if($_POST['video_type'] == 'vimeo') {
                $video_content = $_POST['video_content_vimeo'];
            } else if($_POST['video_type'] == 'mp4') {
                $video_content = $filename_mp4;
                move_uploaded_file($path_tmp_mp4, 'uploads/'.$filename_mp4);
            }
            $video_type = $_POST['video_type'];
            $duration_second = convert_duration_to_seconds($_POST['duration_second']);
            $resource_content = '';
        } else {
            $video_type = '';
            $video_content = '';
            $duration_second = 0;
            $resource_content = $filename;
            move_uploaded_file($path_tmp, 'uploads/'.$filename);
        }

        $statement = $pdo->prepare("INSERT INTO lessons (course_id,module_id,name,lesson_type,video_type,video_content,duration_second,resource_content,is_preview,item_order) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $statement->execute([$_POST['course_id'],$_POST['module_id'],$_POST['name'],$_POST['lesson_type'],$video_type,$video_content,$duration_second,$resource_content,$_POST['is_preview'],$_POST['item_order']]);


        // Update modules table
        $statement = $pdo->prepare("SELECT * FROM modules WHERE id=?");
        $statement->execute([$_POST['module_id']]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($_POST['lesson_type'] == 'video') {
            $new_total_video = $result[0]['total_video']+1;
            $new_total_video_second = $result[0]['total_video_second'] + $duration_second;
            $statement = $pdo->prepare("UPDATE modules SET total_video=?, total_video_second=? WHERE id=?");
            $statement->execute([$new_total_video,$new_total_video_second,$_POST['module_id']]);
        } else {
            $new_total_resource = $result[0]['total_resource']+1;
            $statement = $pdo->prepare("UPDATE modules SET total_resource=? WHERE id=?");
            $statement->execute([$new_total_resource,$_POST['module_id']]);
        }
        
        // Update courses table
        $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
        $statement->execute([$_POST['course_id']]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($_POST['lesson_type'] == 'video') {
            $new_total_video = $result[0]['total_video']+1;
            $new_total_video_second = $result[0]['total_video_second'] + $duration_second;
            $statement = $pdo->prepare("UPDATE courses SET total_video=?, total_video_second=? WHERE id=?");
            $statement->execute([$new_total_video,$new_total_video_second,$_POST['course_id']]);
        } else {
            $new_total_resource = $result[0]['total_resource']+1;
            $statement = $pdo->prepare("UPDATE courses SET total_resource=? WHERE id=?");
            $statement->execute([$new_total_resource,$_POST['course_id']]);
        }

        $success_message = 'Lesson is added successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-curriculum-lesson/'.$_POST['course_id'].'/'.$_POST['module_id']);
        exit;
    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-curriculum-lesson/'.$_POST['course_id'].'/'.$_POST['module_id']);
        exit;
    }
}
?>

<?php
if(isset($_POST['form_lesson_update'])) {
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

        $statement = $pdo->prepare("UPDATE lessons SET name=?,is_preview=?,item_order=? WHERE id=?");
        $statement->execute([$_POST['name'],$_POST['is_preview'],$_POST['item_order'],$_POST['lesson_id']]);

        $success_message = 'Lesson is updated successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-curriculum-lesson/'.$_POST['course_id'].'/'.$_POST['module_id']);
        exit;
    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-curriculum-lesson/'.$_POST['course_id'].'/'.$_POST['module_id']);
        exit;
    }
}
?>

<?php
if(isset($_POST['form_lesson_delete'])) {
    try {

        $statement = $pdo->prepare("SELECT * FROM lessons WHERE id=?");
        $statement->execute([$_POST['lesson_id']]);
        $lesson_data = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($lesson_data[0]['lesson_type'] == 'video') {
            if($lesson_data[0]['video_type'] == 'mp4') {
                unlink('uploads/'.$lesson_data[0]['video_content']);
            }
        } else {
            unlink('uploads/'.$lesson_data[0]['resource_content']);
        }


        // Update modules table
        $statement = $pdo->prepare("SELECT * FROM modules WHERE id=?");
        $statement->execute([$_POST['module_id']]);
        $module_data = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($lesson_data[0]['lesson_type'] == 'video') {
            $new_total_video1 = $module_data[0]['total_video'] - 1;
            $new_total_video1_second = $module_data[0]['total_video_second'] - $lesson_data[0]['duration_second'];
            // Update
            $statement = $pdo->prepare("UPDATE modules SET total_video=?,total_video_second=? WHERE id=?");
            $statement->execute([$new_total_video1,$new_total_video1_second,$_POST['module_id']]);
        }
        if($lesson_data[0]['lesson_type'] == 'resource') {
            $new_total_resource1 = $module_data[0]['total_resource'] - 1;
            // Update
            $statement = $pdo->prepare("UPDATE modules SET total_resource=? WHERE id=?");
            $statement->execute([$new_total_resource1,$_POST['module_id']]);
        }


        // Update courses table
        $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
        $statement->execute([$_POST['course_id']]);
        $course_data = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($lesson_data[0]['lesson_type'] == 'video') {
            $new_total_video2 = $course_data[0]['total_video'] - 1;
            $new_total_video2_second = $course_data[0]['total_video_second'] - $lesson_data[0]['duration_second'];
            // Update
            $statement = $pdo->prepare("UPDATE courses SET total_video=?,total_video_second=? WHERE id=?");
            $statement->execute([$new_total_video2,$new_total_video2_second,$_POST['course_id']]);
        }
        if($lesson_data[0]['lesson_type'] == 'resource') {
            $new_total_resource2 = $course_data[0]['total_resource'] - 1;
            // Update
            $statement = $pdo->prepare("UPDATE courses SET total_resource=? WHERE id=?");
            $statement->execute([$new_total_resource2,$_POST['course_id']]);
        }
        

        $statement = $pdo->prepare("DELETE FROM lessons WHERE id=?");
        $statement->execute([$_POST['lesson_id']]);

        $success_message = 'Lesson is deleted successfully';
        $_SESSION['success_message'] = $success_message;

        header('location: '.BASE_URL.'instructor-course-edit-curriculum-lesson/'.$_POST['course_id'].'/'.$_POST['module_id']);
        exit;
    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header('location: '.BASE_URL.'instructor-course-edit-curriculum-lesson/'.$_POST['course_id'].'/'.$_POST['module_id']);
        exit;
    }
}
?>


<?php
$statement = $pdo->prepare("SELECT * FROM modules WHERE id=?");
$statement->execute([$_REQUEST['module_id']]);
$module_data = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Course Curriculum (Lesson)</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit Course Curriculum (Lesson)</li>
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
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-basic/<?php echo $_REQUEST['course_id']; ?>">Basic Information</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-photo/<?php echo $_REQUEST['course_id']; ?>">Featured Photo</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-banner/<?php echo $_REQUEST['course_id']; ?>">Featured Banner</a></li>
                    <li><a href="<?php echo BASE_URL; ?>instructor-course-edit-featured-video/<?php echo $_REQUEST['course_id']; ?>">Featured Video</a></li>
                    <li class="active"><a href="<?php echo BASE_URL; ?>instructor-course-edit-curriculum/<?php echo $_REQUEST['course_id']; ?>">Curriculum</a></li>
                </ul>

                <div class="module-top">
                    <h4>Module: <?php echo $module_data[0]['name']; ?></h4>
                    <div class="right">
                        <a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addLesson"><i class="fas fa-plus"></i> Add Lesson</a>
                    </div>
                </div>
                <!-- addLesson Modal -->
                <div class="modal fade" id="addLesson" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Lesson</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="module_id" value="<?php echo $_REQUEST['module_id']; ?>">
                                    <input type="hidden" name="course_id" value="<?php echo $_REQUEST['course_id']; ?>">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Lesson Type</label>
                                        <select name="lesson_type" class="form-select" id="lesson_type">
                                            <option value="">Select</option>
                                            <option value="video">Video</option>
                                            <option value="resource">Resource</option>
                                        </select>
                                    </div>
                                    <div class="mb-3" id="video" style="display:none;">
                                        <label for="" class="form-label">Video Type</label>
                                        <select name="video_type" class="form-select" id="video_type">
                                            <option value="">Select</option>
                                            <option value="youtube">Youtube</option>
                                            <option value="vimeo">Vimeo</option>
                                            <option value="mp4">MP4</option>
                                        </select>
                                    </div>
                                    <div class="mb-3" id="youtube" style="display:none;">
                                        <label for="">Video Content (YouTube) *</label>
                                        <div class="form-group">
                                            <input type="text" name="video_content_youtube" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="mb-3" id="vimeo" style="display:none;">
                                        <label for="">Video Content (Vimeo) *</label>
                                        <div class="form-group">
                                            <input type="text" name="video_content_vimeo" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="mb-3" id="mp4" style="display:none;">
                                        <label for="">Video Content (MP4) *</label>
                                        <div class="form-group">
                                            <input type="file" name="video_content_mp4">
                                        </div>
                                    </div>
                                    <div class="mb-3" id="duration" style="display:none;">
                                        <label for="">Duration (Second) *</label>
                                        <div class="form-group">
                                            <input type="text" name="duration_second" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="mb-3" id="resource" style="display:none;">
                                        <label for="">Resource *</label>
                                        <div class="form-group">
                                            <input type="file" name="resource_content">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Is Preview?</label>
                                        <select name="is_preview" class="form-select">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Order</label>
                                        <input type="text" class="form-control" name="item_order">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary" name="form_lesson_add">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- // addLesson Modal -->

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Lesson Type</th>
                            <th>Video Type</th>
                            <th>Is Preview?</th>
                            <th>Order</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        $i=0;
                        $statement = $pdo->prepare("SELECT * FROM lessons WHERE course_id=? AND module_id=? ORDER BY item_order ASC");
                        $statement->execute([$_REQUEST['course_id'],$_REQUEST['module_id']]);
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        $total = $statement->rowCount();
                        if($total==0) {
                            ?>
                            <tr>
                                <td colspan="7" class="text-danger">No data found</td>
                            </tr>
                            <?php
                        } else {
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['lesson_type']; ?></td>
                                    <td><?php echo $row['video_type']; ?></td>
                                    <td>
                                        <?php
                                        if($row['is_preview'] == 1) {
                                            echo '<span class="badge bg-success">Yes</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">No</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['item_order']; ?></td>
                                    <td>
                                        <a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editLesson<?php echo $i; ?>">Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteLesson<?php echo $i; ?>">Delete</a>
                                    </td>
                                </tr>
                                <!-- editLesson Modal -->
                                <div class="modal fade" id="editLesson<?php echo $i; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Lesson</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <input type="hidden" name="lesson_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="module_id" value="<?php echo $row['module_id']; ?>">
                                                    <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Is Preview?</label>
                                                        <select name="is_preview" class="form-select">
                                                            <option value="0" <?php if($row['is_preview']==0) {echo 'selected';} ?>>No</option>
                                                            <option value="1" <?php if($row['is_preview']==1) {echo 'selected';} ?>>Yes</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Order</label>
                                                        <input type="text" class="form-control" name="item_order" value="<?php echo $row['item_order']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <button type="submit" class="btn btn-primary" name="form_lesson_update">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- // editLesson Modal -->

                                <!-- deleteLesson Modal -->
                                <div class="modal fade" id="deleteLesson<?php echo $i; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Lesson</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <input type="hidden" name="lesson_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="module_id" value="<?php echo $row['module_id']; ?>">
                                                    <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="total_video" class="form-label">
                                                            Are you sure want to delete this lesson?
                                                        </label>
                                                    </div>
                                                    <div class="mb-3">
                                                        <button type="submit" class="btn btn-primary" name="form_lesson_delete">Yes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- // deleteLesson Modal -->
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

<script>
    $(document).ready(function(){
        $('#lesson_type').on('change',function(){
            $('#video, #resource').hide();
            var selectedValue = $(this).val();
            if (selectedValue == 'video') {
                $('#video').show();
                $('#resource').hide();
                $('#youtube').hide();
                $('#vimeo').hide();
                $('#mp4').hide();
                $('#duration').hide();
                $('#video_type').val('');
            } else if (selectedValue == 'resource') {
                $('#resource').show();
                $('#video').hide();
                $('#youtube').hide();
                $('#vimeo').hide();
                $('#mp4').hide();
                $('#duration').hide();
            } else if (selectedValue == '') {
                $('#resource').hide();
                $('#video').hide();
                $('#youtube').hide();
                $('#vimeo').hide();
                $('#mp4').hide();
                $('#duration').hide();
            }
        });

        $('#video_type').on('change',function(){
            $('#youtube, #vimeo, #mp4, #duration').hide();
            var selectedValue = $(this).val();
            if (selectedValue == 'youtube') {
                $('#youtube').show();
                $('#duration').show();
            } else if (selectedValue == 'vimeo') {
                $('#vimeo').show();
                $('#duration').show();
            } else if (selectedValue == 'mp4') {
                $('#mp4').show();
                $('#duration').show();
            } else if (selectedValue == '') {
                $('#youtube').hide();
                $('#vimeo').hide();
                $('#mp4').hide();
                $('#duration').hide();
            }
        });
    });
</script>

<?php include "footer.php"; ?>