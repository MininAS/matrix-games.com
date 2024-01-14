<?php
	require "init.php";

	if (!$DB_Connection && !$DB)
	    exit ('
			{
				"res": "100",
				"message": "'._l("Database was not connected by some reason.").'"
			}
		');

	if ($_SESSION["dopusk"] != "yes" && $_SESSION["dopusk"] != "admin")
		if ($gameIsFinished)
			exit ('
				{
					"res": "220",
					"message": "'._l("Gamebook/The game is over. Please, login for the result saving and a competition participation.").'"
				}
			');
		else
			exit();

	if (!preg_match('~^[0-9/]+$~', $canvasLayoutId)) {
		log_file('ERROR Неверные входящие данные с игрового поля:
			- id поля: '.$canvasLayoutId.'
		    - данные: '.$canvasLayoutData);
		exit('
			{
				"res": "110",
				"message": "'._l("Gamebook/Error. Some canvas layout data are invalid.").'"
			}
		');
	}

	if (!preg_match('~^[0-9]{13}_[a-zA-Z]{13}$~', $transitionalKey)) {
		log_file('ERROR Ломаный транзитный ключ:
			- id поля: '.$canvasLayoutId.'
		    - данные: '.$canvasLayoutData.'
		    - транзитный ключ: '.$transitionalKey);
		exit('
			{
				"res": "110",
				"message": "'._l("Gamebook/Error. Some canvas layout data are invalid.").'"
			}
		');
	}

	// TODO Добавить элементарные проверки на превышения лимита очков за шаг и количество шагов. Выполнить пока в WARNING для анализа.

	f_saveGameToTransit($_SESSION["id"], $theme, $canvasLayoutId, $canvasLayoutData, $transitionalKey, $score, $moves);

	if (!$gameIsFinished)
		exit();

    $scoreMin = getScoreMinByGame($theme);
	if ($score <= $scoreMin) {
		if (f_deleteGameFromTransit($_SESSION["id"], $theme, $canvasLayoutId, $canvasLayoutData, $transitionalKey))
		    log_file('
				INFO Попытка игры удалена без сохранения, мало баллов:
				- id поля: '.$canvasLayoutId.'
				- данные: '.$canvasLayoutData.'
				- транзитный ключ: '.$transitionalKey
			);
		exit('
			{
				"res": "110",
				"message": "'._l("Gamebook/The score is too small to save the game.").'"
			}
		');
	}

	if ($canvasLayoutId == "0") {
		$count = getUserLayoutAmount ($theme, $_SESSION["id"]);
		if ($count >= 5){ // TODO Количество разрешенных игр перевести в глобальную переменную.
			if (f_deleteGameFromTransit($_SESSION["id"], $theme, $canvasLayoutId, $canvasLayoutData, $transitionalKey))
				log_file('
					INFO Попытка игры удалена без сохранения, уже сохранено '.$count.' игр пользователем:
					- id поля: '.$canvasLayoutId.'
					- данные: '.$canvasLayoutData.'
					- транзитный ключ: '.$transitionalKey
				);
			exit('
				{
					"res": "100",
					"message": "'._l("Gamebook/The game is over. You have already saved five new games.").'"
				}
			');
		}

		$new_row_id = f_saveGameLikeNewLayout($_SESSION["id"], $theme, $canvasLayoutId, $canvasLayoutData, $transitionalKey, $score, $moves);
		if (!!$new_row_id)
			exit('
				{
					"res": "200",
					"message": "'._l("Gamebook/The game has saved.").'",
					"id": '.$new_row_id.'
				}
			');
		else
			exit('
				{
					"res": "400",
					"message": "'._l("Gamebook/Error. Some canvas layout data are invalid.").'"
				}
			');
	}
	else
	{
		$result = f_saveGameLikeAttempt($_SESSION["id"], $theme, $canvasLayoutId, $canvasLayoutData, $transitionalKey, $score, $moves);

		if ($result == 300)
			exit('
				{
					"res": "300",
					"message": "'._l("Gamebook/Sorry, it seems the game has already been removed.").'"
				}
			');

		if ($result == 210)
		    exit('
				{
					"res": "210",
					"message": "'._l("Gamebook/Your result is best. If nobody get more score, so when game will be removed, you will get points to total rating.").'"
				}
			');

		if ($result == 200)
		    exit('
				{
					"res": "200",
					"message": "'._l("Gamebook/The game has saved.").'"
				}
			');


	}

?>
