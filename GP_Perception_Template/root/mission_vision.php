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
<title>Good Principles Social Agency</title>
<?php include_once ("icons.html"); ?>
<link rel="stylesheet" href="style/style.css">
<script src="js/main.js"></script>
</head>
<body>
<?php include_once ("template_pageTop.php"); ?>
<div id="pageMiddle">
  <img src="images/mission.png" alt="Good Principles Mission Statement" title="Good Principles Mission Statement">
  <img src="images/vision.png" alt="Good Principles Vision Statement" title="Good Principles Vision Statement">
  <img src="images/values.png" alt="Good Principles Values Statement" title="Good Principles Values Statement">
</div>
<?php include_once ("template_pageBottom.php"); ?>
</body>
</html>