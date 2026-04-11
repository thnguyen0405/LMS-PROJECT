<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM testimonials WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".ADMIN_URL."testimonial-view.php");
    exit;
}
?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        if($_POST['designation'] == '') {
            throw new Exception("Designation can not be empty");
        }
        if($_POST['comment'] == '') {
            throw new Exception("Comment can not be empty");
        }

        $path = $_FILES['photo']['name'];
        $path_tmp = $_FILES['photo']['tmp_name'];
        if($path == '') {
            $filename = $_POST['current_photo'];
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "testimonial_".time().".".$extension;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);
            if($mime != 'image/jpeg' && $mime != 'image/png' && $mime != 'image/gif') {
                throw new Exception("Please upload a valid photo");
            }
        }

        if($path != '') {
            unlink('../uploads/'.$_POST['current_photo']);
            move_uploaded_file($path_tmp, '../uploads/'.$filename);
        }

        $statement = $pdo->prepare("UPDATE testimonials SET 
                            photo=?,
                            name=?,
                            designation=?,
                            comment=?
                            WHERE id=?");
        $statement->execute([
            $filename,
            $_POST['name'],
            $_POST['designation'],
            $_POST['comment'],
            $_REQUEST['id']
        ]);

        $success_message = "Data is updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."testimonial-view.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."testimonial-edit.php?id=".$_REQUEST['id']);
        exit;
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM testimonials WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$testimonial = $statement->fetch(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Edit Testimonial</h1>
            <div>
                <a href="<?php echo ADMIN_URL; ?>testimonial-view.php" class="btn btn-primary"><i class="fas fa-plus"></i> View All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="current_photo" value="<?php echo $testimonial['photo']; ?>">
                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $testimonial['photo']; ?>" alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Change Photo</label>
                                    <div>
                                        <input type="file" name="photo">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Name *</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo $testimonial['name']; ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Designation *</label>
                                    <input type="text" class="form-control" name="designation" value="<?php echo $testimonial['designation']; ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Comment *</label>
                                    <textarea name="comment" class="form-control h_150"><?php echo $testimonial['comment']; ?></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary" name="form_submit">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>