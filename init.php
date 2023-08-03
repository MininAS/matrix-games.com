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
	$GLOBALS['MONTHLY_LOGFILE'] = 'logs/'.date('Y.m').'.log';

	/**
	 * Время жизни расклада(слоя) в случае если есть пять и более сохраненных попыток.
	 */
	$GLOBALS['LAYOUT_EXPIRY'] = 18;   # дней

	/**
	 * Список игр в порядке его расположения на основной странице.
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
	 * Наличие файла логирования на дату входа является флагом для инициализации действий на текущий день:
	 *  - создание файла ежедневного логирования;
	 *  - удаление игр с флагом (remove = 1);
	 *  - резервное сохранение БД.
	 */
	if (!file_exists($GLOBALS['DAILY_LOGFILE'])){

		$file = fopen ($GLOBALS['DAILY_LOGFILE'], "w");
		fclose ($file);

		foreach ($GLOBALS['DEFAULT_GAME_LIST_ORDER'] as $game => $order) {
			$result = f_mysqlQuery("
			    SELECT id_game 
				FROM games_".$game." 
				WHERE remove = 1
			");
			while ($canvasLayout = mysqli_fetch_row ($result)){
				removeGameCanvas ($game, $canvasLayout[0]);
			}
		}
		
		db_saver ();
	}
?>