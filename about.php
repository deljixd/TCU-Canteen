<?php

session_start();

include("includes/db.php");
include("includes/header.php");
include("functions/functions.php");
include("includes/main.php");

$get_about_us = "select * from about_us";

$run_about_us = mysqli_query($con,$get_about_us);

$row_about_us = mysqli_fetch_array($run_about_us);

$about_heading = $row_about_us['about_heading'];

$about_short_desc = $row_about_us['about_short_desc'];

$about_desc = $row_about_us['about_desc'];

?>

<main class="about_container">
  <!-- HERO -->
  <div class="header user_header">
    <h1 class="heading"> <?php echo $about_heading; ?> </h1>
    <p class="lead"> <?php echo $about_short_desc; ?> </p>
  </div>

  <div class="about_desc">
    <p> <?php echo $about_desc; ?> </p>
  </div>

</main>



</div><!-- container ends -->
</div><!-- content ends -->



<?php

include("includes/footer.php");

?>

<script src="js/jquery.min.js"> </script>

<script src="js/bootstrap.min.js"></script>

</body>

</html>