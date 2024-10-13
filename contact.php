<?php
session_start();
include("includes/db.php");
include("includes/header.php");
include("functions/functions.php");
include("includes/main.php");

$get_contact_us = "select * from contact_us";
$run_conatct_us = mysqli_query($con,$get_contact_us);
$row_conatct_us = mysqli_fetch_array($run_conatct_us);
$contact_heading = $row_conatct_us['contact_heading'];
$contact_desc = $row_conatct_us['contact_desc'];
$contact_email = $row_conatct_us['contact_email'];

?>

<main class="contact_container">
  <div class="header user_header">
    <h1 class="heading"><?php echo $contact_heading; ?></h1>
    <p><?php echo $contact_desc; ?></p>
  </div>
  </div>
  <form action="contact.php" method="post">
    <div class="form-group">
      <label>Name</label>
      <input type="text" class="form-control" name="name" required>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="text" class="form-control" name="email" required>
    </div>
    <div class="form-group">
      <label> Subject </label>
      <input type="text" class="form-control" name="subject" required>
    </div>
    <div class="form-group">
      <label> Message </label>
      <textarea class="form-control" name="message"></textarea>
    </div>
    <div class="form-group">
      <label> Select Enquiry Type </label>
      <select name="enquiry_type" class="form-control">
        <option>Select Enquiry Type</option>
        <?php
        $get_enquiry_types = "select * from enquiry_types";
        $run_enquiry_types = mysqli_query($con,$get_enquiry_types);
        while($row_enquiry_types = mysqli_fetch_array($run_enquiry_types)){
          $enquiry_title = $row_enquiry_types['enquiry_title'];
          echo "<option>$enquiry_title</option>";
        }
        ?>
      </select>
    </div>
    <button type="submit" name="submit" class="submit-concern-btn">
      <i class="fa fa-user-md"></i> Send Message
    </button>
  </form>
  <?php
  if(isset($_POST['submit'])){
    $sender_name = $_POST['name'];
    $sender_email = $_POST['email'];
    $sender_subject = $_POST['subject'];
    $sender_message = $_POST['message'];
    $enquiry_type = $_POST['enquiry_type'];
    $new_message = "
    <h1>This Message Has Been Sent By $sender_name</h1>
    <p><b>Sender Email :</b><br>$sender_email</p>
    <p><b>Sender Subject :</b><br>$sender_subject</p>
    <p><b>Sender Enquiry Type :</b><br>$enquiry_type</p>
    <p><b>Sender Message :</b><br>$sender_message</p>
    ";
    $headers = "From: $sender_email \r\n";
    $headers .= "Content-type: text/html\r\n";
    mail($contact_email,$sender_subject,$new_message,$headers);
    $email = $_POST['email'];
    $subject = "Welcome to my website";
    $msg = "I shall get you soon, thanks for sending us email";
    $from = "tcu_canteen@gmail.com";
    mail($email,$subject,$msg,$from);
    echo "<h2 align='center'>Your message has been sent successfully</h2>";
  }
  ?>
  </div><!-- container ends -->
  </div><!-- content ends -->
</main>
<?php
include("includes/footer.php");
?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>

</html>