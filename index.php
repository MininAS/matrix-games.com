<?php
	require "init.php";
	$_SESSION["page"] = "index";
	$log = "...";
	log_file ($log);

	$body = "";

	if (isset($_POST["login"]) && isset($_POST["pass"])) require ("auth.php");

	$arr = getCurrentGameArrayOrder();
	$div_chet = "chet";
	foreach ($arr as $game_name => $value){
		$body .= "
	<div class = 'windowSite winPreshowGameItem ".$div_chet."'>
		<a href = 'games.php?theme=".$game_name."'>
		";
		$body .= "
				<img id = '".$game_name."' class = 'border_inset' src = 'img/".$game_name."_.gif?lastVersion=4' alt = 'Gamebox'>
				<i class = 'big'>"._l('Game names/'.$game_name)."</i><br/>
				<i>"._l('Taglines/'.$game_name)."</i>
		</a>
		";
		// Данные для рекорда
		$result = f_mysqlQuery ("
			SELECT  `login`, MAX(  `score` ) AS  `ms`
			FROM  `games_".$game_name."_med` ,  `users`
			WHERE  `users`.`id` =  `games_".$game_name."_med`.`id_user`
			GROUP BY  `id_user`
			ORDER BY  `ms` DESC
			LIMIT 5");
		$Ni = 1;
		$data = isset($result) ? mysqli_fetch_row($result) : ["--", "--"];
		$body .= "
		<div class = 'record ".$div_chet."'>
			<div>
			<ul>
				<li><IMG SRC=\"img/cup_".$Ni."_.png\" alt = 'Cup'></li>
				<li><i class = 'small'>"._l('Home page/High score').": ".$data[0]."</i></li>
				<li><i class = 'small'>".$data[1]."</i></li>
			</ul>
		";
		if (isset($result))
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
		$nic = Array (); // Ники пользователей
		$id_game = Array (); // и номера игр что бы исключить повтарения
		for ($i=1; $i <= 5; $i++) {$nic[$i] = ""; $id_game[$i] = "";}
		$result = f_mysqlQuery ("
				SELECT tg.`id_game`, tg.`id_user`, tg.`score`, tg.`id`
				FROM `games_".$game_name."_com` tg
				JOIN (SELECT  `id_game`, MAX(`score`) AS `ms`
				FROM `games_".$game_name."_com`
				GROUP BY `id_game`) AS tb
				ON tg.`id_game` = tb.`id_game` AND tg.`score` = tb.`ms`
				ORDER BY `score` DESC, `id`");
		if (isset($result))
			while ($data = mysqli_fetch_row($result)){
				$d = mysqli_fetch_row(f_mysqlQuery ("SELECT `login` FROM `users` WHERE id = ".$data[1]));
				$flag = true;
				for ($i=1; $i <= 5; $i++) if ($nic[$i] == $d[0] || $id_game[$i] == $data[0]) $flag = false;
				if ($flag == true)
				{
					$body .= "
		<a href = 'games.php?theme=".$game_name."&canvasLayout=".$data[0]."'>
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
