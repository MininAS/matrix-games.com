<?php
	require ("function.php");
	require ("sess.php");		$_SESSION["page"] = "reg";
	if ($_SESSION['dopusk'] != 'no') exit;
    $reg_text = "";
	$body = "";
	$text = "";
	$login = "";
	$first_name = "";
	$second_name = "";
	$additional_fields = "";

if (isset ($_COOKIE["vk_app_2729439"])){
	$reg_text = _l("Profile/The first visit through Vkontakte.");
    $login = $_GET["last_name"]."_".$_GET["first_name"];
	$first_name = $_GET["first_name"];
	$second_name = $_GET["last_name"];
	$additional_fields = "
		<INPUT id = 'first_name' TYPE = 'hidden' NAME='first_name' value = ".$first_name.">
		<INPUT id = 'last_name' TYPE = 'hidden' NAME='last_name' value = ".$second_name.">
		<INPUT id = 'photo' TYPE = 'hidden' NAME='photo' value = ".$_GET["photo_200"].">";
}

	$body .="
	<div id = 'windowRegistration' class = 'windowSite'>
		<ul class = 'windowTitle'><li>"._l("Profile/Registration")."</li></ul>
		<li id = 'windowRegistrationText'>".$reg_text."</li>
		<li>"._l('Login').":</li>
		<INPUT id = 'login' class = 'border_inset' TYPE = 'text' NAME='login' value='".$login."' SIZE='20' MAXLENGTH='15'>
		<li id = 'login_'>- "._l("Profile/enter login.")."</li>
		<li>"._l('Password').":</li>
		<INPUT id = 'pass1' class = 'border_inset' TYPE = 'password' NAME='pass1' SIZE='20' MAXLENGTH='50'>
		<li id = 'pass1_'>- "._l("Profile/at least 4 characters.")."</li>
		<li>"._l('Password').":</li>
		<INPUT id = 'pass2' class = 'border_inset' TYPE='password' NAME='pass2' SIZE='20' MAXLENGTH='50'>
		<li id = 'pass2_'>- "._l("Profile/repeat password.")."</li>
		<li>"._l('Mail').":</li>
		<INPUT id = 'e_mail' class = 'border_inset' TYPE = 'text' NAME='e_mail' SIZE='20' MAXLENGTH='50'>
		<li id = 'e_mail_'>- "._l("Profile/mail to contact you.")."</li>
		<li id = 'key_Registration_Saving'  class = 'k_enter'></li>
		".$additional_fields."
	</div>
	<script defer type = 'text/javascript' language = 'JavaScript' src = 'reg.js?lastVersion=11'></script>";
	require ("display.php");

?>
