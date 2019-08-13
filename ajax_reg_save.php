<?
	require ("function.php");
	require ("sess.php");
	$text = "";

	if (isset ($_COOKIE["vk_app_2729439"]))
	{
		$member = authOpenAPIMember();
		if($member !== FALSE)
		{
			if (isset($_POST["login"]) && isset($_POST["pass"]))
					if (preg_match("/.{4,}/", $_POST["pass"])) require ("auth.php");
			if ($_SESSION["dopusk"] == "no" && $_SESSION["id"] == "")
			{
				if (preg_match("/[a-zA-Z0-9А-Яа-я_\.\-\@\Ё]+/", $_POST["login"]) &&
					preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $_POST["e_mail"]))
				{
					$qwery = f_mysqlQuery ("SELECT login FROM users;");
					$flag = true;
					while ($data = mysql_fetch_row($qwery)) if ($data[0] == $_POST["login"]) {$flag = false; break;}
					if ($flag == true)
					{
						$_SESSION["login"] = $_POST["login"];
						$_SESSION["dopusk"] = "yes";
						f_mysqlQuery ("INSERT users (id_vk, login, pass, last_name, first_name, dopusk, time_R, data_R, ip_R, time, data, ip, mail, N_game, N_ballov, N_mess, N_visit, F_mailG, F_mail, F_bette)
							VALUES ('".$member["id"]."',          '".$_POST["login"]."', '".$_POST["pass"]."', '".$_POST["last_name"]."',
								      '".$_POST["first_name"]."',   'yes',                 '".date ("H:i")."',   '".date ("y.m.d")."',
											'".getenv ("REMOTE_ADDR")."', '".date ("H:i")."',    '".date ("y.m.d")."', '".getenv ("REMOTE_ADDR")."',
											'".$_POST["e_mail"]."', 0, 0, 0, 0, 1, 1, 0);");
						$log = "Регистрация пользователя ".$_SESSION["login"]." через VK."; log_file ($log);
						$_SESSION["id"] = mysql_insert_id();
						if (isset ($_POST["photo"])) save_avatar ($_SESSION["id"], $_POST["photo"]);
						message ();
					}
					else $text = "&nbsp&nbspПростите, но данное имя уже занято. Пожалуйста повторите попытку еще раз.";
				}
				else $text = "&nbsp&nbspПростите, какой то из параметров был введен не верно. Пожалуйста повторите попытку еще раз.";
			}
			else
			{
				f_mysqlQuery ("UPDATE users SET id_vk='".$member["id"]."', last_name='".$_POST['last_name']."', first_name='".$_POST['first_name']."' WHERE id=".$_SESSION["id"].";");
				$log = "Слияние аккаунтов пользователя ".$_SESSION["login"]." с VK."; log_file ($log);
				if (isset ($_POST["photo"]) && !file_exists('avatar/'.$_SESSION["id"])) save_avatar ($_SESSION["id"], $_POST["photo"]);
				message ();
			}
		}
	}
	else if ($_SESSION["dopusk"] == "no" && $_SESSION["id"] == "")
	{
		$reg = 0;
// Регистрация
		if (preg_match("/[a-zA-Z0-9А-Яа-я_\.\-\@\Ё]+/", $_POST["login"]))
		{
			$qwery = f_mysqlQuery ("SELECT login FROM users;");
			$flag = true;
			while ($data = mysql_fetch_row($qwery)) if ($data[0] == $_POST["login"]) {$flag = false; break;}
			if ($flag == true) $reg++;
		}
		if (preg_match("/.{4,}/", $_POST["pass"])) $reg++;
		if (preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $_POST["e_mail"])) $reg++;
		if ($reg == 3)
		{
			$_SESSION["login"] = $_POST["login"];
			$_SESSION["dopusk"] = "yes";
			f_mysqlQuery ("INSERT users (login, pass, dopusk, time_R, data_R, ip_R, time, data, ip,  mail, N_game, N_ballov, N_mess, N_visit, F_mailG, F_mail, F_bette)
				VALUES ('".$_POST["login"]."',        '".$_POST["pass"]."',         'yes',              '".date ("H:i")."',
				        '".date ("y.m.d")."',         '".getenv ("REMOTE_ADDR")."', '".date ("H:i")."', '".date ("y.m.d")."',
								'".getenv ("REMOTE_ADDR")."', '".$_POST["e_mail"]."', 0, 0, 0, 0, 1, 1, 0);");

			$log = "Регистрация пользователя ".$_SESSION["login"]; log_file ($log);
			$_SESSION["id"] = mysql_insert_id();
			message ();
		}
		else
		{
			$text = "&nbsp&nbspПростите, какой то из параметров был введен не верно. Пожалуйста повторите попытку еще раз.";
		}
	}
	else
	{
		$text = "&nbsp&nbspВы авторизованы, повторная регистрация только после выхода с сайта.";
	}

function message ()
{
	global $text;
	$text = "&nbsp&nbspРегистрация пройдена успешно.";
	if (isset ($_POST["e_mail"])) f_mail ($_SESSION["id"], "Регистрация на сайте Logic Matrix Games!",
	"Здравствуйте!<BR>
	Благодарим вас за посещение нашего сайта.<BR>
	Если вы любите простые логические игры, то обязательно будем рады видеть вас снова и снова.<BR>
	Играйте, сохраняйте новые игры и соревнуйтесь используя записанные игры другими игроками.<BR>
	Для более детального понятия пользования сайтом - используйте помощь в правом верхнем углу и правила игр.<BR><BR>
	Желаем удачи!");
}

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
