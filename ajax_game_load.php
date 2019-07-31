<?php
require ("function.php");
require ("sess.php");
$text = "";
// Блок аунтификации
if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin")
{
	$text = "&nbsp&nbsp&nbspДля соревнования с другими игроками необходимо зарегистрироваться.<br>
		&nbsp&nbsp&nbspДля произвольной игры нажмите кнопку в меню 'Начать заново'.";
}
else
{
	// Определяем количество новых игр сыгранное играком
	$NplayGame = 0;
	$NplayGame=mysql_num_rows(sql ("SELECT id FROM games_".$theme."_com
		WHERE id IN (SELECT MIN(id) FROM games_".$theme."_com GROUP BY id_game) AND id_user=".$_SESSION["id"].";"));

	if ($NplayGame >= 1)
	{
		// Кто открыл и кто лучше сыграл
	// Читаем кто лучше сыграл ----------------------------- Если есть ктото лучше вставляем его данные
		$_mysql=sql ("SELECT id_user, users.login, score FROM games_".$theme."_com AS tb, users
					WHERE id_game=".$canvasState." AND id_user=users.id
					ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;");
		if (mysql_num_rows($_mysql) == 0) $text = "Игра уже удалена.";
		else {
			$data=mysql_fetch_row($_mysql);
	// Кто открыл игру
			$data_=mysql_fetch_row(sql ("SELECT id_user FROM games_".$theme."_com WHERE id_game=".$canvasState." ORDER BY id LIMIT 1;"));
			if ($_SESSION["id"] != $data_[0])
			{
				if ($_SESSION["id"] != $data[0])
				{
					$data=mysql_fetch_row(sql ("SELECT gameboard FROM games_".$theme." WHERE id_game=".$canvasState.";"));
					$text .= $data[0];
				}
				else $text .="&nbsp&nbsp&nbspВаш результат является последним наилучшим, поэтому переиграть вы сможете только после того как переиграют вас.";
			}
			else $text .="&nbsp&nbsp&nbspЭта игра была открыта вами. Вам неразрешено её продолжать.";
		}
	}
	else $text .="&nbsp&nbsp&nbspЕсли вы хотите соревноваться, внесите свой вклад в количество игр - сыграйте до конца хотя бы одну игру.";
}

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
