<?php
    // Инициализируем функции
	require "function.php";

    // Устанавливаем обработчик ошибок и временную зону TODO - переделать время
    set_error_handler('f_errorHandler');
	date_default_timezone_set('Europe/Moscow');

	/**
     * Переменна текста для мгновенных сообщений на этапе открытия страницы.
     * Значение по умолчанию - none. Если переменная содержит текст, то сообщение
	 * будет выведено в popup окне при завершении загрузки страницы.
     */
	$GLOBALS['INSTANT_MESSAGE'] = 'none';

	/**
	 * Имена файлов логирования:
	 *  - ежедневный,
	 *  - ежемесячный.
	 */
	$GLOBALS['DAILY_LOGFILE'] = 'logs/'.date('Y.m.d').'.log';
	$GLOBALS['WEEKLY_LOGFILE'] = 'logs/'.date('Y.M.d', strtotime("Sunday")).'.log';
	$GLOBALS['MONTHLY_LOGFILE'] = 'logs/'.date('Y.m').'.log';

	/**
	 * Максимальное количество игр(полей) для сохранения.
	 * Сделано для агитации игрока выбрать из существующих полей игру и попытаться обыграть.
	 */
	$GLOBALS['LAYOUT_AMOUNT'] = 5;   # штук

	/**
	 * Время жизни расклада(слоя) в случае если есть пять и более сохраненных попыток.
	 */
	$GLOBALS['LAYOUT_EXPIRY'] = 18;   # дней

	/**
	 * Список игр в порядке его расположения на основной странице по умолчанию.
	 */
	$GLOBALS['DEFAULT_GAME_LIST_ORDER'] = [
		"sphere" => 0,
		"tetris" => 0,
		"slicing" => 0,
		"sapper" => 0,
		"number" => 0,
		"filler" => 0,
		"circuit" => 0,
		"bouncer" => 0,
		"barrel" => 0
	];

	// Стартуем БД и инициализируем сессию.
	require "sql_queries.php";
	require "sess.php";

	/**
	 * Кладем в переменную словарь языка который соответствует пользовательскому значению.
	 */
	$GLOBALS['LANG_ARRAY'] = f_getTranslatedText($_COOKIE["lang"]);

    /**
	 * Наличие ежедневного файла логирования является флагом для инициализации действий на текущий день:
	 *  - создание файла ежедневного логирования;
	 *  - резервное сохранение БД;
	 *  - сохранение игр(слоев, попыток) из транзитной таблицы в постоянную при условиях, что:
	 *     - игра была создана во временном хранилище менее чем 3 часа назад,
	 *     - количество очков больше минимального,
	 *     - если новая, то полей должно быть не более 5-ти;
	 *  - удаление игр с флагом (remove = 1) с определение призовых мест.
	 */
	if (!file_exists($GLOBALS['DAILY_LOGFILE'])){

		$file = fopen ($GLOBALS['DAILY_LOGFILE'], "w");
		fclose ($file);

		db_saver ();

		foreach ($GLOBALS['DEFAULT_GAME_LIST_ORDER'] as $game => $order) {
			$id_list = getTransitGameIdList($game);

			$scoreMin = getScoreMinByGame($game);
			foreach ($id_list as $id){
				$entry = getTransitGameEntry($game, $id);
				$author_name = getUserLogin($entry['author']);
				$diff = date_timestamp_get(date_create()) - date_timestamp_get(date_create($entry['datetime']));
				if ($diff < 10800) // 3 часа
					continue;

				if ($entry['score'] <= $scoreMin) {
					if (f_deleteGameFromTransit($entry['author'], $game, $entry['layoutId'], $entry['layoutData'], $entry['transitionalKey']))
						log_to_file("INFO Попытка игры удалена без сохранения, мало баллов {$entry['score']} <= $scoreMin:
							- игра: $game
							- игрок: $author_name({$entry['author']})
							- id поля: {$entry['layoutId']}
							- данные: {$entry['layoutData']}
							- транзитный ключ: {$entry['transitionalKey']}
						");
					continue;
				}

				if (!$entry['layoutId']){
					$count = getUserLayoutAmount ($game, $entry['author']);
					if ($count >= $GLOBALS['LAYOUT_AMOUNT']){
						if (f_deleteGameFromTransit($entry['author'], $game, $entry['layoutId'], $entry['layoutData'], $entry['transitionalKey']))
							log_to_file("INFO Попытка игры удалена без сохранения, уже сохранено $count игр пользователем:
								- игра: $game
								- игрок: $author_name({$entry['author']})
								- id поля: {$entry['layoutId']}
								- данные: {$entry['layoutData']}
								- транзитный ключ: {$entry['transitionalKey']}
							");
						continue;
					}
					f_saveGameLikeNewLayout(
						$entry['author'],  $game,  $entry['layoutId'], $entry['layoutData'],
						$entry['transitionalKey'], $entry['score'],    $entry['moves']
					);
				}
				else{
					$bestPlayer = getLayoutBestPlayer ($game, $entry['layoutId']);
					if(!!$bestPlayer){
						if ($bestPlayer['id'] != $entry['author'])
							f_saveGameLikeAttempt(
								$entry['author'],  $game,  $entry['layoutId'], $entry['layoutData'],
								$entry['transitionalKey'], $entry['score'],    $entry['moves']
							);
						else
							if (f_deleteGameFromTransit($entry['author'], $game, $entry['layoutId'], $entry['layoutData'], $entry['transitionalKey']))
								log_to_file("INFO Попытка игры удалена без сохранения, игрок уже ведет в этом поле:
									- игра: $game
									- игрок: $author_name({$entry['author']})
									- id поля: {$entry['layoutId']}
									- транзитный ключ: {$entry['transitionalKey']}
								");
					}
					else{
						if (f_deleteGameFromTransit($entry['author'], $game, $entry['layoutId'], $entry['layoutData'], $entry['transitionalKey']))
								log_to_file("INFO Попытка игры удалена без сохранения, игра не была найдена в базе данны:
									- игра: $game
									- игрок: $author_name({$entry['author']})
									- id поля: {$entry['layoutId']}
									- транзитный ключ: {$entry['transitionalKey']}
								");
					}
				}
			}
		}

		foreach ($GLOBALS['DEFAULT_GAME_LIST_ORDER'] as $game => $order) {
			$result = getMedalPlacesByGame($game);
			while ($data = mysqli_fetch_row ($result)){
				removeGameCanvas ($game, $data[0], $data[1]);
			}
		}
	}

	/**
	 * Наличие еженедельного файла логирования является флагом для инициализации действий на текущий день:
	 *  - создание файла еженедельного логирования;
	 *  - раз в неделю вычитаем по одному баллу у каждого пользователя,
	 *    эффект таяния заработанных очков в случае не частого посещения пользователем сайта.
	 */
	if (!file_exists($GLOBALS['WEEKLY_LOGFILE'])){

		$file = fopen ($GLOBALS['WEEKLY_LOGFILE'], "w");
		fclose ($file);

		decreaseUserScore();
		log_to_file('Выполнено еженедельное вычитание по одному баллу у каждого пользователя.');
	}
?>