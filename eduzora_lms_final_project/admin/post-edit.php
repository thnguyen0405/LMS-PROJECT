<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".ADMIN_URL."post-view.php");
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
        $statement = $pdo->prepare("SELECT * FROM posts WHERE slug=? AND id!=?");
        $statement->execute([$_POST['slug'],$_REQUEST['id']]);
        $total = $statement->rowCount();
        if($total) {
            throw new Exception("Slug already exists.");
        }
        if($_POST['short_description'] == '') {
            throw new Exception("Short Description can not be empty");
        }
        if($_POST['description'] == '') {
            throw new Exception("Description can not be empty");
        }

        $path = $_FILES['photo']['name'];
        $path_tmp = $_FILES['photo']['tmp_name'];
        if($path == '') {
            $filename = $_POST['current_photo'];
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "post_".time().".".$extension;
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

        $statement = $pdo->prepare("UPDATE posts SET 
                            photo=?,
                            title=?,
                            slug=?,
                            short_description=?,
                            description=?
                            WHERE id=?");
        $statement->execute([
            $filename,
            $_POST['title'],
            $_POST['slug'],
            $_POST['short_description'],
            $_POST['description'],
            $_REQUEST['id']
        ]);

        $success_message = "Data is updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."post-view.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."post-edit.php?id=".$_REQUEST['id']);
        exit;
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$post = $statement->fetch(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Edit Post</h1>
            <div>
                <a href="<?php echo ADMIN_URL; ?>post-view.php" class="btn btn-primary"><i class="fas fa-plus"></i> View All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="current_photo" value="<?php echo $post['photo']; ?>">
                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $post['photo']; ?>" alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Change Photo</label>
                                    <div>
                                        <input type="file" name="photo">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Title *</label>
                                    <input type="text" class="form-control" name="title" value="<?php echo $post['title']; ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Slug *</label>
                                    <input type="text" class="form-control" name="slug" value="<?php echo $post['slug']; ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Short Description *</label>
                                    <textarea name="short_description" class="form-control h_150"><?php echo $post['short_description']; ?></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description *</label>
                                    <textarea name="description" class="form-control editor"><?php echo $post['description']; ?></textarea>
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