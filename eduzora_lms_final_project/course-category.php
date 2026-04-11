<?php include "header.php"; ?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Course Categories</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Course Categories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="course-category pt_70 pb_70">
    <div class="container">
        <div class="row">
            <?php
            $statement = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                ?>
                <div class="col-lg-2 col-md-3 col-sm-12">
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
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>