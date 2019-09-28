<?php
require ("function.php");
require ("sess.php");

	if ($_SESSION["dopusk"] != "yes" && $_SESSION["dopusk"] != "admin")
	{
		exit ('
			{
				"res": "100",
				"message": "Для соревнований, необходимо выполнить регистрацию."
			}
		');
	}

	$result = f_mysqlQuery ("
		SELECT id FROM games_".$theme."_com
		WHERE id IN (SELECT MIN(id) FROM games_".$theme."_com GROUP BY id_game)
		AND id_user=".$_SESSION["id"].";");
	$NplayGame = mysql_num_rows($result);

	if ($NplayGame < 1)
		exit('
			{
				"res": "100",
				"message": "Если вы хотите соревноваться, внесите свой вклад в количество игр - сыграйте до конца хотя бы одну игру."
			}
		');

	// Кто открыл и кто лучше сыграл.
	// Читаем кто лучше сыграл. Если есть кто-то лучше вставляем его данные.
	$result = f_mysqlQuery ("
		SELECT id_user, users.login, score
		FROM games_".$theme."_com AS tb, users
		WHERE id_game=".$canvasLayout." AND id_user=users.id
		ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;");
	$count = mysql_num_rows($result);
	if ($count == 0)
 		exit('
			{
				"res": "100",
				"message": "Игра удалена, вероятно только, что."
			}
		');

	$data=mysql_fetch_row($result);
	// Кто открыл игру
	$result = f_mysqlQuery ("
		SELECT id_user
		FROM games_".$theme."_com
		WHERE id_game=".$canvasLayout."
		ORDER BY id LIMIT 1;");
	$data_ = mysql_fetch_row($result);

	if ($_SESSION["id"] == $data_[0])
	 	exit('
			{
				"res": "100",
				"message": "Эта игра была создана вами. Вам неразрешено её продолжать."
			}
		');

	if ($_SESSION["id"] == $data[0])
	 	exit('
			{
				"res": "100",
				"message": "Ваш результат является последним наилучшим, поэтому переиграть вы сможете только после того как переиграют вас."
			}
		');
	$result = f_mysqlQuery ("
		SELECT gameboard
		FROM games_".$theme."
		WHERE id_game=".$canvasLayout.";");
	$data=mysql_fetch_row($result);
	echo('
		{
			"res": "200",
			"message": "'.$data[0].'"
		}
	');
?>
