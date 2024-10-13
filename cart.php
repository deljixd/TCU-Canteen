<?php

session_start();

include("includes/db.php");
include("includes/header.php");
include("functions/functions.php");
include("includes/main.php");

?>


<!-- MAIN -->
<main>
  <div class="header" style="width:100%; text-alignment: center;">
    <h1>Your Cart</h1>
  </div>
</main>

<div id="content">
  <div class="container cart_container">
    <div class="cart" id="cart">
      <form action="cart.php" method="post" enctype="multipart/form-data">
        <h1>Shopping Cart</h1>

        <?php
                $ip_add = getRealUserIp();
                $select_cart = "select * from cart where ip_add='$ip_add'";
                $run_cart = mysqli_query($con, $select_cart);
                $count = mysqli_num_rows($run_cart);
                ?>

        <table class="table">
          <thead>
            <tr>
              <th colspan="2">Product</th>
              <th>Quantity</th>
              <th>Unit Price</th>
              <!-- <th>Size</th> -->
              <th colspan="1">Delete</th>
              <th colspan="2">Sub Total</th>
            </tr>
          </thead>

          <tbody>
            <?php
                        $total = 0;
                        $ip_add = getRealUserIp();
                        $select_cart = "select * from cart where ip_add='$ip_add'";
                        $run_cart = mysqli_query($con, $select_cart);
                        $count = mysqli_num_rows($run_cart);

                        while($row_cart = mysqli_fetch_array($run_cart)){
                            $pro_id = $row_cart['p_id'];
                            $pro_size = $row_cart['size'];
                            $pro_qty = $row_cart['qty'];
                            $only_price = $row_cart['p_price'];

                            $get_products = "select * from products where product_id='$pro_id'";
                            $run_products = mysqli_query($con, $get_products);
                            while($row_products = mysqli_fetch_array($run_products)){
                                $product_title = $row_products['product_title'];
                                $product_img1 = $row_products['product_img1'];
                                $sub_total = $only_price * $pro_qty;
                                $_SESSION['pro_qty'] = $pro_qty;
                                $total += $sub_total;
                                ?>
            <tr>
              <td><img src="./admin/product_images/food/<?php echo $product_img1; ?>"></td>
              <td><a href="#" class='product_title'><?php echo $product_title; ?></a></td>
              <td><input type="text" name="quantity" value="<?php echo $_SESSION['pro_qty']; ?>"
                  data-product_id="<?php echo $pro_id; ?>" class="quantity form-control"></td>
              <td>₱<?php echo $only_price; ?>.00</td>
              <!-- <td><?php echo $pro_size; ?></td> -->
              <td><input type="checkbox" name="remove[]" value="<?php echo $pro_id; ?>"></td>
              <td>₱<?php echo $sub_total; ?>.00</td>
            </tr>
            <?php 
                            } 
                        }
                        ?>
          </tbody>

          <tfoot>
            <tr>
              <th colspan="5">Total</th>
              <th colspan="2">₱<?php echo $total; ?>.00</th>
            </tr>
          </tfoot>
        </table>

        <div class="box-footer">
          <div class="pull-left">
            <a href="shop.php" class="btn btn-default continue_btn">Continue Ordering</a>
          </div>
          <div class="pull-right">
            <button class="btn updata_btn" type="submit" name="update" value="Update Cart" style="color: #fff;">Update Cart</button>
            <a href="checkout.php" class="btn checkout_btn" style="color: #fff;">Buy</a>
          </div>
        </div>
      </form>

      <?php
            function update_cart(){
                global $con;
                if(isset($_POST['update'])){
                    foreach($_POST['remove'] as $remove_id){
                        $delete_product = "delete from cart where p_id='$remove_id'";
                        $run_delete = mysqli_query($con, $delete_product);
                        if($run_delete){
                            echo "<script>window.open('cart.php','_self')</script>";
                        }
                    }
                }
            }
            echo @$up_cart = update_cart();
            ?>
    </div>

    <div class="order_summary_container" style="width: 40rem">
      <div class="box-header">
        <h3>Order Summary</h3>
      </div>
      <div class="table-responsive">
        <table class="table">
          <tbody>
            <tr>
              <td>Order Subtotal</td>
              <th>₱<?php echo $total; ?>.00</th>
            </tr>
            <tr class="total">
              <td>Total</td>
              <th>₱<?php echo $total; ?>.00</th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<?php

include("includes/footer.php");

?>

<script src="js/jquery.min.js"> </script>

<script src="js/bootstrap.min.js"></script>

<script>
$(document).ready(function(data) {
  $(document).on('keyup', '.quantity', function() {
    var id = $(this).data("product_id");
    var quantity = $(this).val();

    if (quantity != '') {
      $.ajax({
        url: "change.php",
        method: "POST",
        data: {
          id: id,
          quantity: quantity
        },
        success: function(data) {
          $("body").load('cart_body.php');
        }
      });
    }
  });
});
</script>
<!-- <script>
$(document).ready(function(data) {

  $(document).on('keyup', '.quantity', function() {

    var id = $(this).data("product_id");

    var quantity = $(this).val();

    if (quantity != '') {

      $.ajax({

        url: "change.php",

        method: "POST",

        data: {
          id: id,
          quantity: quantity
        },

        success: function(data) {

          $("body").load('cart_body.php');

        }




      });


    }




  });




}); <
/script> 
-->

</body>

</html>