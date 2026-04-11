<?php include 'layouts/top.php'; ?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['title'] == '') {
            throw new Exception("Title can not be empty");
        }
        if($_POST['slug'] == '') {
            throw new Exception("Slug can not be empty");
        }
        $statement = $pdo->prepare("SELECT * FROM posts WHERE slug=?");
        $statement->execute([$_POST['slug']]);
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
            throw new Exception("Photo can not be empty");
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "post_".time().".".$extension;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);
            if($mime != 'image/jpeg' && $mime != 'image/png' && $mime != 'image/gif') {
                throw new Exception("Please upload a valid photo");
            }
        }

        move_uploaded_file($path_tmp, '../uploads/'.$filename);

        $statement = $pdo->prepare("INSERT INTO posts (title,slug,short_description,description,photo,post_date) VALUES (?,?,?,?,?,?)");
        $statement->execute([$_POST['title'],$_POST['slug'],$_POST['short_description'],$_POST['description'],$filename,date('Y-m-d')]);

        $success_message = "Data is inserted successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."post-view.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."post-add.php");
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Add Post</h1>
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
                                <div class="form-group mb-3">
                                    <label>Photo *</label>
                                    <div>
                                        <input type="file" name="photo">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Title *</label>
                                    <input type="text" class="form-control" name="title">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Slug *</label>
                                    <input type="text" class="form-control" name="slug">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Short Description *</label>
                                    <textarea name="short_description" class="form-control h_150"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description *</label>
                                    <textarea name="description" class="form-control editor"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form_submit">Submit</button>
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