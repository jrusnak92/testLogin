<?php
session_start();
// If user is logged in, header them away
if(isset($_SESSION["username"])){
	header("location: message.php?msg=NO to that weenis");
		exit();
}
?><?php
// Ajax calls this NAME CHECK code to execute
if(isset($_POST["usernamecheck"])){
	include_once("php_includes/db_conx.php");
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
	$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
		$query = mysqli_query($db_conx, $sql); 
		$uname_check = mysqli_num_rows($query);
		if (strlen($username) < 3 || strlen($username) > 16) {
	   echo '<strong style="color:#F00;">3 - 16 characters please</strong>';
	   exit();
		}
	if (is_numeric($username[0])) {
	   echo '<strong style="color:#F00;">Usernames must begin with a letter</strong>';
	   exit();
		}
		if ($uname_check < 1) {
	   echo '<strong style="color:#009900;">' . $username . ' is OK</strong>';
	   exit();
		} else {
	   echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
	   exit();
		}
}
?><?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){
	// CONNECT TO THE DATABASE
	include_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = $_POST['p'];
	$g = preg_replace('#[^a-z]#', '', $_POST['g']);
	$c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
	// GET USER IP ADDRESS
		$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
		$query = mysqli_query($db_conx, $sql); 
	$u_check = mysqli_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
		$query = mysqli_query($db_conx, $sql); 
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($u == "" || $e == "" || $p == "" || $g == "" || $c == ""){
	echo "The form submission is missing values.";
			exit();
	} else if ($u_check > 0){ 
			echo "The username you entered is already taken";
			exit();
	} else if ($e_check > 0){ 
			echo "That email address is already in use in the system";
			exit();
	} else if (strlen($u) < 3 || strlen($u) > 16) {
			echo "Username must be between 3 and 16 characters";
			exit(); 
		} else if (is_numeric($u[0])) {
			echo 'Username cannot begin with a number';
			exit();
		} else {
	// END FORM DATA ERROR HANDLING
	   // Begin Insertion of data into the database
	// Hash the password and apply your own mysterious unique salt
	$p_hash = md5($p);
	// Add user info into the database table for the main site table
	$sql = "INSERT INTO users (username, email, password, gender, country, ip, signup, lastlogin, notescheck)       
		   VALUES('$u','$e','$p_hash','$g','$c','$ip',now(),now(),now())";
	$query = mysqli_query($db_conx, $sql); 
	$uid = mysqli_insert_id($db_conx);
	// Establish their row in the useroptions table
	$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
	$query = mysqli_query($db_conx, $sql);
	// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
	if (!file_exists("user/$u")) {
	mkdir("user/$u", 0755);
	}
	// Email the user their activation link
	$to = "$e";	 
	$from = "info@goodprinciples.com";
	$subject = 'Good Principles Account Activation';
	$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Good Principles Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.generalpolar.com/root"><img src="http://www.generalpolar.com/root/images/gpLogo.png" width="36" height="30" alt="Good Principles" style="border:none; float:left;"></a>Good Principles Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://www.generalpolar.com/root/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
	$headers = "From: $from\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	mail($to, $subject, $message, $headers);
	echo "signup_success";
	exit();
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Good Principles Social Sign Up</title>
<?php include_once ("icons.html"); ?>
<link rel="stylesheet" href="style/style.css">
<style type="text/css">
#signupform{
margin-top:24px;	
}
#signupform > div {
margin-top: 12px;	
}
#signupform > input,select {
width: 200px;
padding: 3px;
background: #F3F9DD;
}
#signupbtn {
font-size:18px;
padding: 12px;
}
#terms {
border:#CCC 1px solid;
background: #F5F5F5;
padding: 12px;
}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function restrict(elem){
var tf = _(elem);
var rx = new RegExp;
if(elem == "email"){
rx = /[' "]/gi;
} else if(elem == "username"){
rx = /[^a-z0-9]/gi;
}
tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
_(x).innerHTML = "";
}
function checkusername(){
var u = _("username").value;
if(u != ""){
_("unamestatus").innerHTML = 'checking ...';
var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
       if(ajaxReturn(ajax) == true) {
           _("unamestatus").innerHTML = ajax.responseText;
       }
        }
        ajax.send("usernamecheck="+u);
}
}
function signup(){
var u = _("username").value;
var e = _("email").value;
var p1 = _("pass1").value;
var p2 = _("pass2").value;
var c = _("country").value;
var g = _("gender").value;
var status = _("status");
if(u == "" || e == "" || p1 == "" || p2 == "" || c == "" || g == ""){
status.innerHTML = "Fill out all of the form data";
} else if(p1 != p2){
status.innerHTML = "Your password fields do not match";
} else if( _("terms").style.display == "none"){
status.innerHTML = "Please view the terms of use";
} else {
_("signupbtn").style.display = "none";
status.innerHTML = 'please wait ...';
var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
       if(ajaxReturn(ajax) == true) {
           if(ajax.responseText != "signup_success"){
status.innerHTML = ajax.responseText;
_("signupbtn").style.display = "block";
} else {
window.scrollTo(0,0);
_("signupform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
}
       }
        }
        ajax.send("u="+u+"&e="+e+"&p="+p1+"&c="+c+"&g="+g);
}
}
function openTerms(){
_("terms").style.display = "block";
emptyElement("status");
}
/* function addEvents(){
_("elemID").addEventListener("click", func, false);
}
window.onload = addEvents; */
</script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
  <img src="images/gpAgencyTitle.png" alt="Good Principles Social Agency">
  <h1>MEMBER SIGNUP</h1>
  <form name="signupform" id="signupform" onsubmit="return false;">
    <div>Username: </div>
    <input id="username" type="text" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
    <span id="unamestatus"></span>
    <div>Email Address:</div>
    <input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
    <div>Create Password:</div>
    <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="100">
    <div>Confirm Password:</div>
    <input id="pass2" type="password" onfocus="emptyElement('status')" maxlength="100">
    <div>Gender:</div>
    <select id="gender" onfocus="emptyElement('status')">
      <option value=""></option>
      <option value="m">Male</option>
      <option value="f">Female</option>
    </select>
    <div>Country:</div>
    <select id="country" onfocus="emptyElement('status')">
    <option value=""></option>
<option value="United States of America">United States of America</option>
<option value="United Kingdom">United Kingdom</option>
<option value="Canada">Canada</option>
<option value="Mexico">Mexico</option>
<option value="Denmark">Denmark</option>
<option value="Netherlands">Netherlands</option>
<option value="Afghanistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antigua and Barbuda">Antigua and Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bonaire">Bonaire</option>
<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Brazil">Brazil</option>
<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
<option value="Brunei">Brunei</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Canary Islands">Canary Islands</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Channel Islands">Channel Islands</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Christmas Island">Christmas Island</option>
<option value="Cocos Island">Cocos Island</option>
<option value="Columbia">Columbia</option>
<option value="Comoros">Comoros</option>
<option value="Congo">Congo</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote DIvoire">Cote DIvoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Curacao">Curacao</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="East Timor">East Timor</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Falkland Islands">Falkland Islands</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="French Guiana">French Guiana</option>
<option value="French Polynesia">French Polynesia</option>
<option value="French Southern Ter">French Southern Ter</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Great Britain">Great Britain</option>
<option value="Greece">Greece</option>
<option value="Greenland">Greenland</option>
<option value="Grenada">Grenada</option>
<option value="Guadeloupe">Guadeloupe</option>
<option value="Guam">Guam</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Hawaii">Hawaii</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Isle of Man">Isle of Man</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Korea North">Korea North</option>
<option value="Korea South">Korea South</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macau">Macau</option>
<option value="Macedonia">Macedonia</option>
<option value="Madagascar">Madagascar</option>
<option value="Malaysia">Malaysia</option>
<option value="Malawi">Malawi</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Martinique">Martinique</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mayotte">Mayotte</option>
<option value="Mexico">Mexico</option>
<option value="Midway Islands">Midway Islands</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montserrat">Montserrat</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Nambia">Nambia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherland Antilles">Netherland Antilles</option>
<option value="Netherlands">Netherlands</option>
<option value="Nevis">Nevis</option>
<option value="New Caledonia">New Caledonia</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Niue">Niue</option>
<option value="Norfolk Island">Norfolk Island</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau Island">Palau Island</option>
<option value="Palestine">Palestine</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Philippines">Philippines</option>
<option value="Pitcairn Island">Pitcairn Island</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
<option value="Reunion">Reunion</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="St Barthelemy">St Barthelemy</option>
<option value="St Eustatius">St Eustatius</option>
<option value="St Helena">St Helena</option>
<option value="St Kitts-Nevis">St Kitts-Nevis</option>
<option value="St Lucia">St Lucia</option>
<option value="St Maarten">St Maarten</option>
<option value="St Pierre and Miquelon">St Pierre and Miquelon</option>
<option value="St Vincent and Grenadines">St Vincent and Grenadines</option>
<option value="Saipan">Saipan</option>
<option value="Samoa">Samoa</option>
<option value="Samoa American">Samoa American</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Seychelles">Seychelles</option>
<option value="Serbia and Montenegro">Serbia and Montenegro</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Tahiti">Tahiti</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Togo">Togo</option>
<option value="Tokelau">Tokelau</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad and Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Turks and Caicos Is">Turks and Caicos Is</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States of America">United States of America</option>
<option value="Uruguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City State">Vatican City State</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Virgin Islands">Virgin Islands</option>
<option value="Wake Island">Wake Island</option>
<option value="Wallis and Futana Is">Wallis and Futana Is</option>
<option value="Yemen">Yemen</option>
<option value="Zaire">Zaire</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
    </select>
    <div>
      <a href="#" onclick="return false" onmousedown="openTerms()">
        View the Terms Of Use
      </a>
    </div>
    <div id="terms" style="display:none;">
      <h3>Good Principles Terms Of Use</h3>
      <p>1. Have Fun</p>
      <p>2. Be active</p>
      <p>3. Refer your friends</p>
    </div>
    <br /><br />
    <button id="signupbtn" onclick="signup()">Create Account</button>
    <span id="status"></span>
  </form>
<div id="socialMedia">
  <div>
  <a href="http://www.linkedin.com/company/good-principles" target="_blank"><img src="images/linkedinSphere.png" alt="Good Principles LinkedIn" title="Go to our LinkedIn Page"></a>
  <a href="https://www.facebook.com/pages/Good-Principles/453918438015382" target="_blank"><img src="images/facebookSphere.png" alt="Good Principles Facebook" title="Go to our Facebook Page"></a>
  <a href="http://www.youtube.com/goodprinciplesmedia" target="_blank"><img src="images/youtubeSphere.png" alt="Good Principles YouTube" title="Go to our YouTube Channel"></a>
  </div>
  </div>
</div>
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>