<?php
	require ("function.php");
	require ("sess.php");
	$_SESSION["page"] = "forum";
	$log = "..."; log_file ($log);

	if (isset($_POST["login"]) && isset($_POST["pass"]))	require ("auth.php"); //Аутентификация

$body = "
	<div class = 'windowSite'>
		<ul class = 'windowTitle'>
			<li><p>Записная книжка</p></li>
		</ul>
		<li id = 'k_up'></li>
		<div id = 'messageWindow'>
		</div>
	</div>";

// Форма для сообщений -----------------------------------------------------------------------------------------------------
	if ($_SESSION["dopusk"] == "yes" || $_SESSION["dopusk"] == "admin")
	{
		$body .= "
	<div id = 'formSendTheme' class = 'windowSite'>
		<ul class = 'windowTitle'><li>Новая тема</li></ul>
		<input class = 'c_enterText' type='text' name='newThemeName' SIZE='50' maxlenght='150'>
		<div class = 'k_enter'></div>
	</div>
	<div id = 'formSendMessage' class = 'windowSite'>
		<ul class = 'windowTitle'><li>Новое сообщение</li></ul>
		<textarea name = 'string' cols='50'  rows='3'></textarea>
		<div class = 'k_close'></div>
		<div class = 'k_smile' onClick=\"window_info('smile');\"></div>
		<div class = 'k_enter'></div>
	</div>";
	}

	$body .= "
	<script type = 'text/javascript' language = 'JavaScript' src = 'forum.js?v=7.0.4'></script>";
require ("display.php");
?>
