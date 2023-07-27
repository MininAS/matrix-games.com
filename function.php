<?php

	/**
	 * Запись в журнал.
	 * @param string $sting любая строка.
	 */
	function log_to_file ($string) {
		$file=fopen ($GLOBALS['DAILY_LOGFILE'], "a");
		fwrite ($file, $string);
		fclose ($file);
	}

	/**
	 * Запись в журнал с добавлением даты, IP и идентификаторов сессии.
	 * @param string $log
	 */
	function log_file ($log) {
		$ip = getenv ("REMOTE_ADDR");
		if ($_SESSION["id"] == "") $id = 0; else $id = $_SESSION["id"];
		if ($_SESSION["login"] ==  "") $login = "Guest"; else $login = $_SESSION["login"];
		$string = date ("H:i:s")."\t".$ip."\t".$id."\t".$login."\t".$_SESSION["page"]."\t".$log."\t\n";
		log_to_file ($string);
	}

	/**
	 * Сохраняет сообщение в БД, при этом вставляя смайлы и таги.
	 * @param int $user идентификатор пользователя,
	 * @param string $text текстовое сообщение.
	 */
	function f_saveUserMessage($user, $text) {
		$text=trim($text);
        $status = f_checkLengthMessage($text);
		if (!$status) return $status;
        $text = f_convertSmilesAndTagFormat($text);
		if (f_mysqlQuery ("
				INSERT users_mess (id_tema, id_user, text, time, data)
				VALUE (
					".$user.",
					".$_SESSION["id"].",
					'".$text."',
					'".date("H:i")."',
					'".date("y.m.d")."'
				);
			")
		) {
			f_mysqlQuery ("
				UPDATE users
				SET N_mess=N_mess+1
				WHERE id=".$_SESSION["id"].";
			");
			f_mysqlQuery ("
				UPDATE users
				SET F_bette=1
				WHERE id=".$user.";
			");
			$log = "Sent message for ".$user;
			log_file ($log);
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

	/**
	 * Сохраняет сообщение в БД для пользователя об автоматических действиях на сайте,
	 * например удаление игры или выигрыше вас другим участником.
	 * @param int $from идентификатор пользователя от которого будет отправлено сообщение,
	 * @param int $user идентификатор пользователя куму отправить,
	 * @param string $text текстовое сообщение,
	 * @param string $game имя игры,
	 * @param int $subgame идентификатор подигры.
	 */
	function f_saveTecnicMessage($from, $user, $text, $game = "", $subgame = 0) {
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
			) {
			f_mysqlQuery ("
				UPDATE users
				SET F_bette=1
				WHERE id=".$user.";
			");
			$log = "Отправилено сообщение для ".$user; log_file ($log);
		}
	}

// Сообщения -----------------------------------------------------------------------------------------------------------------------------------------
	/**
	 * Проверяет длину сообщения, должно быть между 5 <> 500
	 */
	function f_checkLengthMessage($text) {
				if (empty($text))
					return '
						{
							"res": "101",
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

	/**
	 * Заменяет внутренний формат хранения смайлов на html таги img.
	 * Заменяет, умышленно добавленные таги, на коды.
	 * @param string $text текст сообщения.
	 */
	function f_convertSmilesAndTagFormat($text) {
		$text=str_replace ("<", "&#60", $text);
		$text=str_replace (">", "&#62", $text);
		$text=str_replace ("\\r\\n", "<br>", $text);
		$text=str_replace ("\\n", "<br>", $text);
		// Проверка на смайлы
		if (strstr ($text, "{[:")) {
			$arr = preg_split ("/\{\[:|:\]\}/i", $text);
			$text="";
			foreach ($arr as $key => $value) {
				if (preg_match("/[a-z]{2}/i", $value)) {
					$file=$value.".gif";
					if (@file_exists ("smile/$file"))
						$text = $text."<img src=\"smile/".$value.".gif\">";
					else
						$text = $text.$value;
				}
				else $text=$text.$value;
			}
		}
		return $text;
	}


	/**
	 * Отправляет сообщение на почтовый сервер.
	 * @param int $user идентификатор пользователя,
	 * @param string $mail_mess текстовое сообщение
	 * @param string $lang на каком языке отправить сообщение, по умолчанию язык текущего пользователя.
	 */
	function f_mail ($user, $mail_mess, $lang = 'default') {
		$result=f_mysqlQuery ("
			SELECT F_mail, mail, lang
			FROM users
			WHERE id=".$user.";
		");
		$data = isset($result) ? mysqli_fetch_row ($result) : [1, "mininas@sampo.ru"];
		if ($data[0] == 1) {
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

	/**
	 * Обработка ошибок в PHP
	 */
	function f_errorHandler($errno, $text, $file = "---", $line=0) {
		if (!(error_reporting() & $errno))
			return false;
		$string = $errno." - ".$text." в файле: ".$file.", строка №".$line."/n";
		log_file ($string);
		f_mail (1, $string);
		return true;
	}

	/**
	 * Вход через Вконтакте --------------------------------------------------------
	 */
	function authOpenAPIMember() {
		$session = array();
		$member = FALSE;
		$valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
		$app_cookie = $_COOKIE['vk_app_2729439'];
		if ($app_cookie) {
			$session_data = explode ('&', $app_cookie, 10);
			foreach ($session_data as $pair) {
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

	/**
	 * Проверяет наличие файла изображения и возвращает HTML таг его или по умолчанию.
	 * @param int $i - размер (1, 2, 3)
	 * @param int $id - идентификатор пользователя
	 * @return string $t - HTML таг
	 */
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

	/**
	 * Сохранение аватара с соответствующими габаритами
	 * @param int $user_id - идентификатор пользователя для имени файла,
	 * @param string $infile - адрес изображения.
	 */
	function save_avatar($user_id, $infile) {
		$infile = str_replace("https://", "http://", $infile);
		$size = getimagesize($infile);
		if ($size['mime'] == 'image/jpeg')
			$im=imagecreatefromjpeg ($infile);
		elseif ($size['mime'] == 'image/png')
			$im=imagecreatefrompng ($infile);
		elseif ($size['mime'] == 'image/gif')
			$im=imagecreatefromgif ($infile);
		else {
			$log = "Пытался загрузить " . $size['mime'] . "тип файла."; log_file ($log);
			$GLOBALS['INSTANT_MESSAGE'] = _l("A file format is not supported.");
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
		imagejpeg($im1, "avatar/".$user_id."_1.jpeg", 100);
		imagejpeg($im2, "avatar/".$user_id."_2.jpeg", 100);
		imagejpeg($im3, "avatar/".$user_id."_3.jpeg", 100);
		imagedestroy($im);
		imagedestroy($im1);
		imagedestroy($im2);
		imagedestroy($im3);

		$log = "Поменял аватару.";
		log_file ($log);
		$GLOBALS['INSTANT_MESSAGE'] = _l("The photo has downloaded.");
	}

	/**
	 * Локализация текстовая
	 *
	 */
	function _l($str, $lang = 'default') {
		$path = explode ('/', $str);
		if ($lang == 'default')
			$arr = $GLOBALS['LANG_ARRAY'];
		else {
			$arr = f_getTranslatedText($lang);
		}
		foreach ($path as $key) {
			if (array_key_exists($key, $arr))
				$arr = $arr[$key];
			else {
				$arr = end($path);
				break;
			}
		}
		return $arr;
	}

	function f_getTranslatedText ($lang) {
		$string = file_get_contents ("lang/".$lang."/lang.json");
		$arr = json_decode($string, true);
		return  $arr;
	}

// Функции работы со списком игр и их порядком на главной странице -------------------------------------

	/**
	 * Устанавливает порядок списка игр по количеству вхождений игрока.
	 * Извлекает сохраненный порядок игр для пользователя из COOKIE games_order.
	 * Проверяет если количество вхождений одной из игр = 3(default), то поднимает его в начало,
	 * обнуляет значение и уменьшает на 1 значения остальных (эффект таяния, если долго не открывали игру).
	 * Затем сохраняет список опять в COOKIE.
	 * Если значение в COOKIE не возможно преобразовать из JSON в массив, то возвращает массив по умолчнию.
	 */
	function getCurrentGameArrayOrder($occurrences = 3) {
		$arr = json_decode($_COOKIE["games_order"], true);
		if (is_array($arr)) {
			foreach($arr as $game_name => $value)
				if ($value >= $occurrences)	{
					unset($arr[$game_name]);
					$arr = [$game_name => 0] + $arr;
					foreach($arr as $game_name => $value)
						if ($value != 0)
							$arr[$game_name] --;
					break;
				}
			$string = json_encode($arr);
			setcookie("games_order", $string, time()+31536000);
			$_COOKIE["games_order"] = $string;
		}
		else 
			$arr = $GLOBALS['DEFAULT_GAME_LIST_ORDER'];
		return $arr;
	}

	function increaseGameOccurrenceAmount ($game) {
		$arr = json_decode($_COOKIE["games_order"], true);
		$arr[$game] ++;
		$string = json_encode($arr);
		setcookie("games_order", $string, time()+31536000);
		$_COOKIE["games_order"] = $string;
	}

	/**
	 * Удаление игры, определение лучшего игрока, назначение наград и отправка почты.
	 * @param string $game имя игры,
	 * @param int $canvas идентификатор подигры.
	 */
	function removeGameCanvas($game, $canvas) {
		$subSubGameAmount = getSubSubGameAmount($game, $canvas);
		if ($subSubGameAmount == 0)
			log_file("Попытка удаление подигры которая в базе не найдена.");
		if ($subSubGameAmount < 5) {
			if ($_SESSION["dopusk"] == "admin")
				log_file("Удаление подигры с правами администратора.");
			else {
				log_file("Попытка удаление подигры с количеством попыток = ".$subSubGameAmount.".");
				return;
			}
		}

		$bestPlayer = getSubGameBestPlayer ($game, $canvas);

		// Пятерка лидеров с медалями
		$Ni = 1; $medal = 0;
		$result = f_mysqlQuery ("
			SELECT id, id_user
			FROM games_".$game."_com
			WHERE score IN (
				SELECT MAX(score)
				FROM games_".$game."_com
				GROUP BY id_game)
			ORDER BY score DESC
			LIMIT 5;
		");
		while ($data_=mysqli_fetch_row($result)) {
			if ($canvas == $data_[0]) $medal = $Ni;
			$Ni++;
		}
		f_mysqlQuery ("UPDATE users SET N_ballov=N_ballov+1 WHERE id=".$bestPlayer["id"].";");
		$q = ".";
		if ($medal != 0) {
			f_mysqlQuery ("INSERT games_".$game."_med (id_user, medal, score)
							VALUE (".$bestPlayer["id"].", ".$medal.", ".$bestPlayer["score"].");");
			$q = _l("Mails/, also you earned a medal", $bestPlayer["lang"])." <img src = \'img/medal_".$medal.".gif\' alt = \'Medal\'/>.";
		}
		$message = _l("Game", $bestPlayer["lang"])." "
			._l('Game names/'.$game, $bestPlayer["lang"])
			." №".$canvas
			." "._l("Mails/where you were winer has been deleted and your rating has been raised by one", $bestPlayer["lang"]).$q;
		f_mail ($bestPlayer["id"], $message, $bestPlayer["lang"]);
		f_saveTecnicMessage (0, $bestPlayer["id"], $message);
		if (f_mysqlQuery ("DELETE FROM games_".$game." WHERE id_game=".$canvas.";"))
			if (f_mysqlQuery ("DELETE FROM games_".$game."_com WHERE id_game=".$canvas.";"))
				log_file ("
				    Удаление игры "._l("Game names/".$game, "rus")."(".$game."), поле - ".$canvas.".
				    Получает балл ".$bestPlayer["login"]."(".$bestPlayer["id"].") с результатом ".$bestPlayer["score"]." очков.
				");

		return true;
	}
?>