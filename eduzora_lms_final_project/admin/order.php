<?php include 'layouts/top.php'; ?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Orders</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Order No</th>
                                            <th>Payment Method</th>
                                            <th>Total Price</th>
                                            <th>Payment Status</th>
                                            <th>Payment Date</th>
                                            <th class="w_100">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $i=0;
                                        $statement = $pdo->prepare("SELECT * FROM orders ORDER BY id DESC");
                                        $statement->execute();
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
                                                    <a href="<?php echo ADMIN_URL; ?>order-invoice.php?order_no=<?php echo $row['order_no']; ?>" class="btn btn-primary btn-sm">Invoice</a>
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
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>