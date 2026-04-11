<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Orders</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
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
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>SL</th>
                                <th>Order No</th>
                                <th>Payment Method</th>
                                <th>Total Price</th>
                                <th>Payment Status</th>
                                <th>Payment Date</th>
                                <th class="w-100">
                                    Action
                                </th>
                            </tr>
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT * FROM orders WHERE student_id=? ORDER BY id DESC");
                            $statement->execute([$_SESSION['student']['id']]);
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['order_no']; ?></td>
                                    <td><?php echo $row['payment_method']; ?></td>
                                    <td>$<?php echo $row['total_paid']; ?></td>
                                    <td><?php echo $row['payment_status']; ?></td>
                                    <td><?php echo $row['payment_date']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>student-order-invoice/<?php echo $row['order_no']; ?>" class="btn btn-secondary btn-sm w-100-p">Invoice</a>
                                    </td>
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
</div>

<?php include "footer.php"; ?>