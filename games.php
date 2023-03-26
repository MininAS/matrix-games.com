<?php
	require ("function.php");
	require ("sess.php");
	$_SESSION["page"] = "game";
	$body = "";
	if (isset($_POST["login"]) && isset($_POST["pass"]))	require ("auth.php"); //Аутентификация
	if ($canvasLayout == null) $canvasLayout = 0;

	// Проверка на то, что игра выбрана, а следовательно вход на эту страницу выполнен корректно (с центральной).
	if ($theme == null)
	{
		echo ("
		Forbidden ... redirect to home page.
		<script type = 'text/javascript' language = 'JavaScript'>
			setTimeout (\"window.location.href='index.php';\", 3000);
		</script>");
		log_file ("Вход на страницу без выбора игры !");
		exit;
	}

	// Увеличим количество посещений в игре. Используется для порядка расположения игр в списке начального экрана
	increaseGameOccurrenceAmount($theme);

	// Удаление игры
	if ($regEdit == "4" && $_SESSION["dopusk"] == "admin")
	{

		// Лучший по баллам, так же проверяем, что игра присутствует.
		if ($data=mysqli_fetch_row(f_mysqlQuery
		   ("   SELECT s1.id, id_user, score, users.login, users.F_mailG, users.mail, users.lang
				FROM games_".$theme."_com AS s1, users
				WHERE id_game=".$canvasLayout." AND id_user=users.id
				ORDER BY score DESC LIMIT 1;")))
		{
	// Пятерка лидеров с медалями
			$Ni = 1; $medal = 0;
			$result = f_mysqlQuery ("SELECT id, id_user
									FROM games_".$theme."_com
									WHERE score IN (SELECT MAX(score) FROM games_".$theme."_com GROUP BY id_game)
									ORDER BY score DESC
									LIMIT 5;");
			while ($data_=mysqli_fetch_row($result))
			{
				if ($data[0] == $data_[0]) $medal = $Ni;
				$Ni++;
			}
			f_mysqlQuery ("UPDATE users SET N_ballov=N_ballov+1 WHERE id=".$data[1].";");
			$q = ".";
			if ($medal != 0)
			{
				f_mysqlQuery ("INSERT games_".$theme."_med (id_user, medal, score)
								VALUE (".$data[1].", ".$medal.", ".$data[2].");");
				$q = _l("Mails/, also you earned a medal", $data[6])." <img src = \'img/medal_".$medal.".gif\' alt = \'Medal\'/>.";
			}
			$message = _l("Game", $data[6])." "
				._l('Game names/'.$theme, $data[6])
				." №".$canvasLayout
				." "._l("Mails/where you were winer has been deleted and your rating has been raised by one", $data[6]).$q;
			f_mail ($data[1], $message, $data[6]);
			f_saveTecnicMessage (0, $data[1], $message);
			if (f_mysqlQuery ("DELETE FROM games_".$theme." WHERE id_game=".$canvasLayout.";"))
				if (f_mysqlQuery ("DELETE FROM games_".$theme."_com WHERE id_game=".$canvasLayout.";"))
				{
					$instant_message = _l("The game has removed.");
					log_file ("Удаление игры. Получает балл ".$data[1]." с результатом ".$data[2]." очков.");
				}
		}
		else $instant_message = _l("The game had have removed already.");
		$canvasLayout = 0;
	}

// Просмотр сыгранных игр ====================================================================================================================
	$log = $theme."/".$canvasLayout; log_file ($log);
	$body .= "
	<div id = 'game_block' class = 'windowSite'>
		<ul class = 'windowTitle'>
			<li>"._l('Game names/'.$theme)."
			    <i id = 'game_sport'> №".$canvasLayout."</i>
			</li>
		</ul>
		<div id = 'game'></div>
	</div>
	<input id = 'canvasLayout' type= 'hidden' name='canvasLayout' value='".$canvasLayout."'/>
	<input id = 'theme' type= 'hidden' name='theme' value='".$theme."'/>

	<script defer type = 'text/javascript' language = 'JavaScript' src = 'games/".$theme.".js?lastVersion=18.3'></script>
	<script defer type = 'text/javascript' language = 'JavaScript' src = 'game.js?lastVersion=15.3'></script>";

	require ("display.php");
?>
