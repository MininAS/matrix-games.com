<?php
	require "init.php";
	$_SESSION["page"] = "forum";
	$log = "...";
	log_file ($log);

	if (isset($_POST["login"]) && isset($_POST["pass"]))
		require ("auth.php");

	$body = "
	<div class = 'windowSite'>
		<ul class = 'windowTitle'>
			<li><p>"._l("Forum/Forum")."</p></li>
			<li id = 'forum_primary' class = 'key'><p>/</p></li>
			<li id = 'forum_secondary' class = 'key'><p>//</p></li>
		</ul>
		<div id = 'messageWindow'>
		</div>
	</div>";

// Форма для сообщений -----------------------------------------------------------------------------------------------------
	if ($_SESSION["dopusk"] == "yes" || $_SESSION["dopusk"] == "admin") {
		$body .= "
	<div id = 'formSendTheme' class = 'formSendTheme windowSite'>
		<ul class = 'windowTitle'><li>"._l("Forum/New topic")."</li></ul>
		<input class = 'c_enterText' type='text' name='newThemeName' SIZE='50' maxlenght='150'>
		<div class = 'k_enter'></div>
	</div>
	<div id = 'formSendMessage' class = 'formSendMessage windowSite'>
		<ul class = 'windowTitle'><li>"._l("Forum/New message")."</li></ul>
		<textarea name = 'newForumItemText' cols='50'  rows='3'></textarea>
		<div class = 'k_close'></div>
		<div class = 'k_smile' onClick=\"f_windowInfoPopup('smile');\"></div>
		<div class = 'k_enter'></div>
	</div>
	<div id = 'messDeleteConfirmPopup' class = 'invisible windowSite popupMenu'>
		<span>&nbsp&nbsp"._l("Forum/Confirm removing.")."&nbsp&nbsp</span>
		<div class = 'k_enter'></div>
	</div>";
	}

	$body .= "
	<script defer type = 'text/javascript' language = 'JavaScript' src = 'forum.js?lastVersion=10.4'></script>";
require ("display.php");
?>
