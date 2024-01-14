<?php
	require "init.php";
	$_SESSION["page"] = "game";
	log_file ("...");

	$body = "";

	//Аутентификация
	if (isset($_POST["login"]) && isset($_POST["pass"]))
		require ("auth.php");
	if ($canvasLayoutId == null)
		$canvasLayoutId = 0;

	// Проверка на то, что игра выбрана, а следовательно вход на эту страницу выполнен корректно (с центральной).
	if ($theme == null) {
		echo ("
		Forbidden ... redirect to home page.
		<script type = 'text/javascript' language = 'JavaScript'>
			setTimeout (\"window.location.href='index.php';\", 3000);
		</script>");
		log_file ("Вход на страницу без выбора игры !");
		exit;
	}

	// Увеличим количество посещений в игре. Используется для порядка расположения игр в списке начального экрана.
	increaseGameOccurrenceAmount($theme);

	// Ручное удаление игры.
	if ($regEdit == "4" && $_SESSION["dopusk"] == "admin") {
		if (removeGameCanvas($theme, $canvasLayoutId))
			$GLOBALS['INSTANT_MESSAGE'] = _l("The game has removed.");
		else
			$GLOBALS['INSTANT_MESSAGE'] = _l("The game had have removed already.");
		$canvasLayoutId = 0;
	}

    // Контейнер игрового поля.
	log_file ($theme."/".$canvasLayoutId);
	$body .= "
	<div id = 'game_block' class = 'windowSite'>
		<ul class = 'windowTitle'>
			<li>"._l('Game names/'.$theme)."
			    <i id = 'game_sport'> №".$canvasLayoutId."</i>
			</li>
		</ul>
		<div id = 'game'></div>
	</div>
	<input id = 'canvasLayoutId' type= 'hidden' name='canvasLayoutId' value='".$canvasLayoutId."'/>
	<input id = 'theme' type= 'hidden' name='theme' value='".$theme."'/>

	<script defer type = 'text/javascript' language = 'JavaScript' src = 'games/".$theme.".js?lastVersion=18.5'></script>
	<script defer type = 'text/javascript' language = 'JavaScript' src = 'game.js?lastVersion=15.6'></script>";

	require ("display.php");
?>
