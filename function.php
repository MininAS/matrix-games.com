<?
set_error_handler('f_error');
date_default_timezone_set('Europe/Moscow');
// Файл frequency хранит число посещений сайта, вызывая эту функцию мы увеличиваем его на еденицу
	function frequency_add ()
	{

		$file_freq=fopen ("info/frequency.txt", "r+");
		if (@!$file_freq) {f_mail_admin("Не найден файл количества посещений в папке info.");}
		else{
			flock ($file_freq, 2+4);
			$string = fgets ($file_freq,100);
			$string ++;
			rewind ($file_freq);
			fwrite ($file_freq, $string);
			flock ($file_freq, 3);
			fclose ($file_freq);
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------------------------
// Запись в лог учета посещений
	function log_file ($log)
	{
		$name = "info/".date ("Y.m.d").".txt";
		if (file_exists($name))
		{
			$file=fopen ($name, "a");
			$ip = getenv ("REMOTE_ADDR");
			if ($_SESSION["id"] == "") $id = 0; else $id = $_SESSION["id"];
			if ($_SESSION["login"] ==  "") $login = "Quest"; else $login = $_SESSION["login"];
			$string = date ("H:i")."\t".$ip."\t".$id."\t".$login."\t".$_SESSION["page"]."\t".$log."\n";
			fwrite ($file, $string);
			fclose ($file);
		}
		else
		{
			$file = fopen ($name, "w");
			db_saver ();
			fclose ($file);
			log_file ($log);
		}
	}
//--------------------------------------------------------------------------------------------------------------------------------------------------
// Эта функция возвращает число посещений сайта
	function frequency_read ()
	{	$file_freq = fopen ("info/frequency.txt", "r");
		$strong = fgets ($file_freq,100);
		if (@!$file_freq) {$freqread = "#"; exit;} else {$freqread=$strong; }
		fclose ($file_freq);
		return $freqread;
	}
//---------------------------------------------------------------------------------------------------------------------------------------------------
// Возвращение рускоязычного названия игры
	function f_returnThemeNameByRus ($t)
	{
		$file = fopen ("games/top.txt", "r");
		$str_eng = fgetcsv ($file, 1000, "\t");
		$str_rus = fgetcsv ($file, 1000, "\t");
		$num = count ($str_eng);
		fclose ($file);
		for ($i=0; $i < $num; $i++) if ($str_eng[$i] == $t){$t = $str_rus[$i]; break;}
		return $t;
	}
	
// Замена тегов, невидимых символов и смайлов
	function f_messSave($table, $belong, $mess)
	{
		$mess=trim($mess);
		if (empty($mess)) 
			return '
				{
					"res": "001",
					"message": "Сообщение не должно быть пустым."
				}
			';
		if (empty($belong)) 
			return '
				{
					"res": "001",
					"message": "Не определен адресат."
				}
			';
		if (strlen($mess) < 5){
			return '
				{
					"res": "102",
					"message": "Слишком короткой текст."
				}
			';
		}
		if (strlen($mess) > 500){
			return '
				{
					"res": "103",
					"message": "Слишком длинный текст."
				}
			';
		}
		$mess=str_replace ("<", "&#60", $mess);
		$mess=str_replace (">", "&#62", $mess);
		$mess=str_replace ("\\r\\n", "<br>", $mess);
		$mess=str_replace ("\\n", "<br>", $mess);
// Проверка на смайлы
		if (strstr ($mess, "{[:"))
		{
			$arr = preg_split ("/\{\[:|:\]\}/i", $mess);
			$mess="";
			while (list($key, $value) = each ($arr))
			{
				if (ereg("[a-z]", $value))
				{
					$file=$value.".gif";
					if (@file_exists ("smile/$file")) $mess = $mess."<IMG SRC=\"smile/".$value.".gif\">";
					else $mess = $mess.$value;
				}
				else $mess=$mess.$value;
			}
		}
		if (sql ("INSERT ".$table." (id_tema, id_user, text, time, data)
					VALUE (
						".$belong.",
						".$_SESSION["id"].",
						'".$mess."',
						'".date("H:i")."',
						'".date("y.m.d")."'
					);
				")
			){
			sql ("UPDATE users SET N_mess=N_mess+1 WHERE id=".$_SESSION["id"].";");
			if ($table == 'users_mess') 
				sql ("UPDATE users SET F_bette=1 WHERE id=".$belong.";");
			if ($table == 'forum')
				f_mail (1, "На форуме было добавлено новое сообщение: ".$mess." в теме = ".$belong);
			$log = "Отправил сообщение в ".$table." для ".$belong; log_file ($log);
			return '
				{
					"res": "200",
					"message": "Сообщение сохранено."
				}
			';
		}
		else
			return '
				{
					"res": "100",
					"message": "Не удалось сохранить сообщение. Попробуйте еще раз.
					В случае неудачи обратитесь пожалуйста к администратору сайта."
				}
			';
	}
	
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Удаление темы
function f_themeDelet ($theme)
{
	global $text_info;
	if ($_SESSION["dopusk"] == "admin")
	{
		if (sql ("DELETE FROM forum_mess WHERE id_tema IN (SELECT id FROM forum_tema WHERE id_tema=".$theme.");") &&
			sql ("DELETE FROM forum_mess WHERE id_tema=".$theme.";") &&
			sql ("DELETE FROM forum_tema WHERE id_tema=".$theme.";") &&
			sql ("DELETE FROM forum_tema WHERE id=".$theme.";"))
		{
			$text_info ="Тема удалена.";
			$log = "Удаление темы №".$theme; log_file ($log);
		}
		else $text_info = "Операция не выполнена.";
	}
}

//------------------------------------------------------------------------------------------------------------------------------------------------
// Редактирование сообщения
function f_messRedact ($mess, $string)
{
	global $text_info, $reg;
	if ($_SESSION["dopusk"] == "yes" || $_SESSION["dopusk"] == "admin")
	{
		$new_str=trim ($string);
		if (@$reg!=="20") {$new_str=str_replace ("<", "&#60", $new_str); $new_str=str_replace (">", "&#62", $new_str);}
		$new_str=str_replace ("\\r\\n", "<BR>", $new_str);
// Проверка на смайлы
		if (strstr ($new_str, "{[:"))
		{
			$arr = preg_split ("/\{\[:|:\]\}/", $new_str);
			$new_str="";
			while (list($key, $value) = each ($arr))
			{
				if (ereg("[a-z]", $value))
				{
					$file=$value.".gif";
					if (@file_exists ("smile/$file")) $new_str=$new_str."<IMG SRC=\"smile/".$value.".gif\">";
					else $new_str=$new_str.$value;
				}
				else $new_str=$new_str.$value;
			}
		}
		if (sql ("UPDATE forum_mess SET text='".$new_str."' WHERE id=".$mess.";"))
		{
			$text_info ="Сообщение отредактированно.";
			$log = "Редактирование сообщения №".$mess; log_file ($log);
		}
		else $text_info = "Операция не выполнена.";
	}
}

//------------------------------------------------------------------------------------------------------------------------------------------------
// Отправка почты
function f_mail ($user, $mail_mess)
{
	$result=sql ("SELECT F_mail, mail FROM users WHERE id=".$user.";");
	$data = mysql_fetch_row ($result);
	if ($data[0] == 1)
	{
		$mail_mess=str_replace ("\\r\\n", "<br>", $mail_mess);
		mail ($data[1], "Новости с сайта LMG ==>>",
		"
		<!DOCTYPE html>
		<html><head>
		<meta charset='utf-8'>
		</head>
		<style>
			body {
				margin: 0px auto; text-align: center;
			}
			table {
				border: 3px solid #fff; max-width: 500px; margin: 0px auto;
				border-radius: 6px; background-color: #f7f7f7;
				padding: 5px; box-shadow: 0px 0px 5px #888;
				filter: progid:DXImageTransform.Microsoft.Shadow(color='#888', Direction=145, Strength=3);
				}
			I, h2, h3, h5, A, {
				font-family: Verdana; text-decoration: none;
				font-style: normal; margin: 0px 3px; position: inline;
				text-decoration: none; color: #000;
			}
			img {
				float: left;
			}
		</style>
		<body>
		<h3>Здравствуйте! Благодарим за посещения и участие в соревнованиях.</h3>
		<table>
		<tr><td><img src = 'http://matrix-games.ru/img/logotip.png' alt = 'logotip'><h2>".$mail_mess."</h2></td></tr>
		</table>
		<h5>Если вас не устраивает рассылка данной информации, вы можете отказаться от нее на сайте на своей странице или ответить на это письмо.<BR>
			<a href='http://matrix-games.ru'>http://matrix-games.ru</a>                           Администрация.</h5>
		</body></html>",
		"Content-type: text/html; charset=utf-8 \r\n"."From: LMG <mininas@sampo.ru>\r\n");
	}
}
//-------------------------------------------------------------------------------------------------------------------------------------------------
// Сколько разных IP за сутки
function IP_quest ()
{
	$arr = array();
	$file=fopen ("info/".date ("Y.m.d").".txt", "r");
	if (isset ($file))
	{
		while ($d = fgetcsv ($file, 1000, "\t"))
		{
			$f_ARR = true; reset ($arr);
			while (list($key, $value) = each ($arr)) if ($d[2] == $value) {$f_ARR = false; break;}
			if ($f_ARR == true) array_push ($arr, $d[2]);
		}
		fclose ($file);
	}
	return count ($arr);
}
//-------------------------------------------------------------------------------------------------------------------------------------------------
// Обработка запроса к БД
function sql($query)
{
	$result = mysql_query($query);
	if (!$result) {
		$result = "Запрос: <b>".$query."</b><br> - выдал ошибку: <b>". mysql_error()."</b>";
		log_file ($result);
	}
	else return $result;
}
//-------------------------------------------------------------------------------------------------------------------------------------------------
// Обработка ошибок в PHP
function f_error($error, $text, $file, $line)
{
	$string = $error." - ".$text." в файле: ".$file.", строка №".$line."</b>";
	log_file ($string);
	return false;
}
//-------------------------------------------------------------------------------------------------------------------------------------------------
// Ежедневное резевное сохранение базы данных
function db_saver()
{
	$result = sql("SHOW TABLES");
	$tables = array();
	for($i = 0; $i < mysql_num_rows($result); $i++)
	{
		$row = mysql_fetch_row($result);
		$tables[] = $row[0];
	}

	$fp = fopen("db_saver/".date ("Y.m.d").".sql","w");
	$text = "
-- SQL Dump
-- База дынных сайта LMG
-- MininAS
";

	fwrite($fp,$text);
	foreach($tables as $item)
	{
		$text = "
-- ---------------------------------------------------

--
-- Структура таблицы - ".$item."
--
		";
		fwrite($fp,$text);
		$text = "";
		$sql = "SHOW CREATE TABLE ".$item;
		$result = sql($sql);
		$row = mysql_fetch_row($result);
		$text .= "\n".$row[1].";";
		fwrite($fp,$text);
		$text = "";
		$text .="

--
-- Дамп данных таблицы ".$item."
--
		";
		$text .= "\nINSERT INTO `".$item."` VALUES";
		fwrite($fp,$text);
		$sql2 = "SELECT * FROM `".$item."`";
		$result2 = sql($sql2);
		$text = "";
		for($i = 0; $i < mysql_num_rows($result2); $i++)
		{
			$row = mysql_fetch_row($result2);
			if($i == 0) $text .= "\n(";
			else  $text .= ",\n(";
			foreach($row as $v)
			{
				$text .= "'".mysql_real_escape_string($v)."',";
			}
			$text = rtrim($text,",");
			$text .= ")";
			if($i > 10)
			{
				fwrite($fp,$text);
				$text = "";
			}
		}
		$text .= ";\n";
		fwrite($fp,$text);
	}
	fclose($fp);
}

// Вход через Вконтакте -------------------------------------------------------------------------------
function authOpenAPIMember()
{
	$session = array();
	$member = FALSE;
	$valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
	$app_cookie = $_COOKIE['vk_app_2729439'];
	if ($app_cookie)
	{
		$session_data = explode ('&', $app_cookie, 10);
		foreach ($session_data as $pair)
		{
			list($key, $value) = explode('=', $pair, 2);
			if (empty($key) || empty($value) || !in_array($key, $valid_keys)) continue;
			$session[$key] = $value;
		}
		foreach ($valid_keys as $key)
			if (!isset($session[$key])) return $member;
		ksort($session);
			$sign = '';
		foreach ($session as $key => $value)
			if ($key != 'sig') $sign .= ($key.'='.$value);
		$sign .= 'uXyToH8eMPVIAIWzosr3';
		$sign = md5($sign);
		if ($session['sig'] == $sign && $session['expire'] > time())
			$member = array(
				'id' => intval($session['mid']),
				'secret' => $session['secret'],
				'sid' => $session['sid']
			  );
	}
	return $member;
}
function f_img($i, $id)
{
	$d="avatar/".$id."_$i.jpeg";
	if (file_exists ($d))
		$t = "
		<IMG class = 'border_inset' src = '$d' alt = 'Аватар'>";
	else $t = "
		<IMG src = 'avatar/0_$i.png' alt = 'Аватар'>";
	return $t;
}
// Сохранение аватара с соответствующими габаритами -----------------------------
function save_avatar($id_outfile,$infile)
{
    global $text_info;

		$infile = str_replace("https://", "http://", $infile);
    $im=imagecreatefromjpeg($infile);

  	$x=imagesx($im); $y=imagesy($im);
  	if ($x == $y) {$xi = 0; $yi = 0;}
  	if ($x > $y) {$xi = ($x - $y)/2; $yi = 0; $x = $y;}
  	if ($x < $y) {$yi = ($y - $x)/2; $xi = 0; $y = $x;}
    $im1=imagecreatetruecolor(150,150);	imagefill($im1, 0, 0, 0xeeeeee);
  	$im2=imagecreatetruecolor(50,50); imagefill($im2, 0, 0, 0xeeeeee);
    $im3=imagecreatetruecolor(30,30); imagefill($im3, 0, 0, 0xeeeeee);
    imagecopyresampled($im1,$im,0,0,$xi,$yi,150,150,$x,$y);
    imagecopyresampled($im2,$im,0,0,$xi,$yi,50,50,$x,$y);
    imagecopyresampled($im3,$im,0,0,$xi,$yi,30,30,$x,$y);
    imagejpeg($im1, "avatar/".$id_outfile."_1.jpeg", 100);
    imagejpeg($im2, "avatar/".$id_outfile."_2.jpeg", 100);
    imagejpeg($im3, "avatar/".$id_outfile."_3.jpeg", 100);
    imagedestroy($im);
    imagedestroy($im1);
    imagedestroy($im2);
  	imagedestroy($im3);

		$log = "Поменял аватару."; log_file ($log);
		$text_info = "Аватар загружен.";
}
?>
