<?php
	// Проверка сессии
	if (isset ($_COOKIE["LMG"]))
	    if (preg_match("/sess_[0-9a-z]{32}/", $_COOKIE["LMG"]))
	        session_id ($_COOKIE["LMG"]);

	// Параметр включения звука.
	if (isset ($_COOKIE["sound"])){
		if (!in_array (($_COOKIE["sound"]), array("on", "off"))){
			setcookie("sound", 'on', time()+31536000);
			$_COOKIE["sound"] = "on";
		}
	}
	else {
		setcookie("sound", 'on', time()+31536000);
		$_COOKIE["sound"] = "on";
	}

	// Параметр выбора языка.
	if (isset ($_COOKIE["lang"])){
		if (!in_array (($_COOKIE["lang"]), array("rus", "eng"))){
			setcookie("lang", 'rus', time() + 31536000);
			$_COOKIE["lang"] = "rus";
		}
	}
	else {
		setcookie("lang", 'rus', time()+31536000);
		$_COOKIE["lang"] = "rus";
	}

	// Параметр последовательности игр.
	if (isset ($_COOKIE["games_order"])){
		$expected = $GLOBALS['DEFAULT_GAME_LIST_ORDER'];
		$current =  json_decode($_COOKIE["games_order"], true);
		$sum = array_diff_key($expected, $current);
        if (count($sum) > 0)
			setGameOrderToCookies($expected);
		else
			log_to_file("WARNING: Не правильный входящий параметр для порядка игр. games_order = ".print_r($current, true));
	}
	else 
		setGameOrderToCookies($GLOBALS['DEFAULT_GAME_LIST_ORDER']);

	session_name ("LMG");
	session_save_path ("sess");
	session_set_cookie_params (31536000);
	session_start ();

	$_SESSION["id"] = isset ($_SESSION["id"]) ? $_SESSION["id"] : "";
	$_SESSION["login"] = isset ($_SESSION["login"]) ? $_SESSION["login"] : "Guest";
	$_SESSION["dopusk"] = isset ($_SESSION["dopusk"]) ? $_SESSION["dopusk"] : "no";
	$_SESSION["page"] = isset ($_SESSION["page"]) ? $_SESSION["page"] : "";

	// Проверка изменен ли язык, если да обновляем его в БД для пользователя.
	if ($_SESSION["id"] != "" && $_SESSION["lang"] != $_COOKIE["lang"]){
	    f_mysqlQuery ("
			UPDATE users
			SET lang='".$_COOKIE["lang"]."'
			WHERE id=".$_SESSION["id"].";
		");
	}
	$_SESSION["lang"] = $_COOKIE["lang"];

	// Переменная сортировка сравнение по массиву
	if (!isset ($_GET["sort"])) $_GET["sort"] = "id";
	else {
		$a_sort = array (
			'id', 'login', 'data DESC, time DESC', 'N_visit DESC',
			'N_ballov DESC', 'N_game DESC', 'N_mess DESC'
		);
		if (!in_array ($_GET["sort"], $a_sort)){
			f_errorHandler('Неверные входящие данные:', ' параметр sort не соответствует допустимому значению. Sort='.$_GET["sort"], 'sess.php', 0);
			$_GET["sort"] = 'id';
		}
	}

	// Проверяем тему(имя игры) на соответствие списку иначе инициируем как integer.
	$theme = isset ($_GET['theme']) ? $_GET['theme'] : (isset($_POST['theme']) ? $_POST['theme'] : null);
	$arr = $GLOBALS['DEFAULT_GAME_LIST_ORDER'];
	if (!in_array ($theme, $arr)) $theme = (int)$theme;

	// Переменные цифровые
	$arr = array ('canvasLayout', 'user', 'regEdit', 'mess');
	foreach ($arr as $key => $value){
		$v = $value;
		$$v	= isset ($_GET[$value]) ? $_GET[$value] : (isset($_POST[$value]) ? $_POST[$value] : null);
		$$v = (int)$$v;
	}

	// Переменные текстовые
	$arr = array ('newThemeName', 'canvasLayoutData', 'newNotebookItemText');
	foreach ($arr as $key => $value){
		$v = $value;
		$$v	= isset ($_GET[$value]) ? $_GET[$value] : (isset($_POST[$value]) ? $_POST[$value] : null);
		if ($DB_Connection)
		    $$v = mysqli_real_escape_string($DB_Connection, $$v);
	}

// Ставим время последнего посещения
	$_SESSION["last_time"] = date ("U");
	if ($_SESSION["id"] != "")
		f_mysqlQuery ("
			UPDATE users
			SET data='".date ("y.m.d")."',
			 	time='".date ("H:i")."'
			WHERE id=".$_SESSION["id"].";
		");

// Проверка пользователя на выход из сайта
	if (isset ($_GET['exit'])){
		$log = "Выход."; log_file ($log);
		$_SESSION["id"] = "";
		$_SESSION["login"] = "";
		$_SESSION["dopusk"] = "no";
	}

	function f_getSessionInfo($name){
		$file = fopen  ("sess/$name", "r");
		$string = fgets ($file);
		fclose ($file);

        $string = str_replace ('"', '', $string);
		$arr = preg_split ('/;/', $string);
		$arr = array_diff($arr, array(''));
		$a_sessionData = array();
		foreach ($arr as $key) {
			$data = preg_split ('/\|s:[0-9]+:/', $key);
			$a_sessionData[$data[0]] = isset($data[1]) ? $data[1] : '';
		}
		return $a_sessionData;
	}

	/**
	 * Переводит массив в JSON и публикует в cookies.
	 * @param array $arr массив с именами игр и значениями вхождений.
	 */
	function setGameOrderToCookies($arr){
		$string = json_encode($arr);
		setcookie("games_order", $string, time()+31536000);
		$_COOKIE["games_order"] = $string;
	}
?>
