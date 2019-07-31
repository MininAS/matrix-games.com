<?php
	require ("function.php");
	require ("sess.php");		$_SESSION["page"] = "forum";

// Ѕлок аунтификации
	if (@$_SESSION["dopusk"]!="yes" && @$_SESSION["dopusk"]!="admin") {exit;}


// »справление сообщени§ в форуме -----------------------------------------------------------------------------------------------------------------------------
	$data=mysql_fetch_row(sql("SELECT text FROM forum_mess WHERE id=".$mess.";"));
	$string=$data[0];
	$string=str_replace ("<BR>", "\r\n", $string);
	$string=str_replace ("<br>", "\r\n", $string);
	$string=str_replace ("<IMG SRC=\"smile/", "{[:", $string); $string=str_replace (".gif\">", ":]}", $string);
	$body ="
	<div id = 'formSendMessage' class = 'windowSite'>
		<ul class = 'windowTitle'><li>Редактирование сообщения</li></ul>
		<a class = 'k_clouse' href = 'forum.php?reg=".$reg."&theme=".$theme."'></a>
		<form action = 'forum.php' name = 'forum'>
			<input TYPE = 'hidden' name = 'regEdit' VALUE='55'/>
			<input TYPE = 'hidden' name = 'reg' VALUE='$reg'/>
			<input TYPE = 'hidden' name = 'theme' VALUE='$theme'/>
			<input TYPE = 'hidden' name = 'mess' VALUE='$mess'/>
			<textarea class = 'c_enterText' name='string' cols='64'  rows='5'>$string</textarea>
			<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'reset'></div>
		</form>
	</div>";
require ("display.php");
?>
