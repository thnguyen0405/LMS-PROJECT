<?php include "header.php"; ?>

<?php
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function get_filter_value($key) {
    return isset($_GET[$key]) ? trim($_GET[$key]) : '';
}

$title    = get_filter_value('title');
$price    = get_filter_value('price');
$language = get_filter_value('language');
$category = get_filter_value('category');
$review   = get_filter_value('review');
$level    = get_filter_value('level');
$page     = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;

$where = [];
$params = [];

$where[] = "t1.status = ?";
$params[] = "Active";

if ($language !== '') {
    $where[] = "t1.language_id = ?";
    $params[] = (int)$language;
}

if ($category !== '') {
    $where[] = "t1.category_id = ?";
    $params[] = (int)$category;
}

if ($level !== '') {
    $where[] = "t1.level_id = ?";
    $params[] = (int)$level;
}

if ($price !== '') {
    if ($price === 'free') {
        $where[] = "t1.price = 0";
    } elseif ($price === 'paid') {
        $where[] = "t1.price != 0";
    }
}

if ($review !== '') {
    $review = (int)$review;

    if ($review == 5) {
        $where[] = "t1.average_rating IN (5, 4.5)";
    } elseif ($review == 4) {
        $where[] = "t1.average_rating IN (4, 3.5)";
    } elseif ($review == 3) {
        $where[] = "t1.average_rating IN (3, 2.5)";
    } elseif ($review == 2) {
        $where[] = "t1.average_rating IN (2, 1.5)";
    } elseif ($review == 1) {
        $where[] = "t1.average_rating = 1";
    }
}

if ($title !== '') {
    $where[] = "t1.title LIKE ?";
    $params[] = "%" . $title . "%";
}

$where_sql = implode(" AND ", $where);
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

                <form action="" method="get" id="courseFilterForm">

                    <div class="course-sidebar">

                        <div class="widget">
                            <h2>Course Title</h2>
                            <div class="box">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input 
                                            type="text" 
                                            name="title" 
                                            id="ajaxCourseSearch"
                                            class="form-control" 
                                            placeholder="Course Title ..." 
                                            autocomplete="off"
                                            value="<?php echo h($title); ?>"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="widget">
                            <h2>Price</h2>
                            <div class="box">
                                <div class="form-check">
                                    <input class="form-check-input ajax-filter" type="radio" name="price" id="priceRadios1" value="" <?php if($price == '') echo 'checked'; ?>>
                                    <label class="form-check-label" for="priceRadios1">All</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input ajax-filter" type="radio" name="price" id="priceRadios2" value="free" <?php if($price == 'free') echo 'checked'; ?>>
                                    <label class="form-check-label" for="priceRadios2">Free</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input ajax-filter" type="radio" name="price" id="priceRadios3" value="paid" <?php if($price == 'paid') echo 'checked'; ?>>
                                    <label class="form-check-label" for="priceRadios3">Paid</label>
                                </div>
                            </div>
                        </div>

                        <div class="widget">
                            <h2>Language</h2>
                            <div class="box">
                                <div class="form-check">
                                    <input class="form-check-input ajax-filter" type="radio" name="language" id="languageRadios0" value="" <?php if($language == '') echo 'checked'; ?>>
                                    <label class="form-check-label" for="languageRadios0">All</label>
                                </div>

                                <?php
                                $i = 0;
                                $statement = $pdo->prepare("SELECT * FROM languages ORDER BY name ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input ajax-filter" 
                                            type="radio" 
                                            name="language" 
                                            id="languageRadios<?php echo $i; ?>" 
                                            value="<?php echo $row['id']; ?>" 
                                            <?php if($language == $row['id']) echo 'checked'; ?>
                                        >
                                        <label class="form-check-label" for="languageRadios<?php echo $i; ?>">
                                            <?php echo h($row['name']); ?>
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
                                    <input class="form-check-input ajax-filter" type="radio" name="category" id="categoryRadios0" value="" <?php if($category == '') echo 'checked'; ?>>
                                    <label class="form-check-label" for="categoryRadios0">All</label>
                                </div>

                                <?php
                                $i = 0;
                                $statement = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input ajax-filter" 
                                            type="radio" 
                                            name="category" 
                                            id="categoryRadios<?php echo $i; ?>" 
                                            value="<?php echo $row['id']; ?>" 
                                            <?php if($category == $row['id']) echo 'checked'; ?>
                                        >
                                        <label class="form-check-label" for="categoryRadios<?php echo $i; ?>">
                                            <?php echo h($row['name']); ?>
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
                                    <input name="review" class="form-check-input ajax-filter" type="radio" id="reviewRadios0" value="" <?php if($review == '') echo 'checked'; ?>>
                                    <label class="form-check-label" for="reviewRadios0">All</label>
                                </div>

                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input ajax-filter" type="radio" id="reviewRadios1" value="5" <?php if($review == 5) echo 'checked'; ?>>
                                    <label class="form-check-label" for="reviewRadios1">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>

                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input ajax-filter" type="radio" id="reviewRadios2" value="4" <?php if($review == 4) echo 'checked'; ?>>
                                    <label class="form-check-label" for="reviewRadios2">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>

                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input ajax-filter" type="radio" id="reviewRadios3" value="3" <?php if($review == 3) echo 'checked'; ?>>
                                    <label class="form-check-label" for="reviewRadios3">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>

                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input ajax-filter" type="radio" id="reviewRadios4" value="2" <?php if($review == 2) echo 'checked'; ?>>
                                    <label class="form-check-label" for="reviewRadios4">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>

                                <div class="form-check form-check-review">
                                    <input name="review" class="form-check-input ajax-filter" type="radio" id="reviewRadios5" value="1" <?php if($review == 1) echo 'checked'; ?>>
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
                                    <input class="form-check-input ajax-filter" type="radio" name="level" id="levelRadios0" value="" <?php if($level == '') echo 'checked'; ?>>
                                    <label class="form-check-label" for="levelRadios0">All</label>
                                </div>

                                <?php
                                $i = 0;
                                $statement = $pdo->prepare("SELECT * FROM levels ORDER BY id ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input ajax-filter" 
                                            type="radio" 
                                            name="level" 
                                            id="levelRadios<?php echo $i; ?>" 
                                            value="<?php echo $row['id']; ?>" 
                                            <?php if($level == $row['id']) echo 'checked'; ?>
                                        >
                                        <label class="form-check-label" for="levelRadios<?php echo $i; ?>">
                                            <?php echo h($row['name']); ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>

                        <input type="hidden" name="p" value="1">

                        <div class="filter-button">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>

                    </div>

                </form>

            </div>

            <div class="col-lg-9 col-md-6">

                <div class="row" id="courseList">

                    <?php
                    $per_page = 15;
                    $offset = ($page - 1) * $per_page;

                    $count_sql = "
                        SELECT COUNT(*)
                        FROM courses t1
                        JOIN categories t2 ON t1.category_id = t2.id
                        JOIN instructors t3 ON t1.instructor_id = t3.id
                        WHERE $where_sql
                    ";

                    $statement = $pdo->prepare($count_sql);
                    $statement->execute($params);
                    $total = (int)$statement->fetchColumn();
                    $total_pages = ceil($total / $per_page);

                    $sql = "
                        SELECT 
                            t1.*,
                            t2.id as category_id,
                            t2.name as category_name,
                            t3.name as instructor_name,
                            t3.id as instructor_id
                        FROM courses t1
                        JOIN categories t2 ON t1.category_id = t2.id
                        JOIN instructors t3 ON t1.instructor_id = t3.id
                        WHERE $where_sql
                        ORDER BY t1.id DESC
                        LIMIT $per_page OFFSET $offset
                    ";

                    $statement = $pdo->prepare($sql);
                    $statement->execute($params);
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                    if (!$result) {
                        echo '
                        <div class="col-md-12">
                            <div class="alert alert-warning text-center">
                                No courses found.
                            </div>
                        </div>';
                    }

                    foreach ($result as $row) {
                        ?>
                        <div class="col-xl-4 col-lg-6 col-md-12">
                            <div class="item pb_25">

                                <div class="photo">
                                    <a href="<?php echo BASE_URL; ?>course/<?php echo h($row['slug']); ?>">
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo h($row['featured_photo']); ?>" alt="<?php echo h($row['title']); ?>">
                                    </a>

                                    <div class="wishlist">
                                        <a href="<?php echo BASE_URL; ?>student-wishlist-add.php?id=<?php echo $row['id']; ?>">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="text">
                                    <h2>
                                        <a href="<?php echo BASE_URL; ?>course/<?php echo h($row['slug']); ?>">
                                            <?php echo h($row['title']); ?>
                                        </a>
                                    </h2>

                                    <div class="category">
                                        <span class="badge bg-primary">
                                            <a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=<?php echo $row['category_id']; ?>&review=&level=">
                                                <?php echo h($row['category_name']); ?>
                                            </a>
                                        </span>
                                    </div>

                                    <div class="rating">
                                        <div class="left">
                                            <i class="fas fa-star"></i> 
                                            <?php echo h($row['average_rating']); ?> 
                                            (<?php echo h($row['total_rating']); ?> Ratings)
                                        </div>

                                        <div class="student">
                                            <i class="fas fa-user"></i> 
                                            <?php echo h($row['total_student']); ?> Persons
                                        </div>
                                    </div>

                                    <div class="ins">
                                        By: 
                                        <a href="<?php echo BASE_URL; ?>instructor/<?php echo $row['instructor_id']; ?>">
                                            <?php echo h($row['instructor_name']); ?>
                                        </a>
                                    </div>

                                    <div class="bottom">
                                        <div class="price">
                                            $<?php echo h($row['price']); ?>

                                            <?php if($row['price_old'] != ''): ?>
                                                <del>$<?php echo h($row['price_old']); ?></del>
                                            <?php endif; ?>
                                        </div>

                                        <div class="cart-add">
                                            <form action="<?php echo BASE_URL; ?>cart-add.php" method="post">
                                                <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-primary btn-sm" name="form_cart_add">
                                                    Add to Cart
                                                </button>
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

                <div id="normalPagination">
                    <?php if($total > $per_page): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pagi">
                                    <nav>
                                        <ul class="pagination">
                                            <?php
                                            $common_url = BASE_URL . 'courses.php?title=' . urlencode($title) . '&price=' . urlencode($price) . '&language=' . urlencode($language) . '&category=' . urlencode($category) . '&review=' . urlencode($review) . '&level=' . urlencode($level);

                                            if($page <= 1) {
                                                echo '<li class="page-item"><a class="page-link" href="javascript:void(0);" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                                            } else {
                                                echo '<li class="page-item"><a class="page-link" href="' . $common_url . '&p=' . ($page - 1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                                            }

                                            for($i = 1; $i <= $total_pages; $i++) {
                                                echo '<li class="page-item"><a class="page-link" href="' . $common_url . '&p=' . $i . '">' . $i . '</a></li>';
                                            }

                                            if($page >= $total_pages) {
                                                echo '<li class="page-item"><a class="page-link" href="javascript:void(0);" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                                            } else {
                                                echo '<li class="page-item"><a class="page-link" href="' . $common_url . '&p=' . ($page + 1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
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
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("courseFilterForm");
    const searchInput = document.getElementById("ajaxCourseSearch");
    const courseList = document.getElementById("courseList");
    const normalPagination = document.getElementById("normalPagination");

    console.log("AJAX search script loaded");
    console.log("Search input:", searchInput);
    console.log("Course list:", courseList);

    if (!form || !searchInput || !courseList) {
        console.error("Missing AJAX elements. Check courseFilterForm, ajaxCourseSearch, or courseList.");
        return;
    }

    let typingTimer = null;

    function loadCourses() {
        const formData = new FormData(form);
        formData.set("p", "1");

        const params = new URLSearchParams(formData);

        console.log("AJAX searching:", params.toString());

        courseList.innerHTML = `
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    Loading courses...
                </div>
            </div>
        `;

        if (normalPagination) {
            normalPagination.style.display = "none";
        }

        fetch("<?php echo BASE_URL; ?>ajax-course-search.php?" + params.toString(), {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(function(response) {
            console.log("AJAX response status:", response.status);

            if (!response.ok) {
                throw new Error("AJAX failed with status " + response.status);
            }

            return response.text();
        })
        .then(function(html) {
            courseList.innerHTML = html;
        })
        .catch(function(error) {
            console.error("AJAX error:", error);

            courseList.innerHTML = `
                <div class="col-md-12">
                    <div class="alert alert-danger text-center">
                        AJAX search failed. Please check Console and Network tab.
                    </div>
                </div>
            `;
        });
    }

    searchInput.addEventListener("keyup", function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(loadCourses, 300);
    });

    const filters = form.querySelectorAll(".ajax-filter");

    filters.forEach(function(filter) {
        filter.addEventListener("change", function() {
            loadCourses();
        });
    });
});
</script>

<?php include "footer.php"; ?>