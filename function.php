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
			$string = date ("H:i:s")."\t".$ip."\t".$id."\t".$login."\t".$_SESSION["page"]."\t".$log."\n";
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

// Замена тегов, невидимых символов и смайлов и сохранение сообщения или редактриование старого --------------
	function f_saveUserMessage($user, $text)
	{
		$text=trim($text);
        $status = f_checkLengthMessage($text);
		if (!$status) return $status;
        $text = f_convertSmilesAndTagFormat($text);
		if (f_mysqlQuery ("INSERT users_mess (id_tema, id_user, text, time, data)
					VALUE (
						".$user.",
						".$_SESSION["id"].",
						'".$text."',
						'".date("H:i")."',
						'".date("y.m.d")."'
					);
				")
			){
			f_mysqlQuery ("UPDATE users SET N_mess=N_mess+1 WHERE id=".$_SESSION["id"].";");
			f_mysqlQuery ("UPDATE users SET F_bette=1 WHERE id=".$user.";");
			$log = "Отправил сообщение для ".$user; log_file ($log);
			return '
				{
					"res": "200",
					"message": "Сообщение отправлено."
				}
			';
		}
		else
			return '
				{
					"res": "100",
					"message": "Не удалось сохранить сообщение. Попробуйте еще раз. В случае неудачи обратитесь пожалуйста к администратору сайта."
				}
			';
	}

//------------------------------------------------------------------------------------------------------------------------------------------------------
// Проверка длинны сообщения
function f_checkLengthMessage($text){
			if (empty($text))
				return '
					{
						"res": "001",
						"message": "Сообщение не должно быть пустым."
					}
				';
			else if (strlen($text) < 5)
				return '
					{
						"res": "102",
						"message": "Слишком короткой текст."
					}
				';
			else if (strlen($text) > 500)
				return '
					{
						"res": "103",
						"message": "Слишком длинный текст."
					}
				';
			else return "Alright";
}

//------------------------------------------------------------------------------------------------------------------------------------------------------
// Форматирование смайлов
function f_convertSmilesAndTagFormat($text){
	$text=str_replace ("<", "&#60", $text);
	$text=str_replace (">", "&#62", $text);
	$text=str_replace ("\\r\\n", "<br>", $text);
	$text=str_replace ("\\n", "<br>", $text);
// Проверка на смайлы
	if (strstr ($text, "{[:")){
		$arr = preg_split ("/\{\[:|:\]\}/i", $text);
		$text="";
		while (list($key, $value) = each ($arr)){
			if (ereg("[a-z]", $value)){
				$file=$value.".gif";
				if (@file_exists ("smile/$file")) $text = $text."<img src=\"smile/".$value.".gif\">";
				else $text = $text.$value;
			}
			else $text=$text.$value;
		}
	}
	return $text;
}

//------------------------------------------------------------------------------------------------------------------------------------------------
// Отправка почты
function f_mail ($user, $mail_mess)
{
	$result=f_mysqlQuery ("SELECT F_mail, mail FROM users WHERE id=".$user.";");
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
function f_mysqlQuery($query)
{
	$result = mysql_query($query);
	if (!$result) {
		$result = "Запрос: ".$query." - выдал ошибку: ". mysql_error()."/n";
		log_file ($result);
	}
	else return $result;
}
//-------------------------------------------------------------------------------------------------------------------------------------------------
// Обработка ошибок в PHP
function f_error($error, $text, $file, $line)
{
	$string = $error." - ".$text." в файле: ".$file.", строка №".$line."/n";
	log_file ($string);
	return false;
}
//-------------------------------------------------------------------------------------------------------------------------------------------------
// Ежедневное резевное сохранение базы данных
function db_saver()
{
	$result = f_mysqlQuery("SHOW TABLES");
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
		$result = f_mysqlQuery($sql);
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
		$result2 = f_mysqlQuery($sql2);
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
