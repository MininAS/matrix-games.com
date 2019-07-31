<?php
	require ("function.php");
	require ("sess.php");
	$text_help = "";
	$text_info = "";
	$text = "";

	if ($canvasState == "") $canvasState = 0;

// Просмотр съигранных игр ====================================================================================================================

	$result = sql ("SELECT id_game, MAX(score) AS sum FROM games_".$theme."_com GROUP BY id_game ORDER BY sum DESC;");
	$text .="
	<ul class = 'windowTitle'><li>Комбинации полей</li></ul>";
    while ($data=mysql_fetch_row($result))
	{
		$text .= "
		<div id = 'G".$data[0]."'>";

// Читаем кто лучше сыграл ----------------------------- Если есть ктото лучше вставляем его данные
		$data_=mysql_fetch_row(sql ("SELECT id_user, users.login, score FROM games_".$theme."_com AS tb, users
											WHERE id_game=".$data[0]." AND id_user=users.id
											ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;"));
		$text .= "
			<ul>
				<li>
		".f_img (3, $data_[0]);
		$text .= "
				</li>
				<li>".$data_[1]."</li>
				<li class='big'>".$data_[2]."</li>
			</ul>";
		$result_ = sql ("SELECT id_user, users.login, score FROM games_".$theme."_com AS s1, users
								WHERE id_game=".$data[0]." AND id_user=users.id
								ORDER BY s1.data, s1.time;");
		$data_=mysql_fetch_row($result_);
		$text .= "
			<ol>
				<li class = 'small'>№".$data[0]." открыл игру: ".$data_[1]." - ".$data_[2]."</li>";
		$Ngames = 1;
		while ($data_=mysql_fetch_row($result_))
		{
			$Ngames += 1;
			$text .= "
				<li class = 'small'4>".$Ngames." ".$data_[1]." - ".$data_[2]."</li>";
		}

		if ($_SESSION["dopusk"]=="admin")
		{
			$text .= "
				<li><a href='games.php?regEdit=4&theme=".$theme."&canvasState=".$data[0]."' class = 'small' align = 'right'>удалить</a></li>";
		}
		$text .="
			</ol>
		</div>";
	}

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);

?>
