<section class="payment" style="display: flex; gap: 1rem">
  <?php
  $session_email = $_SESSION['customer_email'];
  $select_customer = "select * from customers where customer_email='$session_email'";
  $run_customer = mysqli_query($con,$select_customer);
  $row_customer = mysqli_fetch_array($run_customer);
  $customer_id = $row_customer['customer_id'];
  ?>
    <h1 class="payment_link_text">
      <a href="shop.php?c_id=<?php echo $customer_id; ?>">Canteen</a>
    </h1>
  <h1 class="payment_link_text">
    <a href="order.php?c_id=<?php echo $customer_id; ?>">Payment</a>
  </h1>
  <?php
  $i = 0;
  $ip_add = getRealUserIp();
  $get_cart = "select * from cart where ip_add='$ip_add'";
  $run_cart = mysqli_query($con,$get_cart);
  while($row_cart = mysqli_fetch_array($run_cart)){
    $pro_id = $row_cart['p_id'];
    $pro_qty = $row_cart['qty'];
    $pro_price = $row_cart['p_price'];
    $get_products = "select * from products where product_id='$pro_id'";
    $run_products = mysqli_query($con,$get_products);
    $row_products = mysqli_fetch_array($run_products);
    $product_title = $row_products['product_title'];
    $i++;
  ?>
  <?php } ?>
</section><!-- box ends -->