<?php
ob_start();
include "header.php";
ob_end_clean();

header("Content-Type: text/html; charset=UTF-8");

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$title    = isset($_GET['title']) ? trim($_GET['title']) : '';
$price    = isset($_GET['price']) ? trim($_GET['price']) : '';
$language = isset($_GET['language']) ? trim($_GET['language']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$review   = isset($_GET['review']) ? trim($_GET['review']) : '';
$level    = isset($_GET['level']) ? trim($_GET['level']) : '';
$page     = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;

$where = [];
$params = [];

$where[] = "t1.status = ?";
$params[] = "Active";

if ($title !== '') {
    $where[] = "(t1.title LIKE ? OR t2.name LIKE ? OR t3.name LIKE ?)";
    $search = "%" . $title . "%";
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
}

if ($price !== '') {
    if ($price === 'free') {
        $where[] = "t1.price = 0";
    } elseif ($price === 'paid') {
        $where[] = "t1.price != 0";
    }
}

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

$where_sql = implode(" AND ", $where);

$per_page = 15;
$offset = ($page - 1) * $per_page;

$sql = "
    SELECT 
        t1.*,
        t2.id AS category_id,
        t2.name AS category_name,
        t3.name AS instructor_name,
        t3.id AS instructor_id
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
    exit;
}

foreach ($result as $row) {
    ?>
    <div class="col-xl-4 col-lg-6 col-md-12">
        <div class="item pb_25">

            <div class="photo">
                <a href="<?php echo BASE_URL; ?>course/<?php echo h($row['slug']); ?>">
                    <img 
                        src="<?php echo BASE_URL; ?>uploads/<?php echo h($row['featured_photo']); ?>" 
                        alt="<?php echo h($row['title']); ?>"
                    >
                </a>

                <div class="wishlist">
                    <a href="<?php echo BASE_URL; ?>student-wishlist-add.php?id=<?php echo (int)$row['id']; ?>">
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
                        <a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=<?php echo (int)$row['category_id']; ?>&review=&level=">
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
                    <a href="<?php echo BASE_URL; ?>instructor/<?php echo (int)$row['instructor_id']; ?>">
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
                            <input type="hidden" name="course_id" value="<?php echo (int)$row['id']; ?>">
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