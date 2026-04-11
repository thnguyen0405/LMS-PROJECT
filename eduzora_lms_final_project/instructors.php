<?php include "header.php"; ?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Instructors</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Instructors</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pt_70 pb_50">
    <div class="container">
        <div class="row instructor">

            <?php
            $per_page = 12;
            $q = $pdo->prepare("SELECT * FROM instructors WHERE status=? ORDER BY average_rating DESC");
            $q->execute([1]);
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
            $statement = $pdo->prepare("SELECT * FROM instructors WHERE status=? ORDER BY average_rating DESC");
            $statement->execute([1]);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                if(!in_array($row['id'],$arr1)) {
                    continue;
                }
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
                                    <li class="page-item"><a class="page-link" href="<?php echo BASE_URL; ?>instructors/<?php echo $temp; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li class="page-item"><a class="page-link" href="javascript:void;" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                <?php
                            }
                            for($i=1;$i<=$total_pages;$i++) {
                                ?>
                                <li class="page-item"><a class="page-link" href="<?php echo BASE_URL; ?>instructors/<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
                                    <li class="page-item"><a class="page-link" href="<?php echo BASE_URL; ?>instructors/<?php echo $temp; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li class="page-item"><a class="page-link" href="<?php echo BASE_URL; ?>instructors/2" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
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

<?php include "footer.php"; ?>