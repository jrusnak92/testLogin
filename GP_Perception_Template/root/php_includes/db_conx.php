<?php
$db_conx = mysqli_connect("mysteryShop.db.10484197.hostedresource.com", "mysteryShop", "GP13spring#", "mysteryShop");
//Evaluate the cnnection
if (mysqli_connect_errno()) {
	echo mysqli_connect_error();
	exit();
}
?>