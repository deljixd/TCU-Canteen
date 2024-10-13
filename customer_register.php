<?php

session_start();

include("includes/db.php");
include("includes/header.php");
include("functions/functions.php");
include("includes/main.php");

if (isset($_POST['register'])) {
    $c_name = $_POST['c_name'];
    $c_email = $_POST['c_email'];
    $c_pass = password_hash($_POST['c_pass'], PASSWORD_DEFAULT);
    $c_contact = $_POST['c_contact'];
    $c_ip = getRealUserIp();
    $c_image = "default_image.png";  // Default image value
    $customer_confirm_code = ''; // Default value for customer_confirm_code

    // Check if contact number is exactly 11 digits
    if (strlen($c_contact) != 11) {
        echo "<script>alert('Contact number must be exactly 11 digits')</script>";
        // echo "<script>window.location.href = 'customer_register.php';</script>";
        echo "<script>window.location.href = 'customer_register.php?c_name=".urlencode($c_name)."&c_email=".urlencode($c_email)."';</script>";
        exit();
    }
    
     // Validate email format
    $valid_domains = ['yahoo.com', 'mail.com', 'gmail.com'];
    $email_parts = explode('@', $c_email);
    $domain = array_pop($email_parts);

    if (!filter_var($c_email, FILTER_VALIDATE_EMAIL) || !in_array($domain, $valid_domains)) {
        echo "<script>alert('Invalid email format.')</script>";
        echo "<script>window.location.href = 'customer_register.php?c_name=".urlencode($c_name)."&c_email=".urlencode($c_email)."';</script>";
        exit();
    }

    // Check if email is already registered
    $get_email = "SELECT * FROM customers WHERE customer_email='$c_email'";
    $run_email = mysqli_query($con, $get_email);
    $check_email = mysqli_num_rows($run_email);

    if ($check_email == 1) {
        echo "<script>alert('This email is already registered, try another one')</script>";
        echo "<script>window.location.href = 'customer_register.php?c_name=".urlencode($c_name)."&c_email=".urlencode($c_email)."';</script>";
        exit();
    }

    // Check if contact number is already registered
    $get_contact = "SELECT * FROM customers WHERE customer_contact='$c_contact'";
    $run_contact = mysqli_query($con, $get_contact);
    $check_contact = mysqli_num_rows($run_contact);

    if ($check_contact == 1) {
        echo "<script>alert('This contact number is already registered, try another one')</script>";
        echo "<script>window.location.href = 'customer_register.php?c_name=".urlencode($c_name)."&c_email=".urlencode($c_email)."';</script>";
        exit();
    }
    
    // Insert customer into the database
    $insert_customer = "INSERT INTO customers (customer_name, customer_email, customer_pass, customer_contact, customer_image, customer_ip, customer_confirm_code) VALUES ('$c_name', '$c_email', '$c_pass', '$c_contact', '$c_image', '$c_ip', '$customer_confirm_code')";

    $run_customer = mysqli_query($con, $insert_customer);

    if ($run_customer) {
        $_SESSION['customer_email'] = $c_email;
        echo "<script>alert('You have been registered successfully')</script>";
        echo "<script>window.open('checkout.php', '_self')</script>";
    } else {
        echo "<script>alert('Registration failed, please try again')</script>";
    }
}
?>

<!-- MAIN -->
<main>
  <!-- HERO -->
  <div class="header" style="width:100%; text-alignment: center;">
    <h1>Create your account</h1>
  </div>
</main>

<div id="content">
  <!-- content Starts -->
  <div class="container register_container">
    <!-- container ontStarts -->
    <form action="customer_register.php" method="post" enctype="multipart/form-data">
      <!-- form Starts -->
      <div class="form-group">
        <!-- form-group Starts -->
        <label>Customer Name and Student Number</label>
        <input type="text" class="form-control" name="c_name" value="<?php if(isset($_GET['c_name'])) echo htmlspecialchars($_GET['c_name']); ?>" required>
      </div><!-- form-group ends -->

      <div class="form-group">
        <!-- form-group Starts -->
        <label> Customer Email</label>
        <input type="text" class="form-control" name="c_email" value="<?php if(isset($_GET['c_email'])) echo htmlspecialchars($_GET['c_email']); ?>" required>
      </div><!-- form-group ends -->

      <div class="form-group">
        <!-- form-group Starts -->
        <label> Customer Password </label>
        <div class="input-group">
          <!-- input-group Starts -->
          <span class="input-group-addon">
            <!-- input-group-addon Starts -->
            <i class="fa fa-check tick1"> </i>
            <i class="fa fa-times cross1"> </i>
          </span><!-- input-group-addon ends -->
          <input type="password" class="form-control" id="pass" name="c_pass" required>
          <span class="input-group-addon">
            <!-- input-group-addon Starts -->
            <div id="meter_wrapper">
              <!-- meter_wrapper Starts -->
              <span id="pass_type"> </span>
              <div id="meter"> </div>
            </div><!-- meter_wrapper ends -->
          </span><!-- input-group-addon ends -->
        </div><!-- input-group ends -->
      </div><!-- form-group ends -->

      <div class="form-group">
        <!-- form-group Starts -->
        <label> Confirm Password </label>
        <div class="input-group">
          <!-- input-group Starts -->
          <span class="input-group-addon">
            <!-- input-group-addon Starts -->
            <i class="fa fa-check tick2"> </i>
            <i class="fa fa-times cross2"> </i>
          </span><!-- input-group-addon ends -->
          <input type="password" class="form-control confirm" id="con_pass" required>
        </div><!-- input-group ends -->
      </div><!-- form-group ends -->

      <div class="form-group">
        <label> Customer Contact </label>
        <input type="text" class="form-control" name="c_contact" pattern="\d{11}" title="Contact number must be 11 digits" value="<?php if(isset($_POST['c_contact'])) echo $_POST['c_contact']; ?>" required>
      </div>

      <div class="text-center">
        <!-- text-center Starts -->
        <button type="submit" name="register" class="btn btn-primary">
          <i class="fa fa-user-md"></i> Register
        </button>
      </div><!-- text-center ends -->
    </form><!-- form ends -->
  </div><!-- container ends -->
</div><!-- content ends -->

<?php

include("includes/footer.php");

?>

<script src="js/jquery.min.js"> </script>
<script src="js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
  $('.tick1').hide();
  $('.cross1').hide();
  $('.tick2').hide();
  $('.cross2').hide();

  $('.confirm').focusout(function() {
    var password = $('#pass').val();
    var confirmPassword = $('#con_pass').val();
    if (password == confirmPassword) {
      $('.tick1').show();
      $('.cross1').hide();
      $('.tick2').show();
      $('.cross2').hide();
    } else {
      $('.tick1').hide();
      $('.cross1').show();
      $('.tick2').hide();
      $('.cross2').show();
    }
  });
});

$(document).ready(function() {
  $("#pass").keyup(function() {
    check_pass();
  });
});

function check_pass() {
  var val = document.getElementById("pass").value;
  var meter = document.getElementById("meter");
  var no = 0;
  if (val != "") {
    // If the password length is less than or equal to 6
    if (val.length <= 6) no = 1;
    // If the password length is greater than 6 and contain any lowercase alphabet or any number or any special character
    if (val.length > 6 && (val.match(/[a-z]/) || val.match(/\d+/) || val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)))
      no = 2;
    // If the password length is greater than 6 and contain alphabet,number,special character respectively
    if (val.length > 6 && ((val.match(/[a-z]/) && val.match(/\d+/)) || (val.match(/\d+/) && val.match(
        /.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) || (val.match(/[a-z]/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))))
      no = 3;
    // If the password length is greater than 6 and must contain alphabets,numbers and special characters
    if (val.length > 6 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) no = 4;

    if (no == 1) {
      $("#meter").animate({ width: '50px' }, 300);
      meter.style.backgroundColor = "red";
      document.getElementById("pass_type").innerHTML = "Very Weak";
    }

    if (no == 2) {
      $("#meter").animate({ width: '100px' }, 300);
      meter.style.backgroundColor = "#F5BCA9";
      document.getElementById("pass_type").innerHTML = "Weak";
    }

    if (no == 3) {
      $("#meter").animate({ width: '150px' }, 300);
      meter.style.backgroundColor = "#FF8000";
      document.getElementById("pass_type").innerHTML = "Good";
    }

    if (no == 4) {
      $("#meter").animate({ width: '200px' }, 300);
      meter.style.backgroundColor = "#00FF40";
      document.getElementById("pass_type").innerHTML = "Strong";
    }
  } else {
    meter.style.backgroundColor = "";
    document.getElementById("pass_type").innerHTML = "";
  }
}
</script>

</body>
</html>
