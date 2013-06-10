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
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Good Principles Marketing Agency</title>
<?php include_once ("icons.html"); ?>
<link rel="stylesheet" href="style/style.css">
<script src="js/main.js"></script>
</head>
<body>
<?php include_once ("template_pageTop.php"); ?>
<div id="pageMiddle">
  <img src="images/locationsMap2.png">
  <div id="southernCalifornia">
    <div>
      <h1>Daniel Heisig</h1>
      <h2><a href="mailto:info@goodprinciples.com?subject=Good Principles Help
&body=I want you to help increase my repeat business!">Click to email Daniel</a></h2>
      <h2>Phone: (818) 383-2668</h2>
    </div>
  </div>
  <div id="lasVegas">
    <div>
      <h1>Scott Wurth</h1>
      <h2><a href="mailto:scottwurth@gmail.com?subject=Good Principles Help
&body=I want you to help increase my repeat business!">Click to email Scott</a></h2>
      <h2>Phone: (208) 871-0317</h2>
    </div>
  </div>
  <div id="charlotte">
    <div>
    </div>
  </div>
</div>
<?php include_once ("template_pageBottom.php"); ?>
</body>
</html>