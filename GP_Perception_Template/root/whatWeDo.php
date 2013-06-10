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
  <img src="images/benefitsList.png" alt="benefits" title="Benefits of our services">
  <img src="images/featuredServicesTitle.png">
  <a href="#"><img src="images/secretShopperList.png" alt="secret shopper" title="Secret Shopper benefits"></a>
  <a href="#"><img src="images/mobileWebsiteList.png" alt="mobile website" title="Mobile Website benefits"></a>
  <a href="#"><img src="images/socialMediaList.png" alt="social media" title="Social Media benefits"></a>
</div>
<?php include_once ("template_pageBottom.php"); ?>
</body>
</html>