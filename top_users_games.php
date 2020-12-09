<?php
	require ("function.php");
	require ("sess.php");

	if ($canvasLayout == "") $canvasLayout = 0;

// Просмотр съигранных игр ====================================================================================================================

	$result = f_mysqlQuery ("SELECT id_game, MAX(score) AS sum FROM games_".$theme."_com GROUP BY id_game ORDER BY sum DESC;");
	echo ("
	<ul class = 'windowTitle'><li>"._l("Rating/Layout сombinations")."</li></ul>
	<ul class = 'messageLists'>");
    while ($data = mysql_fetch_row ($result))
	{
		echo  ("
		<li id = 'G".$data[0]."' class = 'selectable_list_item'>");

// Читаем кто лучше сыграл ----------------------------- Если есть ктото лучше вставляем его данные
		$data_=mysql_fetch_row(f_mysqlQuery ("SELECT id_user, users.login, score FROM games_".$theme."_com AS tb, users
											WHERE id_game=".$data[0]." AND id_user=users.id
											ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;"));
		echo  ("
    		<span class = 'top_list_item_number'>
				".$data[0]."
			</span>
			<div class = 'message_autor'>
				<div class = 'avatar'>
				".f_img (3, $data_[0])."
				</div>
				<span>".$data_[1]."</span>
				<p class = 'data big'>".$data_[2]."</p>
			</div>

			<div class = 'text'>");
		$result_ = f_mysqlQuery ("SELECT id_user, users.login, score FROM games_".$theme."_com AS s1, users
								WHERE id_game=".$data[0]." AND id_user=users.id
								ORDER BY s1.data, s1.time;");
		while ($data_=mysql_fetch_row($result_))
		{
			echo  ("
				<p class = 'small'> ".$data_[2]." - ".$data_[1]."</p>");
		}

		if ($_SESSION["dopusk"]=="admin")
		{
			echo  ("
				<div class = 'forum_list_item_buttons'>
					<a class = 'text_insignificant'
						href='games.php?regEdit=4&theme=".$theme."&canvasLayout=".$data[0]."'
						>"._l("Notebook/Remove")."</a>
				</div>");
		}
		echo ("
			</div>");
	}
	echo ("
	</ul>");
?>
