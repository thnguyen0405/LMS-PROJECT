<?php include "header.php"; ?>

<?php include "config/config_payment.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
// Check if the student updated his profile or not
if($_SESSION['student']['phone']=='' || $_SESSION['student']['address']=='' || $_SESSION['student']['city']=='' || $_SESSION['student']['state']=='' || $_SESSION['student']['country']=='' || $_SESSION['student']['zip']=='') {
    $_SESSION['error_message'] = "Please update your profile first";
    header('location: '.BASE_URL.'student-profile');
    exit;
}
?>

<?php
if(!isset($_SESSION['cart_course_id'])) {
    $_SESSION['error_message'] = "Cart is empty. So you can not checkout.";
    header("location: ".BASE_URL."cart");
    exit;
}
?>

<?php
if(isset($_POST['form_enroll_free'])) {
    try {
        $i=0;
        foreach($_POST['course_id'] as $item) {
            $_SESSION['course_id'][$i] = $item;
            $i++;
        }
        $i=0;
        foreach($_POST['course_price'] as $item) {
            $_SESSION['course_price'][$i] = $item;
            $i++;
        }
        $i=0;
        foreach($_POST['coupon_name'] as $item) {
            $_SESSION['coupon_name'][$i] = $item;
            $i++;
        }
        $i=0;
        foreach($_POST['discount'] as $item) {
            $_SESSION['discount'][$i] = $item;
            $i++;
        }
        $i=0;
        foreach($_POST['final_price'] as $item) {
            $_SESSION['final_price'][$i] = $item;
            $i++;
        }
        $_SESSION['total'] = $_POST['total'];

        $order_no = substr(sha1(uniqid()), 0, 10);
            
        $statement = $pdo->prepare("INSERT INTO orders (
                        order_no,
                        student_id,
                        payment_method,
                        total_paid,
                        payment_status,
                        payment_date
                    ) VALUES (?,?,?,?,?,?)");
        $statement->execute([
                        $order_no,
                        $_SESSION['student']['id'],
                        '',
                        '0',
                        'Completed',
                        date('Y-m-d')
                    ]);
        
        $arr_course_id = [];
        $arr_course_price = [];
        $arr_coupon_name = [];
        $arr_discount = [];
        $arr_final_price = [];

        $i=0;
        foreach($_SESSION['course_id'] as $value) {
            $arr_course_id[$i] = $value;
            $i++;
        }
        $i=0;
        foreach($_SESSION['course_price'] as $value) {
            $arr_course_price[$i] = $value;
            $i++;
        }
        $i=0;
        foreach($_SESSION['coupon_name'] as $value) {
            $arr_coupon_name[$i] = $value;
            $i++;
        }
        $i=0;
        foreach($_SESSION['discount'] as $value) {
            $arr_discount[$i] = $value;
            $i++;
        }
        $i=0;
        foreach($_SESSION['final_price'] as $value) {
            $arr_final_price[$i] = $value;
            $i++;
        }

        for($i=0;$i<count($arr_course_id);$i++) {
            $statement = $pdo->prepare("INSERT INTO order_details (
                            order_no,
                            student_id,
                            course_id,
                            course_price,
                            coupon_name,
                            discount,
                            final_price,
                            instructor_revenue,
                            admin_revenue
                        ) VALUES (?,?,?,?,?,?,?,?,?)");
            $statement->execute([
                            $order_no,
                            $_SESSION['student']['id'],
                            $arr_course_id[$i],
                            $arr_course_price[$i],
                            $arr_coupon_name[$i],
                            $arr_discount[$i],
                            $arr_final_price[$i],
                            0,
                            0
                        ]);
            
            $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
            $statement->execute([$arr_course_id[$i]]);
            $temp_course_data = $statement->fetch(PDO::FETCH_ASSOC);
            $existing_total_student = $temp_course_data['total_student'];
            $new_total_student = $existing_total_student + 1;
            $statement = $pdo->prepare("UPDATE courses SET total_student=? WHERE id=?");
            $statement->execute([$new_total_student, $arr_course_id[$i]]);
        }
        
        $_SESSION['success_message'] = "Enrollment is successful.";

        unset($_SESSION['course_id']);
        unset($_SESSION['course_price']);
        unset($_SESSION['coupon_name']);
        unset($_SESSION['discount']);
        unset($_SESSION['final_price']);
        unset($_SESSION['total']);
        unset($_SESSION['cart_course_id']);
        unset($_SESSION['coupon']);

        header('location: '.BASE_URL."student-order");
        exit;
        

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("location: ".BASE_URL."checkout");
        exit;
    }
}
if(isset($_POST['form_payment'])) {
    try {
        $i=0;
        foreach($_POST['course_id'] as $item) {
            $_SESSION['course_id'][$i] = $item;
            $i++;
        }
        $i=0;
        foreach($_POST['course_price'] as $item) {
            $_SESSION['course_price'][$i] = $item;
            $i++;
        }
        $i=0;
        foreach($_POST['coupon_name'] as $item) {
            $_SESSION['coupon_name'][$i] = $item;
            $i++;
        }
        $i=0;
        foreach($_POST['discount'] as $item) {
            $_SESSION['discount'][$i] = $item;
            $i++;
        }
        $i=0;
        foreach($_POST['final_price'] as $item) {
            $_SESSION['final_price'][$i] = $item;
            $i++;
        }
        $_SESSION['total'] = $_POST['total'];

        if($_POST['payment_method'] == 'paypal') 
        {
            $response = $gateway->purchase(array(
                'amount' => $_SESSION['total'],
                'currency' => PAYPAL_CURRENCY,
                'returnUrl' => PAYPAL_RETURN_URL,
                'cancelUrl' => PAYPAL_CANCEL_URL,
            ))->send();
            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                echo $response->getMessage();
            }
        }
        elseif($_POST['payment_method'] == 'stripe') 
        {
            
            $qty = count($_SESSION['course_id']);

            \Stripe\Stripe::setApiKey(STRIPE_TEST_SK);
            $response = \Stripe\Checkout\Session::create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Course Purchase'
                            ],
                            'unit_amount' => $_SESSION['total'] * 100,
                        ],
                        'quantity' => $qty,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => STRIPE_SUCCESS_URL.'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => STRIPE_CANCEL_URL,
            ]);
            header('location: '.$response->url);
        }

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("location: ".BASE_URL."checkout");
        exit;
    }
}
?>

<div class="page-top" style="background-image: url('<?php echo BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Checkout</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="" method="post">
<div class="checkout pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2>Billing Information</h2>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name: </th>
                                    <td><?php echo $_SESSION['student']['name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email: </th>
                                    <td><?php echo $_SESSION['student']['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone: </th>
                                    <td><?php echo $_SESSION['student']['phone']; ?></td>
                                </tr>
                                <tr>
                                    <th>Address: </th>
                                    <td><?php echo $_SESSION['student']['address']; ?></td>
                                </tr>
                                <tr>
                                    <th>Country: </th>
                                    <td><?php echo $_SESSION['student']['country']; ?></td>
                                </tr>
                                <tr>
                                    <th>State: </th>
                                    <td><?php echo $_SESSION['student']['state']; ?></td>
                                </tr>
                                <tr>
                                    <th>City: </th>
                                    <td><?php echo $_SESSION['student']['city']; ?></td>
                                </tr>
                                <tr>
                                    <th>Zip: </th>
                                    <td><?php echo $_SESSION['student']['zip']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-4">
                <h2>Order Detail</h2>
                <div class="order-detail">
                    <?php
                    $i=0;
                    $total = 0;
                    foreach($_SESSION['cart_course_id'] as $key=>$value) {
                        $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
                        $statement->execute([$value]);
                        $course = $statement->fetch(PDO::FETCH_ASSOC);
                        $i++;
                        ?>
                        <div class="course-item">
                            <div class="course-image">
                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $course['featured_photo']; ?>" alt="">
                            </div>
                            <div class="course-content">
                                <h3><a href="<?php echo BASE_URL; ?>course/<?php echo $course['slug']; ?>"><?php echo $course['title']; ?></a></h3>
                                <?php if(isset($_SESSION['coupon'][$value])): ?>
                                    <?php
                                    $coupon_name = $_SESSION['coupon'][$value];
                                    $q = $pdo->prepare("SELECT * FROM coupons WHERE coupon_name=?");
                                    $q->execute([$_SESSION['coupon'][$value]]);
                                    $coupon_data = $q->fetch(PDO::FETCH_ASSOC);
                                    $discount = ($course['price'] * $coupon_data['discount_percentage']) / 100;
                                    $subtotal = $course['price'] - $discount;
                                    $subtotal = floor($subtotal);
                                    ?>
                                <?php else: ?>
                                    <?php
                                        $coupon_name = '';
                                        $discount = 0;
                                        $subtotal = $course['price'];
                                    ?>
                                <?php endif; ?>
                                <p>Price: $<?php echo $subtotal; ?></p>
                            </div>
                        </div>
                        <input type="hidden" name="course_id[]" value="<?php echo $course['id']; ?>">
                        <input type="hidden" name="course_price[]" value="<?php echo $course['price']; ?>">
                        <input type="hidden" name="coupon_name[]" value="<?php echo $coupon_name; ?>">
                        <input type="hidden" name="discount[]" value="<?php echo $discount; ?>">
                        <input type="hidden" name="final_price[]" value="<?php echo $subtotal; ?>">
                        <?php
                        $total = $total + (int)$subtotal;
                    }
                    ?>
                </div>

                <h2 class="mt_30">Order Summary</h2>
                <div class="summary">
                    <p>Total: $<?php echo $total; ?></p>
                </div>

                <?php if($total != 0): ?>
                <h2 class="mt_30">Select Payment Method</h2>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" checked>
                        <label class="form-check-label" for="paypal">
                            PayPal
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                        <label class="form-check-label" for="stripe">
                            Stripe
                        </label>
                    </div>
                </div>
                <?php endif; ?>

                <div class="agree">
                    By completing your purchase you agree to these <a href="<?php echo BASE_URL; ?>terms">Terms of Service</a>.
                </div>

                <div class="proceed">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    <?php if($total != 0): ?>
                    <button type="submit" class="btn btn-primary" name="form_payment">Proceed</button>
                    <?php else: ?>
                    <button type="submit" class="btn btn-primary" name="form_enroll_free">Free Enroll</button>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>cart">Back to Cart</a>
                </div>

            </div>
        </div>
    </div>
</div>
</form>

<?php include "footer.php"; ?>