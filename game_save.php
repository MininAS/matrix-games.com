<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"] != "yes" && $_SESSION["dopusk"] != "admin")
	{
		log_file ("Попытка сохранить игру без регистрации.");
		exit ('
			{
				"res": "100",
				"message": "Игра завершена. Для сохранения игры и соревнований, необходимо выполнить регистрацию."
			}
		');
	}

	if (!preg_match('~^[0-9/]+:[0-9]+:[-]*[0-9]+$~', $string)){
		f_error('Неверные входящие данные с игрового поля: ', $string, 'game_save.php', 0);
		exit('
			{
				"res": "110",
				"message": "Пороизошла ошибка. Неверные данные в запросе. Информация отправлена администрации сайта."
			}
		');
	}

	$result = f_mysqlQuery ("
		SELECT id FROM games_".$theme."_com
		WHERE id IN (SELECT MIN(id) FROM games_".$theme."_com
		GROUP BY id_game) AND id_user=".$_SESSION["id"].";");
	$count = mysql_num_rows($result);

	$string = explode (":", $string);

	if ($string[2] <= 100){
		exit('
			{
				"res": "110",
				"message": "Слишком маленький счет, что бы сохранить этот результат."
			}
		');
	}

	if ($canvasLayout == "0")
	{
		if ($count >= 5)
			exit('
				{
					"res": "100",
					"message": "Игра завершена. Вами уже сохранено пять новых игр, более игр сохранить нельзя, пока они не будут удалены за наличием лучшего результата. Выберите игру из списка в левой колонке и попробуйте обыграть другого посетителя сайта."
				}
			');

		f_mysqlQuery ("INSERT games_".$theme." (gameboard) VALUES ('".$string[0]."');");
		$new_row_id = mysql_insert_id ();
		if (f_mysqlQuery ("
			INSERT games_".$theme."_com (id_game, id_user, score, xod, time, data)
			VALUES (".$new_row_id.", ".$_SESSION["id"].", ".$string[2].", ".$string[1].", '".date ("H:i")."', '".date ("y.m.d")."');")){
			echo('
				{
					"res": "200",
					"message": "Игра сохранена.",
					"id": '.$new_row_id.'
				}
			');
			log_file ("Сохранение новой игры в ".f_returnThemeNameByRus($theme)." №".$new_row_id.".");
			f_mysqlQuery ("UPDATE users SET N_game=N_game+1 WHERE id=".$_SESSION["id"].";");
		}
	}
	else
	{
		$result = f_mysqlQuery ("SELECT id_game FROM games_".$theme." WHERE id_game=".$canvasLayout.";");
		$count = mysql_num_rows($result);
		if ($count != 1)
			exit('
				{
					"res": "100",
					"message": "Простите, но игра уже удалена."
				}
			');

		$result = f_mysqlQuery ("
			SELECT id_user, users.login, users.F_mailG, users.mail
			FROM games_".$theme."_com AS tb, users
			WHERE id_game=".$canvasLayout." AND id_user=users.id
			ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;");
		$data = mysql_fetch_row($result);

		if (f_mysqlQuery ("
				INSERT games_".$theme."_com (id_game, id_user, score, xod, time, data)
				VALUES (".$canvasLayout.", ".$_SESSION["id"].", ".$string[2].",
					".$string[1].", '".date ("H:i")."', '".date ("y.m.d")."');
			")){

			log_file ("Сохранение игры в ".f_returnThemeNameByRus($theme)." №".$canvasLayout.".");
			f_mysqlQuery ("UPDATE users SET N_game=N_game+1 WHERE id=".$_SESSION["id"].";"); // Увеличиваем число игр

			$result = f_mysqlQuery ("
				SELECT id_user, users.login, score
				FROM games_".$theme."_com AS tb, users
				WHERE id_game=".$canvasLayout." AND id_user=users.id
				ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;");
			$data_ = mysql_fetch_row($result);

			if ($data_[0] != $data[0])
			{
				f_mail ($data[0],
				"Против вас с успехом сыграл ".$data_[1].".<BR>
				Новый рекорд в игре ".f_returnThemeNameByRus($theme)." за № ".$canvasLayout." составляет  ".$data_[2]." баллов.<BR>
				<A href ='http://matrix-games.ru/games.php?theme=".$theme."&canvasLayout=".$canvasLayout."'><br>&lt;&lt;&lt; Переиграть >>><a>");
				$regEdit = "20";
				f_saveTecnicMessage($_SESSION["id"], $data[0], "Я обыграл(а) вашу игру ".f_returnThemeNameByRus($theme)." за № ".$canvasLayout." со счетом ".$data_[2].".
					<A href =\"./games.php?theme=".$theme."&canvasLayout=".$canvasLayout."\"><br><<< Переиграть >>></a>");
				echo('
					{
						"res": "200",
						"message": "Вы показали лучший результат. Если ее никто не обыграет, то по ее удалении вам будет добавлен балл."
					}
				');
			}
			else {
				echo('
					{
						"res": "200",
						"message": "Игра сохранена."
					}
				');
			}
		}
	}

?>
