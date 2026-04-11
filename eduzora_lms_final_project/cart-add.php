<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM order_details WHERE course_id=? AND student_id=?");
$statement->execute([$_POST['course_id'],$_SESSION['student']['id']]);
$total = $statement->rowCount();
if($total) {
    $_SESSION['error_message'] = "You have already enrolled in this course";
    header('location: '.BASE_URL.'cart');
    exit;
}
?>
<?php
if(isset($_POST['form_cart_add'])) {
    if(isset($_SESSION['cart_course_id'])) {
        $arr_cart_course_id = [];
        $i=0;
        foreach($_SESSION['cart_course_id'] as $key=>$value) 
        {
            $i++;
            $arr_cart_course_id[$i] = $value;   
        }
        if(in_array($_POST['course_id'],$arr_cart_course_id)) {
            $_SESSION['error_message'] = "Course is already in the cart";
            header('location: '.BASE_URL.'cart');
            exit;
        } else {
            $i=0;
            foreach($_SESSION['cart_course_id'] as $key=>$value) {
                $i++;
            }
            $new_key = $i+1;
            $_SESSION['cart_course_id'][$new_key] = $_POST['course_id'];
            $_SESSION['success_message'] = "Course is successfully added to the cart";
            header('location: '.BASE_URL.'cart');
            exit;
        }
    } else {
        $_SESSION['cart_course_id'][1] = $_POST['course_id'];
        $_SESSION['success_message'] = "Course is successfully added to the cart";
        header('location: '.BASE_URL.'cart');
        exit;
    }
}