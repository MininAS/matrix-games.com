<?php
	require ("function.php");
	require ("sess.php");

if ($_SESSION["dopusk"] == 'yes' || $_SESSION["dopusk"] == 'admin')
{

	$text = "
		<p class = 'big'><br>Вы действительно хотите удалить тему<br>и все вложения?</p>
		<form action = '#' name = 'themeDelet'>
			<input type = 'hidden' name = 'regEdit' value = '9'>
			<input type = 'hidden' name = 'theme' value = '".$theme."'>
			<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'reset'></div>
		</form>";
}
$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
