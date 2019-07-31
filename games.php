<?php
	require ("function.php");
	require ("sess.php");		$_SESSION["page"] = "game";
	$body = "";
	if (isset($_POST["login"]) && isset($_POST["pass"]))	require ("auth.php"); //Аутентификация
	if ($canvasState == null) $canvasState = 0;
// Проверка на то, что игра выбрана, а следовательно вход на эту страницу выполнен коректно(с центральной).
	if ($theme == null)
	{
		echo ("
		Простите но вход на эту страницу необходимо выполнить с выбором игры на центральной странице сайта.
		Переадресация...
		<script type = 'text/javascript' language = 'JavaScript'>
			setTimeout (\"window.location.href='index.php';\", 3000);
		</script>");
		log_file ("Вход на страницу без выбора игры !");
		exit;
	}

// Удаление игры
	if ($regEdit == "4" && $_SESSION["dopusk"] == "admin")
	{
// Лучший по баллам, так же проверяем, что игра присутствует.
		if ($data=mysql_fetch_row(sql ("SELECT s1.id, id_user, score, users.login, users.F_mailG, users.mail
				FROM games_".$theme."_com AS s1, users
				WHERE id_game=".$canvasState." AND id_user=users.id
				ORDER BY score DESC LIMIT 1;")))
		{
	// Пятерка лидеров с медалями
			$Ni=1; $medal = 0;
			$result=sql ("SELECT id, id_user
									FROM games_".$theme."_com
									WHERE score IN (SELECT MAX(score) FROM games_".$theme."_com GROUP BY id_game)
									ORDER BY score DESC
									LIMIT 5;");
			while ($data_=mysql_fetch_row ($result))
			{
				if ($data[0] == $data_[0]) $medal = $Ni;
				$Ni++;
			}
			sql ("UPDATE users SET N_ballov=N_ballov+1 WHERE id=".$data[1].";");
			$q = ".";
			if ($medal != 0)
			{
				sql ("INSERT games_".$theme."_med (id_user, medal, score)
								VALUE (".$data[1].", ".$medal.", ".$data[2].");");
				$q = ", плюс вы заработали награду за ".$medal."-е призовое место.";
			}
			f_mail ($data[1], "Игра ".f_returnThemeNameByRus ($theme)." №".$canvasState." в которой вы вели счет была удалена и у вас поднялся рейтинг на один".$q);
			f_messSave ("users_mess", $data[1], "Игра ".f_returnThemeNameByRus ($theme)." №".$canvasState." в которой вы вели счет была удалена и у вас поднялся рейтинг на один".$q);
			if (sql ("DELETE FROM games_".$theme." WHERE id_game=".$canvasState.";"))
				if (sql ("DELETE FROM games_".$theme."_com WHERE id_game=".$canvasState.";"))
				{
					$text_info = "<p>Игра удалена.</p>";
					log_file ("Удаление игры. Получает балл ".$data[1]." с результатом ".$data[2]." очков.");
				}
		}
		else $text_info = "<p>Игра уже была удалена.</p>";
		$canvasState = 0;
	}

// Просмотр съигранных игр ====================================================================================================================
	$log = $theme."/".$canvasState; log_file ($log);
	$body .= "
	<div id = 'game_block' class = 'windowSite'>
		<ul class = 'windowTitle'><li>".f_returnThemeNameByRus ($theme)." <i id = 'game_sport'> №".$canvasState."</i></li></ul>
		<div id = 'game'></div>
	</div>
		<form action ='#' name = 'myFormSend_score' method='post'>
			<input type= 'hidden' name = 'regEdit' value='11'/>
			<input id = 'canvasState' type= 'hidden' name='canvasState' value='".$canvasState."'/>
			<input id = 'theme' type= 'hidden' name='theme' value='".$theme."'/>
			<input id = 'mess' type= 'hidden' name='mess' value='".$mess."'/>
		</form>
	<script type = 'text/javascript' language = 'JavaScript' src = 'games/".$theme.".js?v=5.8.5'></script>";
	require ("display.php");
?>
