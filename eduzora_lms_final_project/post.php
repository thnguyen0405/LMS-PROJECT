<?php include "header.php"; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM posts WHERE slug=?");
$statement->execute([$_REQUEST['slug']]);
$post_data = $statement->fetch(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if($total==0) {
    header("location: " . BASE_URL . "blog");
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo $post_data['title']; ?></h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>blog">Blog</a></li>
                        <li class="breadcrumb-item active"><?php echo $post_data['title']; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="post pt_50 pb_50">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-9 col-lg-12 col-md-12">
                <div class="left-item">
                    <div class="main-photo">
                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $post_data['photo']; ?>" alt="">
                    </div>
                    <div class="sub">
                        <ul>
                            <li>
                                <i class="fas fa-calendar-alt"></i> Posted On: 
                                <?php echo date("F d, Y", strtotime($post_data['post_date'])); ?>
                            </li>
                        </ul>
                    </div>
                    <div class="description">
                        <?php echo $post_data['description']; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include "footer.php"; ?>