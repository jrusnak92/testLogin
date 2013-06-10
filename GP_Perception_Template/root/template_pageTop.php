<?php
// It is important for any file that includes this file, to have
// check_login_status.php included at its very top.
$envelope = '<img src="images/note_dead.jpg" width="22" height="12" alt="Notes" title="This envelope is for logged in members">';
$loginLink = '<a href="login.php"><img src="images/button-logIn.png" alt="login" title="Click to login"></a>
				<a href="signup.php"><img src="images/button-register.png" alt="register" title="Click to register"></a>';
if($user_ok == true) {
$sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
$notescheck = $row[0];
$sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
    if ($numrows == 0) {
$envelope = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/note_still.jpg" width="22" height="12" alt="Notes"></a>';
    } else {
$envelope = '<a href="notifications.php" title="You have new notifications"><img src="images/note_flash.gif" width="22" height="12" alt="Notes"></a>';
}
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a> &nbsp; <a href="logout.php"><img src="images/button-logOut.png" alt="logout" title="Click to logout"></a>';
}
?>
<div id="pageTop">
  <div id="pageTopWrap">
    <div id="pageTopLogo">
      <a href="http://www.generalpolar.com/root"><img src="images/gpLogo.png" alt="good principles logo" title="Good Principles Marketing Agency">
      </a>
    </div>
    <div id="pageTopRest">
      <div id="menu1">
        <div>
          <?php echo $envelope; ?> &nbsp; &nbsp; <?php echo $loginLink; ?>
        </div>
      </div>
      <div id="menu2">
        <div>
          <a href="about.php"><img src="images/button-about.png" alt="Good Principles about" title="About"></a>
          <a href="services.php"><img src="images/button-services.png" alt="Good Principles services" title="Our Services"></a>
          <a href="pricing.php"><img src="images/button-pricing.png" alt="Good Principles pricing" title="Pricing"></a>
          <!--<a href="#">Menu_Item_1</a>
          <a href="#">Menu_Item_2</a> -->
        </div>
      </div>
    </div>
  </div>
</div>