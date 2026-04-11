<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
if(isset($_POST['form_remove_coupon'])) {
    unset($_SESSION['coupon'][$_POST['course_id']]);
    $success_message = "Coupon has been removed successfully.";
    $_SESSION['success_message'] = $success_message;
    header("location: ".BASE_URL."cart");
    exit;
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Cart</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Cart</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cart pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if(!isset($_SESSION['cart_course_id'])): ?>
                    <div class="alert alert-danger">Cart is empty</div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>SL</th>
                            <th class="w-200">Photo</th>
                            <th>Title</th>
                            <th>Course Price</th>
                            <th>Applied Coupon</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            $i=0;
                            $total = 0;
                            foreach($_SESSION['cart_course_id'] as $key=>$value) {
                                $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
                                $statement->execute([$value]);
                                $course = $statement->fetch(PDO::FETCH_ASSOC);
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $course['featured_photo']; ?>" alt="" class="w-150">
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>course/<?php echo $course['slug']; ?>">
                                        <?php echo $course['title']; ?>
                                        </a>
                                    </td>
                                    <td>$<?php echo $course['price']; ?></td>
                                    <td>
                                        
                                        <?php if(isset($_SESSION['coupon'][$value])): ?>
                                        
                                        <?php
                                        $q = $pdo->prepare("SELECT * FROM coupons WHERE coupon_name=?");
                                        $q->execute([$_SESSION['coupon'][$value]]);
                                        $coupon_data = $q->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                        <?php echo $_SESSION['coupon'][$value]; ?> (<?php echo $coupon_data['discount_percentage']; ?>% Discount)<br>
                                        <form action="" method="post">
                                            <input type="hidden" name="course_id" value="<?php echo $value; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?')" name="form_remove_coupon">Remove Coupon</button>
                                        </form>

                                        <?php else: ?>

                                            <span class="text-danger">Not Found</span>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(isset($_SESSION['coupon'][$value])): ?>
                                            <?php
                                            $discount = ($course['price'] * $coupon_data['discount_percentage']) / 100;
                                            $subtotal = $course['price'] - $discount;
                                            ?>
                                            $<?php echo floor($subtotal); ?>
                                        <?php else: ?>
                                            <?php $subtotal = $course['price']; ?>
                                            $<?php echo $subtotal; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>cart-delete.php?id=<?php echo $value; ?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?')">X</a>
                                    </td>
                                </tr>
                                <?php
                                $total = $total + (int)$subtotal;
                            }
                        ?>
                        <tr>
                            <td colspan="5" style="text-align:right;font-weight:700;font-size:22px;">Total Price</td>
                            <td style="font-weight:700;font-size:22px;">$<?php echo $total; ?></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="checkout" style="text-align:right;">
                    <a href="<?php echo BASE_URL; ?>checkout" class="btn btn-primary">Go to Checkout</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>