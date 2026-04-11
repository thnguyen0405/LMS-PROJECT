<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Reviews</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Reviews</li>
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
                                <th>My Review</th>
                                <th>Comment</th>
                                <th class="w-100">Action</th>
                            </tr>
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT t1.*,
                                                    t2.featured_photo as course_featured_photo,
                                                    t2.title as course_title,
                                                    t2.slug as course_slug
                                                    FROM reviews t1
                                                    JOIN courses t2
                                                    ON t1.course_id = t2.id
                                                    WHERE t1.student_id=?");
                            $statement->execute([$_SESSION['student']['id']]);
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
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
                                        <div class="rating">
                                            <?php
                                            if($row['rating'] == 5) {
                                                ?>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <?php
                                            } elseif($row['rating'] == 4) {
                                                ?>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <?php
                                            } elseif($row['rating'] == 3) {
                                                ?>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <?php
                                            } elseif($row['rating'] == 2) {
                                                ?>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <?php
                                            } elseif($row['rating'] == 1) {
                                                ?>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                            ?>
                                            <?php
                                        }
                                        ?>  
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo nl2br($row['comment']); ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>course/<?php echo $row['course_slug']; ?>" class="btn btn-primary btn-sm">See Course</a>
                                    </td>
                                </tr>
                                <?php
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