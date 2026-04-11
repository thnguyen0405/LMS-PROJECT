<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM admins WHERE id=?");
$statement->execute([1]);
$admin_data = $statement->fetch(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("SELECT * FROM orders WHERE order_no=?");
$statement->execute([$_REQUEST['order_no']]);
$order_data = $statement->fetch(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if(!$total) {
    $_SESSION['error_message'] = "Order not found";
    header('location: '.BASE_URL.'student-order');
    exit;
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Invoice: <?php echo $_REQUEST['order_no']; ?></h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Invoice: <?php echo $_REQUEST['order_no']; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="card">
                    <?php include "student-sidebar.php"; ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="invoice-container" id="print_invoice">
                    <div class="invoice-top">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-border-0">
                                        <tbody>
                                            <tr>
                                                <td class="w-50">
                                                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo $setting_data['logo']; ?>" alt="" class="h-60">
                                                </td>
                                                <td class="w-50">
                                                    <div class="invoice-top-right">
                                                        <h4>Invoice</h4>
                                                        <h5>Order No: <?php echo $_REQUEST['order_no']; ?></h5>
                                                        <h5>Date: <?php echo $order_data['payment_date']; ?></h5>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-middle">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-border-0">
                                        <tbody>
                                            <tr>
                                                <td class="w-50">
                                                    <div class="invoice-middle-left">
                                                        <h4>Invoice To:</h4>
                                                        <p class="mb_0"><?php echo $_SESSION['student']['name']; ?></p>
                                                        <p class="mb_0"><?php echo $_SESSION['student']['email']; ?></p>
                                                        <p class="mb_0"><?php echo $_SESSION['student']['phone']; ?></p>
                                                        <p class="mb_0"><?php echo $_SESSION['student']['address']; ?></p>
                                                        <p class="mb_0"><?php echo $_SESSION['student']['city']; ?>, <?php echo $_SESSION['student']['state']; ?>, <?php echo $_SESSION['student']['country']; ?>, <?php echo $_SESSION['student']['zip']; ?></p>
                                                    </div>
                                                </td>
                                                <td class="w-50">
                                                    <div class="invoice-middle-right">
                                                        <h4>Invoice From:</h4>
                                                        <p class="mb_0">EduZora</p>
                                                        <p class="mb_0 color_6d6d6d"><?php echo $admin_data['email']; ?></p>
                                                        <p class="mb_0">215-899-5780</p>
                                                        <p class="mb_0">3145 Glen Falls Road</p>
                                                        <p class="mb_0">Bensalem, PA 19020</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-item">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered invoice-item-table">
                                        <tbody>
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
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-bottom">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-border-0">
                                        <tbody>
                                            <td class="w-70 invoice-bottom-payment">
                                                <h4>Payment Method</h4>
                                                <?php if($order_data['payment_method'] != 'PayPal' && $order_data['payment_method'] != 'Stripe'): ?>
                                                <p>Not Applicable</p>
                                                <?php else: ?>
                                                <p><?php echo $order_data['payment_method']; ?></p>
                                                <?php endif; ?>
                                            </td>
                                            <td class="w-30 tar invoice-bottom-total-box">
                                                <p class="mb_0">Total: <span>$<?php echo $order_data['total_paid']; ?></span></p>
                                            </td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="print-button mt_25">
                    <a onclick="printInvoice()" href="javascript:;" class="btn btn-primary"><i class="fas fa-print"></i> Print</a>
                </div>
                <script>
                    function printInvoice() {
                        let body = document.body.innerHTML;
                        let data = document.getElementById('print_invoice').innerHTML;
                        document.body.innerHTML = data;
                        window.print();
                        document.body.innerHTML = body;
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>