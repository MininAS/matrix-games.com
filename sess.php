<?php
// Открытие сессии
	if (isset ($_COOKIE["LMG"])) session_id ($_COOKIE["LMG"]);
	if (!isset ($_COOKIE["sound"])) {setcookie("sound", 'on', time()+31536000); $_COOKIE["sound"]="on";}
	if (!isset ($_COOKIE["lang"]))  {setcookie("lang", 'rus', time()+31536000); $_COOKIE["lang"]="eng";}

	session_name ("LMG");
	session_save_path ("sess");
	session_set_cookie_params (31536000);
	session_start ();

	mysql_connect("localhost", "root", "");
	mysql_query ("SET NAMES 'utf8'");
	mysql_select_db ("mininas_db");

	$_SESSION["id"] = isset ($_SESSION["id"]) ? $_SESSION["id"] : "";
	$_SESSION["login"] = isset ($_SESSION["login"]) ? $_SESSION["login"] : "Guest";
	$_SESSION["dopusk"] = isset ($_SESSION["dopusk"]) ? $_SESSION["dopusk"] : "";
	$_SESSION["frequency"] = isset ($_SESSION["frequency"]) ? $_SESSION["frequency"] : "";
	$_SESSION["page"] = isset ($_SESSION["page"]) ? $_SESSION["page"] : "";
	if ($_SESSION["id"] != "" && $_SESSION["lang"] != $_COOKIE["lang"]){
	    f_mysqlQuery ("
			UPDATE users
			SET lang='".$_COOKIE["lang"]."'
			WHERE id=".$_SESSION["id"].";
		");
	}
	$_SESSION["lang"] = $_COOKIE["lang"];

// Восстановление переменных+++++++++++++++++++++++++
	// Переменная переменного типа
	$theme = isset ($_GET['theme']) ? $_GET['theme'] : (isset($_POST['theme']) ? $_POST['theme'] : null);
	$file = fopen ("games/top.txt", "r");
	$a_theme = fgetcsv ($file, 1000, "\t");
	fclose ($file);
	if (!in_array ($theme, $a_theme)) $theme = (int)$theme;

	// Переменная сортировка сравнение по массиву
	if (!isset ($_GET["sort"])) $_GET["sort"] = "id";
	else {
		$a_sort = array (
			'id', 'login', 'data DESC',
			'time DESC', 'N_visit DESC',
			'N_ballov DESC', 'N_game DESC', 'N_mess DESC'
		);
		if (!in_array ($_GET["sort"], $a_sort)){
			f_error('Неверные входящие данные:', ' параметр sort не соответствует допустимому значению. Sort='.$_GET["sort"], 'sess.php', 0);
			$_GET["sort"] = 'id';
		}
	}
	// Переменные цифровые
	$arr = array ('canvasLayout', 'user', 'regEdit', 'mess');
	while (list($key, $value) = each ($arr)){
		$v = $value;
		$$v	= isset ($_GET[$value]) ? $_GET[$value] : (isset($_POST[$value]) ? $_POST[$value] : null);
		$$v = (int)$$v;
	}

	// Переменные текстовые
	$arr = array ('newThemeName', 'subGameData', 'newNotebookItemText');
	while (list($key, $value) = each ($arr)){
		$v = $value;
		$$v	= isset ($_GET[$value]) ? $_GET[$value] : (isset($_POST[$value]) ? $_POST[$value] : null);
		$$v = mysql_real_escape_string($$v);
	}

// Открытие сессии dopusk - пользователь зарегестрирован, frequency - открывает при первом прохождении через
// сценарий разрешение на увеличение числа посещений.
// Если первый раз зашел то допуск - гость.
	if ($_SESSION["frequency"]!="yes"){
		$_SESSION["dopusk"] = "no";
		frequency_add (); 		// Количество посещений данного сайта
		log_file ("\tПосещение сайта. ");
		$_SESSION["frequency"] = "yes";
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

// Локализация тектовая
    if (@!$string = file_get_contents ("lang/".$_COOKIE["lang"]."/lang.json"))
		f_mail_admin("The lang json file was not found for lang=".$_COOKIE["lang"].".");
	else
		$LANG_ARRAY = json_decode($string, true);

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

		$arr = preg_split ('/";/', $string);
		$arr = array_diff($arr, array(''));
		$a_sessionData = array();
		foreach ($arr as $key) {
			$data = preg_split ('/\|s:[0-9]+:\"/', $key);
			$a_sessionData[$data[0]] = $data[1];
		}
		return $a_sessionData;
	}
?>
