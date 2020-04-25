<?php
	require ("function.php");
	require ("sess.php");
	$text = "";
//Топ =====================================================================================================================
	$text .= "
	<ul class = 'windowTitle'><li>Рейтинг по сайту</li></ul>
	<ul class = 'messageLists'>";
// Сортируем

	$result = f_mysqlQuery ("SELECT id, login, N_ballov FROM users ORDER BY N_ballov DESC, data DESC;");
	$i_Users = 0;
	while ($data=mysql_fetch_row ($result))
	{
		$i_Users ++;
		$text .= "
		<li  class = 'selectable_list_item' onClick = \"f_windowInfoPopup ('user_top', ".$data[0].");\">
			<span class = 'top_list_item_number'>
				".$i_Users."
			</span>
			<div class = 'message_autor'>
				<div class = 'avatar'>
				".f_img (3, $data[0])."
				</div>
				<span class = 'big'>".$data[2]."</span>
			</div>
			<div class = 'text big'>
				".$data[1]."
			</div>
		</li>";
 	}
	$text .= "
	</ul>
<p>Всего участников - $i_Users</p>
	";

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
