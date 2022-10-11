<?php
	require ("function.php");
	require ("sess.php");

	if (!$DB_Connection && !$DB)
	    exit ('
			{
				"res": "100",
				"message": "'._l("Database was not connected by some reason.").'"
			}
		');

	if ($_SESSION["dopusk"] != "yes" && $_SESSION["dopusk"] != "admin"){
		exit ('
			{
				"res": "100",
				"message": "'._l("Gamebook/The game is over. Please, login for the result saving and a competition participation.").'"
			}
		');
	}

	if (!preg_match('~^[0-9/]+:[0-9]+:[-]*[0-9]+$~', $subGameData)){
		f_errorHandler('Неверные входящие данные с игрового поля: ', $subGameData, 'game_save.php', 0);
		exit('
			{
				"res": "110",
				"message": "'._l("Error. Some data are invalid.").'"
			}
		');
	}

	$string = explode (":", $subGameData);

	if ($string[2] <= 100){
		exit('
			{
				"res": "110",
				"message": "'._l("Gamebook/The score is too small to save the game.").'"
			}
		');
	}

    $count = getUserSubGameAmount ($theme, $_SESSION["id"]);
	if ($canvasLayout == "0"){
		if ($count >= 5)
			exit('
				{
					"res": "100",
					"message": "'._l("Gamebook/The game is over. You have already saved five new games.").'"
				}
			');

		f_mysqlQuery ("INSERT games_".$theme." (gameboard) VALUES ('".$string[0]."');");
		$new_row_id = mysqli_insert_id ($DB_Connection);
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
		$count = mysqli_num_rows($result);
		if ($count != 1)
			exit('
				{
					"res": "100",
					"message": "'._l("Gamebook/Sorry, it seems the game has already been removed.").'"
				}
			');

		$bestPlayer_old = getSubGameBestPlayer ($theme, $canvasLayout);

		if (f_mysqlQuery ("
				INSERT games_".$theme."_com (id_game, id_user, score, xod, time, data)
				VALUES (".$canvasLayout.", ".$_SESSION["id"].", ".$string[2].",
					".$string[1].", '".date ("H:i")."', '".date ("y.m.d")."');
			")){

			log_file ("Сохранение игры в ".$theme." №".$canvasLayout.".");
			f_mysqlQuery ("UPDATE users SET N_game=N_game+1 WHERE id=".$_SESSION["id"].";"); // Увеличиваем число игр

			$bestPlayer_new = getSubGameBestPlayer ($theme, $canvasLayout);
			$creator = getSubGameСreator ($theme, $canvasLayout);

			if ($bestPlayer_new["id"] != $bestPlayer_old["id"]) {
				$message = $bestPlayer_new["login"]." ".
					_l("Mails/played against you successfully.", $bestPlayer_old["lang"])." ".
				    _l("Mails/New score in game", $bestPlayer_old["lang"])." ".
				    _l('Game names/'.$theme, $bestPlayer_old["lang"]).
				    " № ".$canvasLayout." => ".$bestPlayer_new["score"].".";

				if ($bestPlayer_old["id"] != $creator)
				    $message .= "<br>
				    <a href ='http://matrix-games.ru/games.php?theme=".$theme."&canvasLayout=".$canvasLayout.
				    "'><br>&lt;&lt;&lt; "._l("Mails/Replay", $bestPlayer_old["lang"])." >>><a>";

				f_mail ($bestPlayer_old["id"], $message, $bestPlayer_old["lang"]);

				if ($bestPlayer_old["id"] != $creator)
				    $message = _l("Mails/I won your last score", $bestPlayer_old["lang"]);
				else
					$message = _l("Mails/I played your game successfully", $bestPlayer_old["lang"]);
				$message .= " "._l('Game names/'.$theme, $bestPlayer_old["lang"]).
				    " № ".$canvasLayout." => ".$bestPlayer_new["score"].".";

				f_saveTecnicMessage($_SESSION["id"], $bestPlayer_old["id"], $message, $theme, $canvasLayout);

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
