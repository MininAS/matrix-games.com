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
	$GLOBALS['instant_message'] = 'none';

	/**
	 * Имя файла логирования по сегодняшней дате.
	 */
	$GLOBALS['log_file_name'] = 'logs/'.date('Y.m.d').'.log';

	/**
	 *
	 */
	$GLOBALS['subgame_expiry'] = 18;   # дней

	// Стартуем БД и инициализируем функции.
	require "sql_queries.php";
	require "sess.php";

    /**
	 * Наличие файла логирования на дату входа является флагом для инициализация действий на текущий день:
	 *  - создание файла ежедневного логирования;
	 *  - резервное сохранение БД.
	 */
	if (!file_exists($GLOBALS['log_file_name'])) {
		$file = fopen ($GLOBALS['log_file_name'], "w");
		fclose ($file);
		db_saver ();
	}
?>