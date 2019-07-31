<?php
require ("function.php");
require ("sess.php");
// Блок аунтификации
if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin")
{
	$text = "&nbsp&nbsp&nbspИгра завершена. Для сохранения игры на сервере, необходимо выполнить регистрацию.";
	log_file ("Попытка сохранить игру без регистрации.");
}
else
{

	// Заново проверяем переменную mess так как здесь в ее содержании имеются символы табуляции
	$mess	= isset ($_GET['mess']) ? $_GET['mess'] : (isset($_POST['mess']) ? $_POST['mess'] : null);
	if (preg_match('~^[0-9/]+\t[0-9]+\t[0-9]+$~', $mess))
	{
		$text = "";
	// Определяем количество игр сыгранное играком
		$NplayGame = 0;
		if ($_SESSION["id"] != "")
		{
			$NplayGame=mysql_num_rows(sql ("SELECT id FROM games_".$theme."_com
					WHERE id IN (SELECT MIN(id) FROM games_".$theme."_com GROUP BY id_game) AND id_user=".$_SESSION["id"].";"));
		}
		$string = explode ("\t", $mess);
	// Новое сообщение (сохранение игры)
		if ($canvasState == "0")
		{
			if ($NplayGame >= 5) $text .="&nbsp&nbsp&nbspИгра завершена. Вами уже сохранено пять новых игр, более игр сохранить нельзя, пока они не будут удалены за наличием лучшего результата. Выберите игру из списка в правой колонке и попробуйте обыграть другого посетителя сайта.";
			else
			{
				sql ("INSERT games_".$theme." (gameboard) VALUES ('".$string[0]."');");
				$data = mysql_insert_id ();
				if (sql ("INSERT games_".$theme."_com (id_game, id_user, score, xod, time, data)
					VALUES (".$data.", ".$_SESSION["id"].", ".$string[2].", ".$string[1].", '".date ("H:i")."', '".date ("y.m.d")."');"))
				{$text .="&nbsp&nbsp&nbspИгра сохранена."; log_file ("Сохранение новой игры в ".f_returnThemeNameByRus($theme)." №".$data.".");}
				sql ("UPDATE users SET N_game=N_game+1 WHERE id=".$_SESSION["id"].";"); // Увеличиваем число игр
			}
		}
		else
		{
		// Проверка, что игру не стерли
			if (mysql_num_rows(sql ("SELECT id_game FROM games_".$theme." WHERE id_game=".$canvasState.";")) == 1)
			{
				$data=mysql_fetch_row(sql ("SELECT id_user, users.login, users.F_mailG, users.mail FROM games_".$theme."_com AS tb, users
											WHERE id_game=".$canvasState." AND id_user=users.id
											ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;"));

				if (sql ("INSERT games_".$theme."_com (id_game, id_user, score, xod, time, data)
					VALUES (".$canvasState.", ".$_SESSION["id"].", ".$string[2].", ".$string[1].", '".date ("H:i")."', '".date ("y.m.d")."');"))
				{$text .="&nbsp&nbsp&nbspИгра сохранена."; log_file ("Сохранение игры в ".f_returnThemeNameByRus($theme)." №".$canvasState.".");}
				sql ("UPDATE users SET N_game=N_game+1 WHERE id=".$_SESSION["id"].";"); // Увеличиваем число игр
				$data_=mysql_fetch_row(sql ("SELECT id_user, users.login, score FROM games_".$theme."_com AS tb, users
											WHERE id_game=".$canvasState." AND id_user=users.id
											ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;"));
				if ($data_[0] != $data[0])
				{
					f_mail ($data[0],
					"Против вас с успехом сыграл ".$data_[1].".<BR>
					Новый рекорд в игре ".f_returnThemeNameByRus($theme)." за № ".$canvasState." составляет  ".$data_[2]." баллов.<BR>
					<A href ='http://matrix-games.ru/games.php?theme=".$theme."&canvasState=".$canvasState."'><br>&lt;&lt;&lt; Переиграть >>><a>");
					$regEdit = "20";
					f_messSave("users_mess", $data[0], "Я обыграл(а) вашу игру ".f_returnThemeNameByRus($theme)." за № ".$canvasState." со счетом ".$data_[2].".
						<A href =\"http://matrix-games.ru/games.php?theme=".$theme."&canvasState=".$canvasState."\"><br><<< Переиграть >>></a>");
					$text .="&nbsp&nbsp&nbspВы показали лучший результат. Если ее никто не обыграет, то по ее удалении вам будет добавлен балл.";
				}
			}
			else $text .="&nbsp&nbspПростите, но игра уже удалена.";
		}
	}
	else
	{
		$text ="&nbsp&nbsp&nbspПороизошла ошибка. Неверные данные в запросе. Информация отправлена администрации сайта.";
		f_error('Неверные входящие данные с игрового поля:', ' mess='.$mess, 'ajax_game_save.php', 0);
	}
}

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
