<?php

session_start();

include("includes/db.php");
include("includes/header.php");
include("functions/functions.php");
include("includes/main.php");

?>

<?php

$product_id = @$_GET['pro_id'];

$get_product = "select * from products where product_url='$product_id'";
$run_product = mysqli_query($con,$get_product);
$check_product = mysqli_num_rows($run_product);

if($check_product == 0){
    echo "<script> window.open('index.php','_self') </script>";
}
else {
    $row_product = mysqli_fetch_array($run_product);

    $p_cat_id = $row_product['p_cat_id'];
    $pro_id = $row_product['product_id'];
    $pro_title = $row_product['product_title'];
    $pro_price = $row_product['product_price'];
    $pro_desc = $row_product['product_desc'];
    $pro_img1 = $row_product['product_img1'];
    $pro_img2 = $row_product['product_img2'];
    $pro_img3 = $row_product['product_img3'];
    $pro_manufacturer_id = $row_product['manufacturer_id'];
    $pro_label = $row_product['product_label'];
    $pro_psp_price = $row_product['product_psp_price'];
    $status = $row_product['status'];
    $pro_url = $row_product['product_url'];

    if($pro_label == "") {
        $product_label = "";
    } else {
        $product_label = "
            <a class='label sale' href='#' style='color:black;'>
                <div class='thelabel'>$pro_label</div>
                <div class='label-background'></div>
            </a>
        ";
    }

    $get_p_cat = "select * from product_categories where p_cat_id='$p_cat_id'";
    $run_p_cat = mysqli_query($con,$get_p_cat);
    $row_p_cat = mysqli_fetch_array($run_p_cat);
    //$p_cat_title = $row_p_cat['p_cat_title'];
?>

<main>
  <div class="header" style="width:100%; text-align: center;">
    <h1><?php echo $pro_title; ?></h1>
  </div>
</main>

<div id="content">
  <div class="container">
    <div class="product_detail_container">
      <div class="row" id="productMain">
        <div class="">
          <div id="mainImage">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
              </ol>

              <div class="carousel-inner">
                <div class="item active">
                  <center>
                    <img src="./admin/product_images/food/<?php echo $pro_img1; ?>" class="img-responsive">
                  </center>
                </div>

                <div class="item">
                  <center>
                    <img src="./admin/product_images/food/<?php echo $pro_img2; ?>" class="img-responsive">
                  </center>
                </div>

                <div class="item">
                  <center>
                    <img src="./admin/product_images/food/<?php echo $pro_img3; ?>" class="img-responsive">
                  </center>
                </div>
              </div>

              <a href="#myCarousel" class="left carousel-control" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"> </span>
                <span class="sr-only"> Previous </span>
              </a>

              <a class="right carousel-control" href="#myCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"> </span>
                <span class="sr-only"> Next </span>
              </a>
            </div>

            <div class="row" id="thumbs">
              <div class="col-xs-4">
                <a href="#" class="thumb">
                  <img src="./admin/product_images/food/<?php echo $pro_img1; ?>" class="img-responsive">
                </a>
              </div>
              <div class="col-xs-4">
                <a href="#" class="thumb">
                  <img src="./admin/product_images/food/<?php echo $pro_img2; ?>" class="img-responsive">
                </a>
              </div>
              <div class="col-xs-4">
                <a href="#" class="thumb">
                  <img src="./admin/product_images/food/<?php echo $pro_img3; ?>" class="img-responsive">
                </a>
              </div>
            </div>

            <?php echo $product_label; ?>
          </div>
        </div>

        <div class="product_form_container">
          <div class="product_form">
            <h1 class="product_title"> <?php echo $pro_title; ?> </h1>

            <?php

            if(isset($_POST['add_cart'])){
              $ip_add = getRealUserIp();
              $p_id = $pro_id;
              $product_name = $_POST['product_name'];
              $product_qty = $_POST['product_qty'];
              $product_size = $_POST['product_size'];

              if($product_qty == "Select quantity" || $product_size == "Select a Size") {
                  echo "<script>alert('Please select quantity and size.')</script>";
              } else {
                // Check if the same product with the same size and quantity exists
                $check_product = "select * from cart where ip_add='$ip_add' AND p_id='$p_id' AND size='$product_size' AND qty='$product_qty'";
                $run_check = mysqli_query($con,$check_product);

                if(mysqli_num_rows($run_check) > 0){
                    // Product with the same size and quantity already exists, update quantity
                    $row = mysqli_fetch_array($run_check);
                    $new_qty = $row['qty'] + $product_qty;
                    $update_qty = "update cart set qty='$new_qty' where p_id='$p_id' AND ip_add='$ip_add' AND size='$product_size'";
                    $run_update_qty = mysqli_query($con, $update_qty);
                    echo "<script>window.open('$pro_url', '_self')</script>";
                } else {
                    // No duplicate entry found, proceed with insertion
                    $get_price = "select * from products where product_id='$p_id'";
                    $run_price = mysqli_query($con,$get_price);
                    $row_price = mysqli_fetch_array($run_price);
                    $pro_price = $row_price['product_price'];
                    $pro_psp_price = $row_price['product_psp_price'];
                    $pro_label = $row_price['product_label'];

                    if($pro_label == "Sale" or $pro_label == "Gift"){
                        $product_price = $pro_psp_price;
                    } else {
                        $product_price = $pro_price;
                    }

                    $query = "insert into cart (p_id,ip_add,p_name,qty,p_price, p_manufacturer,size) values ('$p_id','$ip_add','$product_name','$product_qty','$product_price', '$pro_manufacturer_id','$product_size')";
                    $run_query = mysqli_query($con,$query);

                    echo "<script>window.open('$pro_url','_self')</script>";
                }
              }
            }
                            
            ?>

            <form action="" method="post" class="form-horizontal">
              <?php
                            if($status == "product"){
                                if($pro_label == "Sale" or $pro_label == "Gift"){
                                    echo "<p class='price'><del> ₱$pro_price </del>  ₱$pro_psp_price</p>";
                                } else {
                                    echo "<p class='price'>₱$pro_price</p>";
                                }
                            } else {
                                if($pro_label == "Sale" or $pro_label == "Gift"){
                                    echo "<p class='price'>Bundle Price : <del> ₱$pro_price </del><br>Bundle sale Price : ₱$pro_psp_price</p>";
                                } else {
                                    echo "<p class='price'>Bundle Price : ₱$pro_price</p>";
                                }
                            }
                            ?>

              <?php if($status == "product"){ ?>
              <div class="field_form">
                <label>Product Quantity </label>
                <select name="product_qty">
                  <option>Select quantity</option>
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>

              <!-- <?php 
                if($p_cat_id == 8  || $p_cat_id == 12 || $p_cat_id == 13){
              ?>
              <div class="field_form">
                <label>Product Size</label>
                <select name="product_size">
                  <option>Select a Size</option>
                  <option>6</option>
                  <option>7</option>
                  <option>8</option>
                  <option>9</option>
                  <option>10</option>
                  <option>11</option>
                  <option>12</option>
                  <option>36</option>
                  <option>37</option>
                  <option>38</option>
                  <option>39</option>
                  <option>40</option>
                  <option>41</option>
                  <option>42</option>
                  <option>43</option>
                  <option>44</option>
                  <option>45</option>
                </select>
              </div>
              <?php } else { ?>
              <div class="field_form">
                <label>Product Size</label>
                <select name="product_size">
                  <option>Select a Size</option>
                  <option>Small</option>
                  <option>Medium</option>
                  <option>Large</option>
                </select>
              </div>
              <?php } ?> -->

              <?php } else { ?>
              <div class="field_form">
                <label class="col-md-5 control-label">Bundle Quantity </label>
                <select name="product_qty" class="form-control">
                  <option>Select quantity</option>
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>

              <div class="field_form">
                <label class="col-md-5 control-label">Bundle Size</label>
                <select name="product_size" class="form-control">
                  <option>Select a Size</option>
                  <option>Small</option>
                  <option>Medium</option>
                  <option>Large</option>
                </select>
              </div>
              <?php } ?>

              <div class="field_form" style="display: none;">
                <label class="col-md-5 control-label">Product Name</label>
                <input name="product_name" class="form-control" value="<?php echo $pro_title; ?>"/>
              </div>

              <div class="field_form" style="display: none;">
                <label class="col-md-5 control-label">Product Manufacturer</label>
                <input name="manufacturer_id" class="form-control" value="<?php echo $pro_manufacturer_id; ?>"/>
              </div>

              <div class="buttons">
                <button class="add_cart" type="submit" name="add_cart">
                  <i class="fa fa-shopping-cart"></i> Add to Cart
                </button>
                <!-- <button class="add_wishlist" type="submit" name="add_wishlist">
                  <i class="fa fa-heart"></i>
                </button> -->

                <?php
                                if(isset($_POST['add_wishlist'])){
                                    if(!isset($_SESSION['customer_email'])){
                                        echo "<script>alert('Login or create an account to add in Wishlist')</script>";
                                        echo "<script>window.open('checkout.php','_self')</script>";
                                    } else {
                                        $customer_session = $_SESSION['customer_email'];
                                        $get_customer = "select * from customers where customer_email='$customer_session'";
                                        $run_customer = mysqli_query($con,$get_customer);
                                        $row_customer = mysqli_fetch_array($run_customer);
                                        $customer_id = $row_customer['customer_id'];

                                        $select_wishlist = "select * from wishlist where customer_id='$customer_id' AND product_id='$pro_id'";
                                        $run_wishlist = mysqli_query($con,$select_wishlist);
                                        $check_wishlist = mysqli_num_rows($run_wishlist);

                                        if($check_wishlist == 1){
                                            echo "<script>alert('This product has been already added in wishlist')</script>";
                                            echo "<script>window.open('$pro_url','_self')</script>";
                                        } else {
                                            $insert_wishlist = "insert into wishlist (customer_id,product_id) values ('$customer_id','$pro_id')";
                                            $run_wishlist = mysqli_query($con,$insert_wishlist);

                                            if($run_wishlist){
                                                echo "<script> alert('Product has added into wishlist') </script>";
                                                echo "<script>window.open('$pro_url','_self')</script>";
                                            }
                                        }
                                    }
                                }
                                ?>
              </div>
            </form>
          </div>

          <p class='product_desc'><?php echo $pro_desc; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>

<script src="js/jquery.min.js"> </script>
<script src="js/bootstrap.min.js"></script>
</body>

</html>

<?php } ?>