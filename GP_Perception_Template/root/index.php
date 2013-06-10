<?php
include_once("php_includes/check_login_status.php");
$sql = "SELECT username FROM users WHERE activated='1' ORDER BY RAND() LIMIT 32";
$query = mysqli_query($db_conx, $sql);
$userlist = "";
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$u = $row["username"];
	$avatar = $row["avatar"];
	$profile_pic = 'user/'.$u.'/'.$avatar;
	$userlist .= '<a href="user.php?u='.$u.'" title="'.$u.'"><img src="'.$profile_pic.'" alt="'.$u.'" style="width:100px; height:100px; margin:10px;"</a>'; 
}
$sql = "SELECT COUNT(id) FROM users WHERE activated='1'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
$usercount = $row[0];
?><?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if(isset($_POST["e"])){
	// CONNECT TO THE DATABASE
	include_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = md5($_POST['p']);
	// GET USER IP ADDRESS
		$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// FORM DATA ERROR HANDLING
	if($e == "" || $p == ""){
		echo "login_failed";
		exit();
	} else {
	// END FORM DATA ERROR HANDLING
		$sql = "SELECT id, username, password FROM users WHERE email='$e' AND activated='1' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
		$db_id = $row[0];
		$db_username = $row[1];
        $db_pass_str = $row[2];
		if($p != $db_pass_str){
			echo "login_failed";
            exit();
		} else {
			// CREATE THEIR SESSIONS AND COOKIES
			$_SESSION['userid'] = $db_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['password'] = $db_pass_str;
			setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE); 
			// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
			$sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
			echo $db_username;
   			exit();
		}
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Good Principles Marketing Agency</title>
<?php include_once ("icons.html"); ?>
                                   
<meta name="description" content="Good Principles Marketing Agency">
<meta name="author" content="Good Principles Marketing Agency">
<link rel="stylesheet" href="style/style.css">
<style type="text/css">
#loginform{
margin-top:24px;	
}
#loginform > div {
margin-top: 12px;	
}
#loginform > input {
width: 200px;
padding: 3px;
background: #F3F9DD;
}
#loginbtn {
font-size:15px;
padding: 10px;
}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function emptyElement(x){
_(x).innerHTML = "";
}
function login(){
	var e = _("email").value;
	var p = _("password").value;
	if(e == "" || p == ""){
		_("status").innerHTML = "Fill out all of the form data";
	} else {
		_("loginbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "login.php");
		ajax.onreadystatechange = function() {
		   if(ajaxReturn(ajax) == true) {
			   if(ajax.responseText == "login_failed"){
					_("status").innerHTML = "Login unsuccessful, please try again.";
					_("loginbtn").style.display = "block";
				} else {
					window.location = "user.php?u="+ajax.responseText;
				}
		   }
		}
		ajax.send("e="+e+"&p="+p);
	}
}
</script>
</head>
<body>
<?php include_once ("template_pageTop.php"); ?>
<div id="pageMiddle">
  <img src="images/gpAgencyTitle.png" alt="Good Principles Marketing Agency">
  <h1>MEMBER LOGIN</h1>
  <!-- LOGIN FORM -->
  <form id="loginform" onsubmit="return false;">
    <div>Email Address:</div>
    <input type="text" id="email" onfocus="emptyElement('status')" maxlength="88">
    <div>Password:</div>
    <input type="password" id="password" onfocus="emptyElement('status')" maxlength="100">
    <br /><br />
    <button id="loginbtn" onclick="login()">Login</button> 
    <p id="status"></p>
    <a href="forgot_pass.php">Forgot Your Password?</a>
  </form>
  <!-- LOGIN FORM -->
  <h1><a href="signup.php">Click HERE to Join</a></h1>
  <div id="mainMenu">
    <div id="mainMenuLeft">
      <div>
      <a href="about.php"><img src="images/aboutMenuButton.png" alt="Good Principles About"></a>
      <a href="services.php"><img src="images/pricingMenuButton.png" alt="Good Principles Pricing"></a>
      </div>
    </div>
    <div id="mainMenuRight">
      <div>
      <a href="#"><img src="images/servicesMenuButton.png" alt="Good Principles Services"></a>
      <a href="locations.php"><img src="images/contactMenuButton.png" alt="Good Principles Contact"></a>
      </div>
    </div>
  </div>
  <br />
  <img src="images/stay-connected.png" alt="Good Principles Social Media">
  <div id="socialMedia">
  <div>
  <a href="http://www.linkedin.com/company/good-principles" target="_blank"><img src="images/linkedinSphere.png" alt="Good Principles LinkedIn" title="Go to our LinkedIn Page"></a>
  <a href="https://www.facebook.com/pages/Good-Principles/453918438015382" target="_blank"><img src="images/facebookSphere.png" alt="Good Principles Facebook" title="Go to our Facebook Page"></a>
  <a href="http://www.youtube.com/goodprinciplesmedia" target="_blank"><img src="images/youtubeSphere.png" alt="Good Principles YouTube" title="Go to our YouTube Channel"></a>
  </div>
  </div>
  <h3>Total Members: <?php echo $usercount; ?></h3>
  <h3>A few of our secret shoppers and their beautiful avatars</h3>
  <?php echo $userlist; ?>
</div>
<?php include_once ("template_pageBottom.php"); ?>
</body>
</html>