<?php include 'layouts/top.php'; ?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        // Duplicate name check
        $statement = $pdo->prepare("SELECT * FROM categories WHERE name=?");
        $statement->execute([$_POST['name']]);
        $total = $statement->rowCount();
        if($total) {
            throw new Exception("Name already exists.");
        }
        if($_POST['icon'] == '') {
            throw new Exception("Icon can not be empty");
        }

        $statement = $pdo->prepare("INSERT INTO categories (name,icon) VALUES (?,?)");
        $statement->execute([$_POST['name'],$_POST['icon']]);

        $success_message = "Data is inserted successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."category-view.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."category-add.php");
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Add Category</h1>
            <div>
                <a href="<?php echo ADMIN_URL; ?>category-view.php" class="btn btn-primary"><i class="fas fa-plus"></i> View All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Icon</label>
                                            <input type="text" class="form-control" name="icon">
                                        </div>
                                    </div>
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