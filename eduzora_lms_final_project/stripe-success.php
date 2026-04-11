<?php include "header.php"; ?>

<?php include "config/config_payment.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
if(!isset($_SESSION['course_id'])) {
    header('location: '.BASE_URL);
    exit;
}
?>

<?php
if (isset($_GET['session_id'])) {
    \Stripe\Stripe::setApiKey(STRIPE_TEST_SK);
    $response = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
    $paymentIntent = $response->payment_intent; // Transaction Id

    
    // Generate a unique secured order_no. Use sha
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
                    'Stripe',
                    $_SESSION['total'],
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

    $statement = $pdo->prepare("SELECT * FROM settings WHERE id=?");
    $statement->execute([1]);
    $setting_data = $statement->fetch(PDO::FETCH_ASSOC);
    $admin_commission = $setting_data['sales_commission'];

    for($i=0;$i<count($arr_course_id);$i++) {
        $instructor_revenue = $arr_final_price[$i] - (($arr_final_price[$i] * $admin_commission) / 100);
        $instructor_revenue = number_format((float)$instructor_revenue, 1, '.', '');
        $admin_revenue = $arr_final_price[$i] - $instructor_revenue;
        $admin_revenue = number_format((float)$admin_revenue, 1, '.', '');
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
                        $instructor_revenue,
                        $admin_revenue
                    ]);
        $statement = $pdo->prepare("SELECT * FROM courses WHERE id=?");
        $statement->execute([$arr_course_id[$i]]);
        $temp_course_data = $statement->fetch(PDO::FETCH_ASSOC);
        $existing_total_student = $temp_course_data['total_student'];
        $new_total_student = $existing_total_student + 1;
        $statement = $pdo->prepare("UPDATE courses SET total_student=? WHERE id=?");
        $statement->execute([$new_total_student, $arr_course_id[$i]]);
    }
    
    $_SESSION['success_message'] = "Payment is successful.";

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

} else {
    header('location: '.STRIPE_CANCEL_URL);
}