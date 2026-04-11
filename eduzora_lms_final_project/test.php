<?php
echo password_hash("1234", PASSWORD_DEFAULT);
?>



// All Products Page
// Add to cart Button Code:
<form action="" method="post">
<input type="hidden" name="product_id" value="<?php echo safe_data($row['product_id']); ?>">
<input type="hidden" name="product_current_price" value="<?php echo safe_data($row['product_current_price']); ?>">
<input type="hidden" name="product_name" value="<?php echo safe_data($row['product_name']); ?>">
<input type="hidden" name="product_featured_photo" value="<?php echo safe_data($row['product_featured_photo']); ?>">
<input type="hidden" name="product_qty" value="1">
<input type="submit" value="Add to Cart" name="form_add_to_cart">
</form>

<?php
if(isset($_POST['form_add_to_cart'])) 
{
	if(isset($_SESSION['cart_product_id']))
    {
        $arr_cart_product_id = array();
        $arr_cart_product_qty = array();

        $i=0;
        foreach($_SESSION['cart_product_id'] as $key => $value) 
        {
            $i++;
            $arr_cart_product_id[$i] = $value;
        }

        $i=0;
        foreach($_SESSION['cart_product_qty'] as $key => $value) 
        {
            $i++;
            $arr_cart_product_qty[$i] = $value;
        }

        if(in_array($_POST['product_id'],$arr_cart_product_id))
        {
           $error_message1 = 'This product is already added to the shopping cart.';
        } 
        else 
        {
            $i=0;
            foreach($_SESSION['cart_product_id'] as $key => $res) 
            {
                $i++;
            }
            $new_key = $i+1;          

            $_SESSION['cart_product_id'][$new_key] = sanitize_int($_POST['product_id']);
            $_SESSION['cart_product_qty'][$new_key] = sanitize_int($_POST['product_qty']);

            $_SESSION['success_message1'] = 'Product is added to the cart successfully!';
            
            $q = $pdo->prepare("SELECT * FROM tbl_page WHERE page_layout=?");
			$q->execute(['Product Page Layout']);
			$tot = $q->rowCount();
			$result = $q->fetchAll();
			foreach ($result as $row){
				$page_slug = $row['page_slug'];
			}
			if(!$tot) {
				header('location: '.BASE_URL);
			}
            header('location: '.BASE_URL.PAGE.$page_slug);
            exit;
        }
        
    }
    else
    {
        $_SESSION['cart_product_id'][1] = sanitize_int($_POST['product_id']);
        $_SESSION['cart_product_qty'][1] = sanitize_int($_POST['product_qty']);

        $_SESSION['success_message1'] = 'Product is added to the cart successfully!';
        $q = $pdo->prepare("SELECT * FROM tbl_page WHERE page_layout=?");
		$q->execute(['Product Page Layout']);
		$tot = $q->rowCount();
		$result = $q->fetchAll();
		foreach ($result as $row){
			$page_slug = $row['page_slug'];
		}
		if(!$tot) {
			header('location: '.BASE_URL);
		}
        header('location: '.BASE_URL.PAGE.$page_slug);
        exit;
    }
}
?>




// Cart Page
<tbody>
<?php
$arr_cart_product_id = array();
$arr_cart_product_qty = array();

$i=0;
foreach($_SESSION['cart_product_id'] as $value)
{
    $i++;
    $arr_cart_product_id[$i] = $value;
}

$i=0;
foreach($_SESSION['cart_product_qty'] as $value)
{
    $i++;
    $arr_cart_product_qty[$i] = $value;
}

$tot1 = 0;
for($i=1;$i<=count($arr_cart_product_id);$i++)
{
    $q = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id=?");
    $q->execute([$arr_cart_product_id[$i]]);
    $res = $q->fetchAll();
    foreach ($res as $row) {
        $product_name = $row['product_name'];
        $product_slug = $row['product_slug'];
        $product_current_price = $row['product_current_price'];
        $product_featured_photo = $row['product_featured_photo'];
    }
    ?>
    <input type="hidden" name="product_id[]" value="<?php echo safe_data($arr_cart_product_id[$i]); ?>">
    <input type="hidden" name="product_name[]" value="<?php echo safe_data($product_name); ?>">
    <tr>
        <td><?php echo safe_data($i); ?></td>
        <td class="align-middle"><img src="<?php echo BASE_URL; ?>uploads/<?php echo safe_data($product_featured_photo); ?>"></td>
        <td class="align-middle">
            <a href="<?php echo BASE_URL.PRODUCT; ?><?php echo safe_data($product_slug); ?>"><?php echo safe_data($product_name); ?></a>
        </td>
        <td class="align-middle">$<?php echo safe_data($product_current_price); ?></td>
        <td class="align-middle">
            <input type="number" class="form-control" name="product_qty[]" step="1" min="1" max="" pattern="" pattern="[0-9]*" inputmode="numeric" value="<?php echo safe_data($arr_cart_product_qty[$i]); ?>">
        </td>
        <td class="align-middle">
            <?php $subtotal = $product_current_price*$arr_cart_product_qty[$i]; ?>
            $<?php echo safe_data($subtotal); ?>
        </td>
        <td class="align-middle">
            <a href="cart-item-delete.php?id=<?php echo safe_data($arr_cart_product_id[$i]); ?>" class="btn btn-xs btn-danger" onClick="return confirm('Are you sure to delete this item from the cart?');"><i class="fa fa-trash"></i></a>
        </td>
    </tr>
    <?php
    $tot1 = $tot1+$subtotal;
}
?>
</tbody>
<input type="submit" value="Update Cart" class="btn btn-info btn-arf" name="form1">






// Update Cart
<?php
if(isset($_POST['form1'])) 
{
    $i = 0;
    $q = $pdo->prepare("SELECT * FROM tbl_product");
    $q->execute();
    $result = $q->fetchAll();
    foreach ($result as $row) {
        $i++;
        $table_product_id[$i] = $row['product_id'];
        $table_product_stock[$i] = $row['product_stock'];
    }

    $arr1 = array();
    $arr2 = array();
    $arr3 = array();

    $i=0;
    foreach($_POST['product_id'] as $val) {
        $i++;
        $arr1[$i] = $val;
    }
    $i=0;
    foreach($_POST['product_qty'] as $val) {
        $i++;
        $arr2[$i] = $val;
    }
    $i=0;
    foreach($_POST['product_name'] as $val) {
        $i++;
        $arr3[$i] = $val;
    }
    
    $allow_update = 1;
    for($i=1;$i<=count($arr1);$i++) 
    {
        for($j=1;$j<=count($table_product_id);$j++) 
        {
            if($arr1[$i] == $table_product_id[$j]) 
            {
                $temp_index = $j;
                break;
            }
        }
        if($table_product_stock[$temp_index] < $arr2[$i]) 
        {
        	$allow_update = 0;
            $error_message .= '"'.$arr2[$i].'" items are not available for "'.$arr3[$i].'"\n';
        } 
        else 
        {
            $_SESSION['cart_product_qty'][$i] = $arr2[$i];
        }
    }
    
    
    if($allow_update == 0) 
    {
    	echo "<script>Swal.fire({icon: 'error',title: 'Error',html: '".$error_message."'})</script>";
    }
    else
    {
    	echo "<script>Swal.fire({icon: 'success',title: 'Success',html: 'All Items Quantity Update is Successful!'})</script>";
    }
}
?>


// Delete from cart
$i=0;
foreach($_SESSION['cart_product_id'] as $value) {
    $i++;
    $arr_cart_product_id[$i] = $value;
}

$i=0;
foreach($_SESSION['cart_product_qty'] as $value) {
    $i++;
    $arr_cart_product_qty[$i] = $value;
}

unset($_SESSION['cart_product_id']);
unset($_SESSION['cart_product_qty']);


$k=1;
for($i=1;$i<=count($arr_cart_product_id);$i++) 
{
    if($arr_cart_product_id[$i] == $_REQUEST['id']) 
    {
        continue;
    }
    else
    {
        $_SESSION['cart_product_id'][$k] = $arr_cart_product_id[$i];
        $_SESSION['cart_product_qty'][$k] = $arr_cart_product_qty[$i];
        $k++;
    }
}