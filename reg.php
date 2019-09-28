<?php
	require ("function.php");
	require ("sess.php");		$_SESSION["page"] = "reg";
	if ($_SESSION['dopusk'] != 'no') exit;
	$record = array ();
	$record_ = array ();

	$body = "";
	$text = "";
	$login = "";
	$first_name = "";
	$second_name = "";
	$additional_fieldrs = "";


if (isset ($_COOKIE["vk_app_2729439"]))
{
	$reg_text = "Вы первый раз заходите к нам с аккаунта социальной сети Вконтакте.</center>
<center>Если вы ранее регистрировались на сайте вы можете объединить ваши аккаунты, просто заполните поля
Ник и Пароль которыми вы пользовались прежде и нажмите ввод.<br>
Иначе, если желаете, измените ваше имя и нажмите кнопку Регистрация.";
  $login = $_GET["last_name"]."_".$_GET["first_name"];
	$first_name = $_GET["first_name"];
	$second_name = $_GET["last_name"];
	$additional_fieldrs = "
		<INPUT id = 'first_name' TYPE = 'hidden' NAME='first_name' value = ".$first_name.">
		<INPUT id = 'last_name' TYPE = 'hidden' NAME='last_name' value = ".$second_name.">
		<INPUT id = 'photo' TYPE = 'hidden' NAME='photo' value = ".$_GET["photo_200"].">";
}

	$body .="
	<div id = 'windowRegistration' class = 'windowSite'>
		<ul class = 'windowTitle'><li>Регистрация</li></ul>
		<li id = 'windowRegistrationText'>".$reg_text."</li>
		<li>Ник:</li>
		<INPUT id = 'login' class = 'border_inset' TYPE = 'text' NAME='login' value='".$login."' SIZE='20' MAXLENGTH='15'>
		<li id = 'login_'>- введите сетевое имя.</li>
		<li>Пароль:</li>
		<INPUT id = 'pass1' class = 'border_inset' TYPE = 'password' NAME='pass1' SIZE='20' MAXLENGTH='50'>
		<li id = 'pass1_'>- не меньше 4-х символов.</li>
		<li>Пароль:</li>
		<INPUT id = 'pass2' class = 'border_inset' TYPE='password' NAME='pass2' SIZE='20' MAXLENGTH='50'>
		<li id = 'pass2_'>- повторите пароль.</li>
		<li>E-mail:</li>
		<INPUT id = 'e_mail' class = 'border_inset' TYPE = 'text' NAME='e_mail' SIZE='20' MAXLENGTH='50'>
		<li id = 'e_mail_'>- почтовый адресс для связи с вами.</li>
		<li id = 'key_Registration_Saving'  class = 'k_enter'></li>
		".$additional_fieldrs."
	</div>
	<script type = 'text/javascript' language = 'JavaScript' src = 'reg.js?v=8'></script>";
	require ("display.php");

?>
