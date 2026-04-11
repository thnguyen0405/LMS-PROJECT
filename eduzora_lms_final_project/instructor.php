<?php include "header.php"; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM instructors WHERE id=? AND status=?");
$statement->execute([$_REQUEST['id'], 1]);
$instructor = $statement->fetchAll(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if(!$total) {
    header("location: ".BASE_URL."instructors");
    exit;
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo $instructor[0]['name']; ?></h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>instructors">Instructors</a></li>
                        <li class="breadcrumb-item active"><?php echo $instructor[0]['name']; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="instructor-single pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="photo">
                    <?php if($instructor[0]['photo']!=''): ?>
                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $instructor[0]['photo']; ?>" alt="">
                    <?php else: ?>
                        <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-9">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td><?php echo $instructor[0]['name']; ?></td>
                        </tr>
                        <tr>
                            <th>Designation</th>
                            <td><?php echo $instructor[0]['designation']; ?></td>
                        </tr>
                        <tr>
                            <th>Rating</th>
                            <td>
                                <div class="rating">
                                    <i class="fas fa-star"></i> <?php echo $instructor[0]['average_rating']; ?> (<?php echo $instructor[0]['total_rating']; ?> Ratings)
                                </div>
                            </td>
                        </tr>
                        <?php if($instructor[0]['address']!=''): ?>
                        <tr>
                            <th>Address</th>
                            <td><?php echo $instructor[0]['address']; ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Email Address</th>
                            <td><?php echo $instructor[0]['email']; ?></td>
                        </tr>
                        <?php if($instructor[0]['phone']!=''): ?>
                        <tr>
                            <th>Phone</th>
                            <td><?php echo $instructor[0]['phone']; ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($instructor[0]['website']!=''): ?>
                        <tr>
                            <th>Website</th>
                            <td><?php echo $instructor[0]['website']; ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($instructor[0]['facebook']!='' || $instructor[0]['twitter']!='' || $instructor[0]['linkedin']!='' || $instructor[0]['instagram']!=''): ?>
                        <tr>
                            <th>Social Media</th>
                            <td>
                                <ul>
                                    <?php if($instructor[0]['facebook']!=''): ?>
                                    <li><a href="<?php echo $instructor[0]['facebook']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                    <?php endif; ?>
                                    <?php if($instructor[0]['twitter']!=''): ?>
                                    <li><a href="<?php echo $instructor[0]['twitter']; ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                    <?php endif; ?>
                                    <?php if($instructor[0]['linkedin']!=''): ?>
                                    <li><a href="<?php echo $instructor[0]['linkedin']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                                    <?php endif; ?>
                                    <?php if($instructor[0]['instagram']!=''): ?>
                                    <li><a href="<?php echo $instructor[0]['instagram']; ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                    <?php endif; ?>
                                </ul>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt_30">
                <h4>Biography</h4>
                <div class="description">
                    <p>
                        <?php echo nl2br($instructor[0]['biography']); ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt_30">
                <h4>Courses (<?php echo $instructor[0]['total_course']; ?>)</h4>
            </div>
        </div>

        <div class="row course">
        <?php
            $statement = $pdo->prepare("SELECT 
                                t1.*,
                                t2.id as category_id,
                                t2.name as category_name,
                                t3.name as instructor_name,
                                t3.id as instructor_id
                                FROM courses t1
                                JOIN categories t2
                                ON t1.category_id = t2.id
                                JOIN instructors t3
                                ON t1.instructor_id = t3.id
                                WHERE t1.status=? AND t1.instructor_id=?
                                LIMIT 10");
            $statement->execute(['Active',$_REQUEST['id']]);
            $total = $statement->rowCount();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            if(!$total) {
                echo "<p class='text-danger'>No course found</p>";
            }
            foreach ($result as $row) {
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="item pb_25">
                        <div class="photo">
                            <a href="<?php echo BASE_URL; ?>course/<?php echo $row['slug']; ?>"><img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['featured_photo']; ?>" alt=""></a>
                            <div class="wishlist">
                                <a href="<?php echo BASE_URL; ?>student-wishlist-add.php?id=<?php echo $row['id']; ?>"><i class="far fa-heart"></i></a>
                            </div>
                        </div>
                        <div class="text">
                            <h2>
                                <a href="<?php echo BASE_URL; ?>course/<?php echo $row['slug']; ?>"><?php echo $row['title']; ?></a>
                            </h2>
                            <div class="category">
                                <span class="badge bg-primary"><a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=<?php echo $row['category_id']; ?>&review=&level="><?php echo $row['category_name']; ?></a></span>
                            </div>
                            <div class="rating">
                                <div class="left">
                                    <i class="fas fa-star"></i> <?php echo $row['average_rating']; ?> (<?php echo $row['total_rating']; ?> Ratings)
                                </div>
                                <div class="student">
                                    <i class="fas fa-user"></i> <?php echo $row['total_student']; ?> Persons
                                </div>
                            </div>
                            <div class="ins">
                                By: <a href="<?php echo BASE_URL; ?>instructor/<?php echo $row['instructor_id']; ?>"><?php echo $row['instructor_name']; ?></a>
                            </div>
                            <div class="bottom">
                                <div class="price">
                                    $<?php echo $row['price']; ?>
                                    <?php if($row['price_old']!=''): ?>
                                        <del>$<?php echo $row['price_old']; ?></del>
                                    <?php endif; ?>
                                </div>
                                <div class="cart-add">
                                    <form action="<?php echo BASE_URL; ?>cart-add.php" method="post">
                                        <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-primary btn-sm" name="form_cart_add">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            

        </div>
    </div>
</div>

<?php include "footer.php"; ?>