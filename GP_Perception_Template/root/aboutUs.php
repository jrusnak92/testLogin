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
  <img src="images/philosophy.png" alt="Good Principles Philosophy" title="Good Principles Philosophy">
  <img src="images/passion.png" alt="Good Principles Passion" title="Good Principles Passion">
  <div id="locationsMenu">
  	<div>
      <a href="locations.php"><img src="images/charlotteMenuButton.png" alt="Good Principles Charlotte" title="Read about our Charlotte 		territory"></a>
      <a href="locations.php"><img src="images/lasVegasMenuButton.png" alt="Good Principles Las Vegas" title="Read about our Las Vegas territory"></a>
      <a href="locations.php"><img src="images/southernCaliforniaMenuButton.png" alt="Good Principles Values" title="Read about our So Cal territory"></a>
    </div>
  </div>
  <div id="objectives">
    <div>
      <a href="mission_vision.php"><img src="images/missionMenuButton.png" alt="Good Principles Mission" title="Click to view our Mission"></a>
      <a href="mission_vision.php"><img src="images/visionMenuButton.png" alt="Good Principles Vision" title="Click to view our Vision"></a>
      <a href="mission_vision.php"><img src="images/valuesMenuButton.png" alt="Good Principles Values" title="Click to view our Values"></a>
    </div>
  </div>
</div>
<?php include_once ("template_pageBottom.php"); ?>
</body>
</html>