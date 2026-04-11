<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
if(isset($_POST['form_delete'])) { 
    $statement = $pdo->prepare("DELETE FROM wishlists WHERE id=?");
    $statement->execute([$_POST['id']]);
    $_SESSION['success_message'] = "Course has been removed from wishlist";
    header('location: '.BASE_URL.'student-wishlist');
    exit;
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Wishlist</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Wishlist</li>
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
                    <?php include "student-sidebar.php"; ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>SL</th>
                                <th>Photo</th>
                                <th>Course</th>
                                <th>Price</th>
                                <th class="w-100">Action</th>
                            </tr>
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT t1.*, 
                                                t2.title as course_title,
                                                t2.featured_photo as course_featured_photo,
                                                t2.price as course_price,
                                                t2.slug as course_slug
                                                FROM wishlists t1
                                                JOIN courses t2
                                                ON t1.course_id = t2.id
                                                WHERE student_id=?");
                            $statement->execute([$_SESSION['student']['id']]);
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            $total = $statement->rowCount();
                            if(!$total) {
                                echo "<tr><td colspan='5' class='text-danger'>No course found in wishlist</td></tr>";
                            }
                            else {
                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td>
                                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['course_featured_photo']; ?>" alt="" class="w-200">
                                        </td>
                                        <td>
                                            <?php echo $row['course_title']; ?>
                                        </td>
                                        <td>
                                            $<?php echo $row['course_price']; ?>
                                        </td>
                                        <td>
                                            <form action="" method="post">
                                                <a href="<?php echo BASE_URL; ?>course/<?php echo $row['course_slug']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" name="form_delete" onClick="return confirm('Are You Sure?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>