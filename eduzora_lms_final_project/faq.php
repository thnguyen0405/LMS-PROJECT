<?php include "header.php"; ?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>FAQ</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">FAQ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="faq pt_70 pb_40">
    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="accordion" id="accordionExample">

                    <?php
                    $i=0;
                    $statement = $pdo->prepare("SELECT * FROM faqs ORDER BY id ASC");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        $i++;
                        ?>
                        <div class="accordion-item mb_30">
                            <h2 class="accordion-header" id="heading_<?php echo $i; ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse_<?php echo $i; ?>">
                                    <?php echo $row['question']; ?>
                                </button>
                            </h2>
                            <div id="collapse_<?php echo $i; ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?php echo $i; ?>" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <?php echo nl2br($row['answer']); ?>
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

<?php include "footer.php"; ?>