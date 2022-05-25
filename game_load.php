<?php
require ("function.php");
require ("sess.php");

	if ($_SESSION["dopusk"] != "yes" && $_SESSION["dopusk"] != "admin")
	{
		exit ('
			{
				"res": "100",
				"message": "'._l("Gamebook/Please, login that you'll be able to competition.").'"
			}
		');
	}

	$count = getUserSubgameAmount ($theme, $_SESSION["id"]);
	if ($count < 1)
		exit('
			{
				"res": "100",
				"message": "'._l("Gamebook/If you want to competition, please make contribution to the games number - play one game at least by end.").'"
			}
		');

	// Кто открыл и кто лучше сыграл.
	// Читаем кто лучше сыграл. Если есть кто-то лучше вставляем его данные.
	$result = f_mysqlQuery ("
		SELECT id_user, score
		FROM games_".$theme."_com
		WHERE id_game=".$canvasLayout."
		ORDER BY score DESC, xod, data, time LIMIT 1;");
	$count = mysqli_num_rows($result);
	if ($count == 0)
 		exit('
			{
				"res": "100",
				"message": "'._l("Gamebook/The game was removed, perhaps right now.").'"
			}
		');

	$data=mysqli_fetch_row($result);
	// Кто открыл игру
	$result = f_mysqlQuery ("
		SELECT id_user
		FROM games_".$theme."_com
		WHERE id_game=".$canvasLayout."
		ORDER BY id LIMIT 1;");
	$data_ = mysqli_fetch_row($result);

	if ($_SESSION["id"] == $data_[0])
	 	exit('
			{
				"res": "100",
				"message": "'._l("Gamebook/The game was created by you, you can not replay it.").'"
			}
		');

	if ($_SESSION["id"] == $data[0])
	 	exit('
			{
				"res": "100",
				"message": "'._l("Gamebook/Your result is the best, you can not replay the game.").'"
			}
		');
	$result = f_mysqlQuery ("
		SELECT gameboard
		FROM games_".$theme."
		WHERE id_game=".$canvasLayout.";");
	$data=mysqli_fetch_row($result);
	echo('
		{
			"res": "200",
			"message": "'.$data[0].'"
		}
	');
?>
