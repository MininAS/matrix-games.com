<?
// Файл frequency хранит число посещений сайта, вызывая эту функцию мы увеличиваем его на еденицу
	function frequency_add ()
	{

		$file_freq = fopen ("logbook/frequency.txt", "r+");
		if (@!$file_freq)
		    f_error("Файл не найден.", "Не найден файл количества посещений в папке logbook.");
		else{
			flock ($file_freq, 2+4);
			$string = fgets ($file_freq,100);
			$string ++;
			rewind ($file_freq);
			fwrite ($file_freq, $string);
			flock  ($file_freq, 3);
			fclose ($file_freq);
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------------------------
// Запись в лог учета посещений
	function log_file ($log)
	{
		$name = "logbook/".date ("Y.m.d").".txt";
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
	{	$file_freq = fopen ("logbook/frequency.txt", "r");
		$strong = fgets ($file_freq,100);
		if (@!$file_freq) {$freqread = "#"; exit;} else {$freqread=$strong; }
		fclose ($file_freq);
		return $freqread;
	}
//---------------------------------------------------------------------------------------------------------------------------------------------------

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
			$log = "Sent message for ".$user; log_file ($log);
			return '
				{
					"res": "200",
					"message": "'._l('Notebook/Message was sent.').'"
				}
			';
		}
		else
			return '
				{
					"res": "100",
					"message": "'._l('Notebook/Message was not sent.').'"
				}
			';
	}

	function f_saveTecnicMessage($from, $user, $text, $game = "", $subgame = 0){
		if (f_mysqlQuery ("INSERT users_mess (id_tema, id_user, text, time, data, game, subgame)
					VALUE (
						".$user.",
						".$from.",
						'".$text."',
						'".date("H:i")."',
						'".date("y.m.d")."',
						'".$game."',
						'".$subgame."'
					);
				")
			){
			f_mysqlQuery ("UPDATE users SET F_bette=1 WHERE id=".$user.";");
			$log = "Отправилено сообщение для ".$user; log_file ($log);
		}
	}

//------------------------------------------------------------------------------------------------------------------------------------------------------
// Проверка длинны сообщения
function f_checkLengthMessage($text){
			if (empty($text))
				return '
					{
						"res": "001",
						"message": "'._l("Notebook/The input field is empty.").'"
					}
				';
			else if (strlen($text) < 5)
				return '
					{
						"res": "102",
						"message": "'._l("Notebook/The text is very shot.").'"
					}
				';
			else if (strlen($text) > 500)
				return '
					{
						"res": "103",
						"message": "'._l("Notebook/The text is very long.").'"
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
			if (preg_match("[a-z]", $value)){
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
function f_mail ($user, $mail_mess, $lang = 'default')
{
	$result=f_mysqlQuery ("SELECT F_mail, mail, lang FROM users WHERE id=".$user.";");
	$data = mysql_fetch_row ($result);
	if ($data[0] == 1)
	{
		$mail_mess=str_replace ("\\r\\n", "<br>", $mail_mess);
		mail ($data[1], _l("Mails/News from LMG ==>>", $lang),
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
				border: 3px solid #fff; max-width: 600px; margin: 0px auto;
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
		<h3>"._l("Mails/Hello! Thank you for visiting and participating in the competition.", $lang)."</h3>
		<table>
		<tr><td><img src = 'http://matrix-games.ru/img/logotip.png' alt = 'logotip'><h2>".$mail_mess."</h2></td></tr>
		</table>
		<h5>"._l("Mails/If you are not satisfied notifications, you can unsubscribe from it on the website on your page or reply to this letter.", $lang)."<BR>
			<a href='http://matrix-games.ru'>http://matrix-games.ru</a>                           Cubic.</h5>
		</body></html>",
		"Content-type: text/html; charset=utf-8 \r\n"."From: LMG <mininas@sampo.ru>\r\n");
	}
}
//-------------------------------------------------------------------------------------------------------------------------------------------------
// Сколько разных IP за сутки
function IP_quest ()
{
	$arr = array();
	$file=fopen ("logbook/".date ("Y.m.d").".txt", "r");
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

// Обработка ошибок в PHP ------------------------------------------------------

function f_error($error, $text, $file, $line=0)
{
	$string = $error." - ".$text." в файле: ".$file.", строка №".$line."/n";
	log_file ($string);
	f_mail (1, $string);
	return false;
}

// Вход через Вконтакте --------------------------------------------------------

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
		<img class = 'border_inset' src = '$d' alt = 'Avatar'>";
	else $t = "
		<img src = 'avatar/0_$i.png' alt = 'Avatar'>";
	return $t;
}
// Сохранение аватара с соответствующими габаритами -----------------------------

function save_avatar($id_outfile, $infile)
{
    global $instant_message;

	$infile = str_replace("https://", "http://", $infile);
    $size = getimagesize($infile);
    if ($size['mime'] == 'image/jpeg') $im=imagecreatefromjpeg ($infile);
	elseif ($size['mime'] == 'image/png') $im=imagecreatefrompng ($infile);
	elseif ($size['mime'] == 'image/gif') $im=imagecreatefromgif ($infile);
	else {
		$log = "Пытался загрузить " . $size['mime'] . "тип файла."; log_file ($log);
		$instant_message = _l("A file format is not supported.");
		return;
	}

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
		$instant_message = _l("The foto has downloaded.");
}

// Локализация тектовая
function _l($str, $lang = 'default'){
	$path = explode ('/', $str);
	if ($lang == 'default')
	    $arr = $GLOBALS['LANG_ARRAY'];
	else {
		$arr = f_getTranslatedText($lang);
	}
	foreach ($path as $key){
		if (array_key_exists($key, $arr))
			$arr = $arr[$key];
		else {
		    $arr = end($path);
			break;
		}
	}
    return $arr;
}

function f_getTranslatedText ($lang){
	$string = file_get_contents ("lang/".$lang."/lang.json");
	$arr = json_decode($string, true);
	return  $arr;
}

require ("sql_queries.php");
?>
