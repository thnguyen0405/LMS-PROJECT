<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM orders WHERE order_no=?");
$statement->execute([$_REQUEST['order_no']]);
$order_data = $statement->fetch(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if(!$total) {
    $_SESSION['error_message'] = "Order not found";
    header('location: '.ADMIN_URL.'order.php');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM students WHERE id=?");
$statement->execute([$order_data['student_id']]);
$student_data = $statement->fetch(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Invoice</h1>
            <div>
                <a href="<?php echo ADMIN_URL; ?>order.php" class="btn btn-primary"><i class="fas fa-plus"></i> View Orders</a>
            </div>
        </div>
        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Invoice</h2>
                                <div class="invoice-number">Order #<?php echo $_REQUEST['order_no']; ?></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Invoice To</strong><br>
                                        <?php echo $student_data['name']; ?><br>
                                        <?php echo $student_data['email']; ?><br>
                                        <?php echo $student_data['phone']; ?><br>
                                        <?php echo $student_data['address']; ?><br>
                                        <?php echo $student_data['city']; ?>, <?php echo $student_data['state']; ?>, <?php echo $student_data['country']; ?>, <?php echo $student_data['zip']; ?>
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right" style="text-align: right;">
                                    <address>
                                        <strong>Invoice From</strong><br>
                                        <?php echo $_SESSION['admin']['full_name']; ?><br>
                                        <?php echo $_SESSION['admin']['email']; ?><br>
                                        215-899-5780<br>
                                        3145 Glen Falls Road, <br>
                                        Bensalem, PA, USA, 19020
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <hr class="invoice-above-table">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th>SL</th>
                                        <th>Course</th>
                                        <th>Price</th>
                                        <th>Coupon Name</th>
                                        <th>Discount</th>
                                        <th>Final Price</th>
                                    </tr>
                                    <?php
                                    $i=0;
                                    $statement = $pdo->prepare("SELECT 
                                                    t1.*,
                                                    t2.title as course_title
                                                    FROM order_details t1
                                                    JOIN courses t2
                                                    ON t1.course_id = t2.id
                                                    WHERE t1.order_no=?");
                                    $statement->execute([$_REQUEST['order_no']]);
                                    $order_detail_data = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($order_detail_data as $row) {
                                        $i++;
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row['course_title']; ?></td>
                                            <td>$<?php echo $row['course_price']; ?></td>
                                            <td><?php echo $row['coupon_name']; ?></td>
                                            <td>$<?php echo $row['discount']; ?></td>
                                            <td>$<?php echo $row['final_price']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-12 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Total</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">$<?php echo $order_data['total_paid']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="about-print-button">
                <div class="text-md-right">
                    <a href="javascript:window.print();" class="btn btn-warning btn-icon icon-left text-white print-invoice-button"><i class="fas fa-print"></i> Print</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>