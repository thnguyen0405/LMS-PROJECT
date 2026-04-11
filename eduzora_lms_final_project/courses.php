<?php include "header.php"; ?>

<?php
if(!empty($_GET['language'])) {
    if($_GET['language'] == '') {
        $c_language = '';
    } else {
        $c_language = ' AND language_id=' . $_GET['language'];
    }
} else {
    $c_language = '';
}

if(!empty($_GET['category'])) {
    if($_GET['category'] == '') {
        $c_category = '';
    } else {
        $c_category = ' AND category_id=' . $_GET['category'];
    }
} else {
    $c_category = '';
}

if(!empty($_GET['level'])) {
    if($_GET['level'] == '') {
        $c_level = '';
    } else {
        $c_level = ' AND level_id=' . $_GET['level'];
    }
} else {
    $c_level = '';
}

if(!empty($_GET['price'])) {
    if($_GET['price'] == '') {
        $c_price = '';
    } else {
        if($_GET['price'] == 'free') {
            $c_price = ' AND price=0';
        } else {
            $c_price = ' AND price!=0';
        }
    }
} else {
    $c_price = '';
}

if(!empty($_GET['review'])) {
    if($_GET['review'] == '') {
        $c_review = '';
    } else {
        $review = (int)$_GET['review'];
        if($review == 5) {
            $c_review = ' AND t1.average_rating IN (5, 4.5)';
        } elseif($review == 4) {
            $c_review = ' AND t1.average_rating IN (4, 3.5)';
        } elseif($review == 3) {
            $c_review = ' AND t1.average_rating IN (3, 2.5)';
        } elseif($review == 2) {
            $c_review = ' AND t1.average_rating IN (2, 1.5)';
        } elseif($review == 1) {
            $c_review = ' AND t1.average_rating = 1';
        }
    }
} else {
    $c_review = '';
}

if(!empty($_GET['title'])) {
    if($_GET['title'] == '') {
        $c_title = '';
    } else {
        $c_title = ' AND title LIKE "%' . $_GET['title'] . '%"';
    }
} else {
    $c_title = '';
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Courses</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="course pt_70 pb_50">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">

                <form action="" method="get">

                    <div class="course-sidebar">
                        <div class="widget">
                            <h2>Course Title</h2>
                            <div class="box">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="title" class="form-control" placeholder="Course Title ..." value="<?php if(isset($_GET['title'])) {echo $_GET['title'];} ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget">
                            <h2>Price</h2>
                            <div class="box">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price" id="priceRadios1" value="" checked>
                                    <label class="form-check-label" for="priceRadios1">
                                        All
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price" id="priceRadios2" value="free" <?php if(isset($_GET['price'])) {if($_GET['price'] == 'free') {echo 'checked';}} ?>>
                                    <label class="form-check-label" for="priceRadios2">
                                        Free
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price" id="priceRadios3" value="paid" <?php if(isset($_GET['price'])) {if($_GET['price'] == 'paid') {echo 'checked';}} ?>>
                                    <label class="form-check-label" for="priceRadios3">
                                        Paid
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="widget">
                            <h2>Language</h2>
                            <div class="box">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="language" id="languageRadios0" value="" checked>
                                    <label class="form-check-label" for="languageRadios0">
                                        All
                                    </label>
                                </div>
                                <?php
                                $i=0;
                                $statement = $pdo->prepare("SELECT * FROM languages ORDER BY name ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="language" id="languageRadios<?php echo $i; ?>" value="<?php echo $row['id']; ?>" <?php if(isset($_GET['language'])) {if($_GET['language'] == $row['id']) {echo 'checked';}} ?>>
                                        <label class="form-check-label" for="languageRadios<?php echo $i; ?>">
                                            <?php echo $row['name']; ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="widget">
                            <h2>Category</h2>
                            <div class="box">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" id="categoryRadios0" value="" checked>
                                    <label class="form-check-label" for="categoryRadios0">
                                        All
                                    </label>
                                </div>
                                <?php
                                $i=0;
                                $statement = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="categoryRadios<?php echo $i; ?>" value="<?php echo $row['id']; ?>" <?php if(isset($_GET['category'])) {if($_GET['category'] == $row['id']) {echo 'checked';}} ?>>
                                        <label class="form-check-label" for="categoryRadios<?php echo $i; ?>">
                                            <?php echo $row['name']; ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="widget">
                            <h2>Review</h2>
                            <div class="box">
                                <div class="form-check form-check-review form-check-review-1">
                                    <input name="review" class="form-check-input" type="radio"  id="reviewRadios0" value="" checked>
                                    <label class="form-check-label" for="reviewRadios0">
                                        All
                                    </label>
                                </div>
                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input" type="radio" id="reviewRadios1" value="5" <?php if(isset($_GET['review'])) {if($_GET['review'] == 5) {echo 'checked';}} ?>>
                                    <label class="form-check-label" for="reviewRadios1">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>
                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input" type="radio" id="reviewRadios2" value="4" <?php if(isset($_GET['review'])) {if($_GET['review'] == 4) {echo 'checked';}} ?>>
                                    <label class="form-check-label" for="reviewRadios2">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>
                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input" type="radio" id="reviewRadios3" value="3" <?php if(isset($_GET['review'])) {if($_GET['review'] == 3) {echo 'checked';}} ?>>
                                    <label class="form-check-label" for="reviewRadios3">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>
                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input" type="radio" id="reviewRadios4" value="2" <?php if(isset($_GET['review'])) {if($_GET['review'] == 2) {echo 'checked';}} ?>>
                                    <label class="form-check-label" for="reviewRadios4">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>
                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input" type="radio" id="reviewRadios5" value="1" <?php if(isset($_GET['review'])) {if($_GET['review'] == 1) {echo 'checked';}} ?>>
                                    <label class="form-check-label" for="reviewRadios5">
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="widget">
                            <h2>Skill Level</h2>
                            <div class="box">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="level" id="levelRadios0" value="" checked>
                                    <label class="form-check-label" for="levelRadios0">
                                        All
                                    </label>
                                </div>
                                <?php
                                $i=0;
                                $statement = $pdo->prepare("SELECT * FROM levels ORDER BY id ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="level" id="levelRadios<?php echo $i; ?>" value="<?php echo $row['id']; ?>" <?php if(isset($_GET['level'])) {if($_GET['level'] == $row['id']) {echo 'checked';}} ?>>
                                        <label class="form-check-label" for="levelRadios<?php echo $i; ?>">
                                            <?php echo $row['name']; ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <input type="hidden" name="p" value="1">
                        <div class="filter-button">
                            <button class="btn btn-primary">Filter</button>
                        </div>
                    </div>

                </form>

            </div>
            <div class="col-lg-9 col-md-6">
                <div class="row">
                <?php
                    $query = '';
                    $query = $c_language.$c_category.$c_level.$c_price.$c_review.$c_title;
                    $per_page = 15;
                    $statement = $pdo->prepare("SELECT 
                                        t1.*,
                                        t2.name as category_name,
                                        t3.name as instructor_name,
                                        t3.id as instructor_id
                                        FROM courses t1
                                        JOIN categories t2
                                        ON t1.category_id = t2.id
                                        JOIN instructors t3
                                        ON t1.instructor_id = t3.id
                                        WHERE t1.status=? " . $query);
                    $statement->execute(['Active']);
                    $total = $statement->rowCount();
                    $total_pages = ceil($total/$per_page);

                    if(!isset($_REQUEST['p'])) {
                        $start = 1;
                    } else {
                        $start = $per_page * ($_REQUEST['p']-1) + 1;
                    }


                    $j=0;
                    $k=0;
                    $arr1 = [];
                    $res = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach($res as $row) {
                        $j++;
                        if($j>=$start) {
                            $k++;
                            if($k>$per_page) {break;}
                            $arr1[] = $row['id'];
                        }
                    }
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
                                        WHERE t1.status=? " . $query);
                    $statement->execute(['Active']);
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        if(!in_array($row['id'],$arr1)) {
                            continue;
                        }
                        ?>
                        <div class="col-xl-4 col-lg-6 col-md-12">
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

                <?php if($total > $per_page): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pagi">
                            <nav>
                                <ul class="pagination">
                                    <?php
                                    $common_url = BASE_URL.'courses.php?title='.$_GET['title'].'&price='.$_GET['price'].'&language='.$_GET['language'].'&category='.$_GET['category'].'&review='.$_GET['review'].'&level='.$_GET['level'];
                                    if(isset($_REQUEST['p'])) {
                                        if($_REQUEST['p'] == 1) {
                                            ?>
                                            <li class="page-item"><a class="page-link" href="javascript:void;" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                            <?php
                                        } else {
                                            ?>
                                            <li class="page-item"><a class="page-link" href="<?php echo $common_url; ?>&p=<?php echo ($_REQUEST['p']-1); ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <li class="page-item"><a class="page-link" href="javascript:void;" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                        <?php
                                    }
                                    
                                    for($i=1;$i<=$total_pages;$i++) {
                                        ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $common_url; ?>&p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php
                                    }
                                    
                                    if(isset($_REQUEST['p'])) {
                                        if($_REQUEST['p'] == $total_pages) {
                                            ?>
                                            <li class="page-item"><a class="page-link" href="javascript:void;" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                            <?php
                                        } else {
                                            ?>
                                            <li class="page-item"><a class="page-link" href="<?php echo $common_url; ?>&p=<?php echo ($_REQUEST['p']+1); ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $common_url; ?>&p=2" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>