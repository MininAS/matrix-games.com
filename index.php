<?
	require "function.php";
	require "sess.php";		$_SESSION["page"] = "index";
	$record = array ();
	$record_ = array ();
	$body = "";

	if (isset($_POST["login"]) && isset($_POST["pass"])) require ("auth.php");

	$file=fopen ("games/top.txt", "r");
	$gameNames = fgetcsv($file, 1000, "\t");
	fclose ($file);
	$div_chet = "chet";
	foreach ($gameNames as $key => $list)
	{
		$theme = $gameNames[$key];
		$body .= "
	<div class = 'windowSite winPreshowGameItem ".$div_chet."'>
		<a href = 'games.php?theme=".$theme."'>
		";
		$body .= "
				<img id = '".$theme."' class = 'border_inset' src = 'img/".$theme."_.gif?lastVersion=4' alt = 'Gamebox'>
				<i class = 'big'>"._l('Game names/'.$theme)."</i><br/>
				<i>"._l('Taglines/'.$theme)."</i>
		</a>
		";
		// Данные для рекорда
		$result = f_mysqlQuery ("
			SELECT  `login`, MAX(  `score` ) AS  `ms`
			FROM  `games_".$theme."_med` ,  `users`
			WHERE  `users`.`id` =  `games_".$theme."_med`.`id_user`
			GROUP BY  `id_user`
			ORDER BY  `ms` DESC
			LIMIT 5");
		$Ni = 1;
		$data = mysqli_fetch_row($result);
		$body .= "
		<div class = 'record ".$div_chet."'>
			<div>
			<ul>
				<li><IMG SRC=\"img/cup_".$Ni."_.png\" alt = 'Cup'></li>
				<li><i class = 'small'>"._l('Home page/High score').": ".$data[0]."</i></li>
				<li><i class = 'small'>".$data[1]."</i></li>
			</ul>
		";
		while ($data = mysqli_fetch_row($result)){
			$Ni++;
			$body .= "
			<ul>
				<li><IMG SRC=\"img/cup_".$Ni."_.png\" alt = 'Cup'></li>
				<li><i class = 'small'>".$data[0]."</i></li>
				<li><i class = 'small'>".$data[1]."</i></li>
			</ul>
			";
		}
		$body .= "
		</div>
	</div>
	<div class = 'windowSite list'>";
// Читаем кто лучше сыграл ---------------------------------------------------------------------------
		$chet = "chet"; $Ni=1;
		$nic = Array (); // Ники полбзователей
		$id_game = Array (); // и номера игр что бы исключить повтарения
		for ($i=1; $i <= 5; $i++) {$nic[$i] = ""; $id_game[$i] = "";}
		$result = f_mysqlQuery ("
				SELECT tg.`id_game`, tg.`id_user`, tg.`score`, tg.`id`
				FROM `games_".$theme."_com` tg
				JOIN (SELECT  `id_game`, MAX(`score`) AS `ms`
				FROM `games_".$theme."_com`
				GROUP BY `id_game`) AS tb
				ON tg.`id_game` = tb.`id_game` AND tg.`score` = tb.`ms`
				ORDER BY `score` DESC, `id`");
    	while ($data = mysqli_fetch_row($result))
		{
			$d = mysqli_fetch_row(f_mysqlQuery ("SELECT `login` FROM `users` WHERE id = ".$data[1]));
			$flag = true;
			for ($i=1; $i <= 5; $i++) if ($nic[$i] == $d[0] || $id_game[$i] == $data[0]) $flag = false;
			if ($flag == true)
			{
				$body .= "
		<a href = 'games.php?theme=".$theme."&canvasLayout=".$data[0]."'>
			<ul class = 'line_game_".$chet."'>
				<li><IMG SRC=\"img/medal_".$Ni.".gif\" alt = 'Medal'></li>
				<li><i class = 'small'>".$d[0]."</i></li>
				<li><i class = 'small'>";
				$body .= $data[2];
				$body .= "</i></li>
			</ul>
		</a>";
				if ($chet == "chet") $chet = "nechet"; else {$chet = "chet";}
				$nic[$Ni] = $d[0];
				$id_game[$Ni] = $data[0];
				if ($Ni == 5) break;
				$Ni++;
			}
		}

		$body .= "
		</div>

	</div>
	";
		if ($div_chet == "chet") $div_chet = "nechet"; else {$div_chet = "chet";}
	}
	require ("display.php");
?>
