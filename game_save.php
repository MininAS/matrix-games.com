<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"] != "yes" && $_SESSION["dopusk"] != "admin")
	{
		exit ('
			{
				"res": "100",
				"message": "'._l("Gamebook/The game is over. Please, login for the result saving and a competition participation.").'"
			}
		');
	}

	if (!preg_match('~^[0-9/]+:[0-9]+:[-]*[0-9]+$~', $subGameData)){
		f_error('Неверные входящие данные с игрового поля: ', $subGameData, 'game_save.php', 0);
		exit('
			{
				"res": "110",
				"message": "'._l("Error. Some data are invalid.").'"
			}
		');
	}

	$result = f_mysqlQuery ("
		SELECT id FROM games_".$theme."_com
		WHERE id IN (SELECT MIN(id) FROM games_".$theme."_com
		GROUP BY id_game) AND id_user=".$_SESSION["id"].";");
	$count = mysql_num_rows($result);

	$string = explode (":", $subGameData);

	if ($string[2] <= 100){
		exit('
			{
				"res": "110",
				"message": "'._l("Gamebook/The score is too small to save the game.").'"
			}
		');
	}

	if ($canvasLayout == "0")
	{
		if ($count >= 5)
			exit('
				{
					"res": "100",
					"message": "'._l("Gamebook/The game is over. You have already saved five new games.").'"
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
					"message": "'._l("Gamebook/The game has saved.").'",
					"id": '.$new_row_id.'
				}
			');
			log_file ("Сохранение новой игры в ".$theme." №".$new_row_id.".");
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
					"message": "'._l("Gamebook/Sorry, it seems the game has already been removed.").'"
				}
			');

		$result = f_mysqlQuery ("
			SELECT id_user, users.login, users.F_mailG, users.mail, users.lang
			FROM games_".$theme."_com AS tb, users
			WHERE id_game=".$canvasLayout." AND id_user=users.id
			ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;");
		$data = mysql_fetch_row($result);

		if (f_mysqlQuery ("
				INSERT games_".$theme."_com (id_game, id_user, score, xod, time, data)
				VALUES (".$canvasLayout.", ".$_SESSION["id"].", ".$string[2].",
					".$string[1].", '".date ("H:i")."', '".date ("y.m.d")."');
			")){

			log_file ("Сохранение игры в ".$theme." №".$canvasLayout.".");
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
				    $data_[1]." "
					._l("Mails/played against you successfully.", $data[4])
					." "
				    ._l("Mails/New score in game", $data[4])." "
				    ._l('Game names/'.$theme, $data[4])
				    ." № ".$canvasLayout." => ".$data_[2]
				    .".<br><a href ='http://matrix-games.ru/games.php?theme=".$theme."&canvasLayout=".$canvasLayout
				    ."'><br><&lt;&lt;&lt; "._l("Mails/Replay", $data[4])." >>><a>", $data[4]);
				$regEdit = "20";
				f_saveTecnicMessage($_SESSION["id"], $data[0],
				    _l("Mails/I played you game successfully", $data[4])
					." "
					._l('Game names/'.$theme, $data[4])
					." № ".$canvasLayout." => ".$data_[2].".
					<A href =\"./games.php?theme=".$theme."&canvasLayout=".$canvasLayout."\"><br><<< "._l("Mails/Replay", $data[4])." >>></a>");
				echo('
					{
						"res": "200",
						"message": "'._l("Gamebook/Your result is best. If nobody get more score, so when game will be removed, you will get one point to overall rating.").'"
					}
				');
			}
			else {
				echo('
					{
						"res": "200",
						"message": "'._l("Gamebook/The game has saved.").'"
					}
				');
			}
		}
	}

?>
