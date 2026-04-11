
<?php include "header.php"; ?>

<div class="slider">
    <div class="">
        <div class="item" style="background-image:url(<?php echo BASE_URL; ?>uploads/slide-1.jpg);">
            <div class="bg"></div>
            <div class="text">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text-wrapper">
                                <div class="text-content">
                                    <h2>Find The Best Website For Courses</h2>
                                    <p>
                                        We have hosted around 2,000 courses worldwide and helped people to learn and grow. Currently, we are teaching around 15,000 people. So we hope you would enjoy the course.
                                    </p>
                                    <div class="button-style-1 mt_20">
                                        <a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=&review=&level=">Explore Courses</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="course-category pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <div class="left">
                        <h2>Course Categories</h2>
                        <p>
                            Pick your favorite course from our hand picked categories.
                        </p>
                    </div>
                    <div class="right">
                        <a href="<?php echo BASE_URL; ?>course-category">View All <i class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="course-category-carousel owl-carousel">
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC LIMIT 10");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <div class="item pb_25 mr_10 ml_10">
                            <div class="icon">
                                <a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=<?php echo $row['id']; ?>&review=&level=">
                                    <i class="<?php echo $row['icon']; ?>"></i>
                                </a>
                            </div>
                            <div class="text">
                                <h2>
                                    <a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=<?php echo $row['id']; ?>&review=&level="><?php echo $row['name']; ?></a>
                                </h2>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="why-choose pt_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="inner pb_70">
                    <div class="icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="text">
                        <h2>High Quality Courses</h2>
                        <p>
                            Our courses are designed to help you develop the skills you need to succeed in your academic and professional life. 
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="inner pb_70">
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="text">
                        <h2>Life Time Access</h2>
                        <p>
                            If you purchase any of our courses, you will have lifetime access to the course material. You can learn the material whenever you need.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="inner pb_70">
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="text">
                        <h2>Expert Instructors</h2>
                        <p>
                            Learn from industry experts who are passionate about teaching. Learn anywhere, anytime, on any device.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="course pt_70 pb_40">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <div class="left">
                        <h2>Popular Courses</h2>
                        <p>
                            Check out our most popular courses and start learning today
                        </p>
                    </div>
                    <div class="right">
                        <a href="courses.php">View All <i class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="course-carousel owl-carousel">
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
                                        WHERE t1.status=?
                                        LIMIT 10");
                    $statement->execute(['Active']);
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
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
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="instructor pt_20 pb_40">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <div class="left">
                        <h2>Featured Instructors</h2>
                        <p>
                            Learn from the best instructors in the world
                        </p>
                    </div>
                    <div class="right">
                        <a href="<?php echo BASE_URL; ?>instructors">View All <i class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <?php
            $statement = $pdo->prepare("SELECT * FROM instructors WHERE status=? ORDER BY average_rating DESC LIMIT 4");
            $statement->execute([1]);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="item pb_50">
                        <div class="photo">
                            <?php if($row['photo'] != ''): ?>
                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['photo']; ?>" alt="">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="">
                            <?php endif; ?>
                        </div>
                        <div class="text">
                            <h2><a href="<?php echo BASE_URL; ?>instructor/<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></h2>
                            <div class="designation"><?php echo $row['designation']; ?></div>
                            <div class="rating">
                                <i class="fas fa-star"></i> <?php echo $row['average_rating']; ?> (<?php echo $row['total_rating']; ?> Ratings)
                            </div>

                            <?php if($row['facebook'] != '' || $row['twitter'] != '' || $row['linkedin'] != '' || $row['instagram'] != ''): ?>
                            <div class="social">
                                <ul>
                                    <?php if($row['facebook'] != ''): ?>
                                    <li><a href="<?php echo $row['facebook']; ?>"><i class="fab fa-facebook-f"></i></a></li>
                                    <?php endif; ?>

                                    <?php if($row['twitter'] != ''): ?>
                                    <li><a href="<?php echo $row['twitter']; ?>"><i class="fab fa-twitter"></i></a></li>
                                    <?php endif; ?>

                                    <?php if($row['linkedin'] != ''): ?>
                                    <li><a href="<?php echo $row['linkedin']; ?>"><i class="fab fa-linkedin-in"></i></a></li>
                                    <?php endif; ?>

                                    <?php if($row['instagram'] != ''): ?>
                                    <li><a href="<?php echo $row['instagram']; ?>"><i class="fab fa-instagram"></i></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>


<div class="testimonial pt_20 pb_40">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <div class="left">
                        <h2>Client Testimonials</h2>
                        <p>
                            See what our clients have to say about their experience with us
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="testimonial-carousel owl-carousel">
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM testimonials ORDER BY id ASC");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <div class="item">
                            <div class="photo">
                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['photo']; ?>" alt="" />
                            </div>
                            <div class="text">
                                <h4><?php echo $row['name']; ?></h4>
                                <p><?php echo $row['designation']; ?></p>
                            </div>
                            <div class="description">
                                <p>
                                    <?php echo nl2br($row['comment']); ?> 
                                </p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="blog pt_40">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <div class="left">
                        <h2>Latest News</h2>
                        <p>
                            Check out the latest news and updates from our blog post.
                        </p>
                    </div>
                    <div class="right">
                        <a href="<?php echo BASE_URL; ?>blog">View All <i class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $statement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT 4");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="item pb_70">
                        <div class="photo">
                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $row['photo']; ?>" alt="" />
                        </div>
                        <div class="text">
                            <h2>
                                <a href="<?php echo BASE_URL; ?>post/<?php echo $row['slug']; ?>"><?php echo $row['title']; ?></a>
                            </h2>
                            <div class="short-des">
                                <p>
                                    <?php echo nl2br($row['short_description']); ?>
                                </p>
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