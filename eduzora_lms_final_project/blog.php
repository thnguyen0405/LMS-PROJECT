<?php include "header.php"; ?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Blog</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Blog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="blog pt_70 pb_70">
    <div class="container">
        <div class="row">
            <?php
            $per_page =20;
            $q = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
            $q->execute();
            $total = $q->rowCount();
            $total_pages = ceil($total/$per_page);    
            
            if(!isset($_REQUEST['p'])) {
                $start = 1;
            } else {
                $start = $per_page * ($_REQUEST['p']-1) + 1;
            }
            
            $j=0;
            $k=0;
            $arr1 = [];
            $res = $q->fetchAll();
            foreach($res as $row) {
                $j++;
                if($j>=$start) {
                    $k++;
                    if($k>$per_page) {break;}
                    $arr1[] = $row['id'];
                }
            }
            ?>
            <?php
            $statement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                if(!in_array($row['id'],$arr1)) {
                    continue;
                }
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

        <?php if($total > $per_page): ?>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="pagi">
                    <nav>
                        <ul class="pagination">
                        <?php
                            if(isset($_REQUEST['p'])) {
                                if($_REQUEST['p'] == 1) {
                                    ?>
                                    <li class="page-item"><a class="page-link" href="javascript:void;" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                    <?php
                                } else {
                                    $temp = $_REQUEST['p']-1;
                                    ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo BASE_URL; ?>blog/<?php echo $temp; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li class="page-item"><a class="page-link" href="javascript:void;" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                <?php
                            }
                            for($i=1;$i<=$total_pages;$i++) {
                                ?>
                                <li class="page-item"><a class="page-link" href="<?php echo BASE_URL; ?>blog/<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php
                            }
                            if(isset($_REQUEST['p'])) {
                                if($_REQUEST['p'] == $total_pages) {
                                    ?>
                                    <li class="page-item"><a class="page-link" href="javascript:void;" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                    <?php
                                } else {
                                    $temp = $_REQUEST['p']+1;
                                    ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo BASE_URL; ?>blog/<?php echo $temp; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li class="page-item"><a class="page-link" href="<?php echo BASE_URL; ?>blog/2" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php endif; ?>


        </div>
    </div>
</div>

<?php include "footer.php"; ?>