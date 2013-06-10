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
  <img src="images/whoWeServeTitle.png" alt="Good Principles Serves">
  <img src="images/whoWeServeList.png" alt="Good Principles Industries">
  <img src="images/benefitsList.png" alt="Good Principles benefits" title="Benefits of our services">
  <img src="images/featuredServicesTitle.png" alt="Good Principles featured services">
  <a href="monthly_evaluation.php"><img src="images/monthlyEvaluationList.png" alt="Good Principles monthly evaluation" title="Monthly Evaluation benefits"></a>
  <img src="images/complementaryServicesTitle.png" alt="Good Principles Services">
  <div id="services">
    <div id="servicesLeft">
      <div>
      <img src="images/secretShoppersButton.png" alt="Good Principles Secret Shopper">
      <img src="images/socialMediaStrategyButton.png" alt="Good Principles Social Media Strategy">
      <img src="images/satisfactionSurveysButton.png" alt="Good Principles Satisfaction Surveys">
      </div>
    </div>
    <div id="servicesRight">
      <div>
      <a href="http://restaurant.goodprinciples.com" target="_blank"><img src="images/mobileWebsitesButton.png" alt="Good Principles Mobile Websites" title="Mobile Website Preview"></a>
      <a href="https://www.facebook.com/pages/Mobile-Restaurant/189013371250134?ref=br_tf"><img src="images/fbAppsButton.png" alt="Good Principles Facebook Apps" title="Facebook Apps Preview"></a>
      <img src="images/imagesDesignButton.png" alt="Good Principles Design">
      </div>
    </div>
  </div>
  <img src="images/packagesMenuButton.png" alt="Good Principles Packages">
</div>
<?php include_once ("template_pageBottom.php"); ?>
</body>
</html>