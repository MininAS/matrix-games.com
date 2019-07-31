<?php
	require ("function.php");
	require ("sess.php");
	$text = "";
//Топ =====================================================================================================================
	$text .= "
	<ul class = 'windowTitle'><li>Рейтинг по сайту</li></ul>";
// Сортируем

	$result = sql ("SELECT id, login, N_ballov FROM users ORDER BY N_ballov DESC, data DESC;");
	$i_Users = 0;
	while ($data=mysql_fetch_row ($result))
	{
		$i_Users ++;
		$text .= "
		<div onClick = \"window_info ('user_top', ".$data[0].");\">
			<ul>
				<li>
					<i>".$i_Users."</i>
					".f_img(3, $data[0]);
		$text .= "</li>
				<li>".$data[1]."</li>
				<li class = 'big'>".$data[2]."</li>
			</ul>
		</div>";
 	}
	$text .= "
<p>Всего участников - $i_Users</p>
	";

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
