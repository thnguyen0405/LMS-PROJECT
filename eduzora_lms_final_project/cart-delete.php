<?php include "header.php"; ?>

<?php
if(!isset($_SESSION['student'])) {
    $_SESSION['error_message'] = "Please login first";
    header('location: '.BASE_URL.'login');
    exit;
}
?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: '.BASE_URL.'cart');
    exit;
}

$arr_cart_course_id = [];
$i=0;
foreach($_SESSION['cart_course_id'] as $value) {
    $i++;
    $arr_cart_course_id[$i] = $value;
}
unset($_SESSION['cart_course_id']);
$k=0;
for($i=1;$i<=count($arr_cart_course_id);$i++) {
    if($arr_cart_course_id[$i]!=$_REQUEST['id']) {
        $k++;
        $_SESSION['cart_course_id'][$k] = $arr_cart_course_id[$i];
    }
}

$_SESSION['success_message'] = "Course is successfully removed from the cart";
header('location: '.BASE_URL.'cart');
exit;

// Filter out the item with the id passed in the request
// $_SESSION['cart_course_id'] = array_filter(
//     $_SESSION['cart_course_id'],
//     function($value) {
//         return $value != $_REQUEST['id'];
//     }
// );
// Re-index array to avoid gaps in the array keys
//$_SESSION['cart_course_id'] = array_values($_SESSION['cart_course_id']);
