<?php
	require ("function.php");
	require ("sess.php");		$_SESSION["page"] = "index";
	$record = array ();
	$record_ = array ();
	$body = "";
// Блок аунтификации
	if (isset($_POST["login"]) && isset($_POST["pass"]))	require ("auth.php");

// Рисуем блоки ДИВ для описания игр _____________________________________________________________________________________________________________________
	$file=fopen ("info/top.txt", "r");
	$str_theme = fgetcsv($file, 1000, "\t");
	$str_theme_rus = fgetcsv($file, 1000, "\t");
	fclose ($file);
	$div_chet = "chet";
	while (list ($key,$list) = each ($str_theme))
	{
		$theme = $str_theme[$key];
		$theme_rus = $str_theme_rus[$key];
		$body .= "
	<div class = 'windowSite winPreshowGameItem ".$div_chet."'>
		<a href = 'games.php?theme=".$theme."'>";
		$body .= "
				<img id = '".$theme."' class = 'border_inset' src = 'img/".$theme."_.gif?lastVersion=2' alt = 'Играбокс'>
				<i class = 'big'>".$theme_rus."</i><br/>
				<i>";
		require ("info/pre_".$theme.".txt");
		$body .= "</i>
		</a>";
		// Данные для рекорда
		$result = f_mysqlQuery ("
			SELECT  `login`, MAX(  `score` ) AS  `ms`
			FROM  `games_".$theme."_med` ,  `users`
			WHERE  `users`.`id` =  `games_".$theme."_med`.`id_user`
			GROUP BY  `id_user`
			ORDER BY  `ms` DESC
			LIMIT 5");
		$Ni = 1;
		$data = mysql_fetch_row ($result);
		$body .= "<div class = 'record ".$div_chet."'>

			<div>
			<ul>
				<li><IMG SRC=\"img/cup_".$Ni."_.png\" alt = 'Кубок'></li>
				<li><i class = 'small'>Рекорд: ".$data[0]."</i></li>
				<li><i class = 'small'>".$data[1]."</i></li>
			</ul>
			";
		while ($data = mysql_fetch_row ($result))
		{
			$Ni++;
			$body .= "
			<ul>
				<li><IMG SRC=\"img/cup_".$Ni."_.png\" alt = 'Кубок'></li>
				<li><i class = 'small'>".$data[0]."</i></li>
				<li><i class = 'small'>".$data[1]."</i></li>
			</ul>
			";
		}
		$body .= "</div>
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
    	while ($data = mysql_fetch_row ($result))
		{
			$d = mysql_fetch_row (f_mysqlQuery ("SELECT `login` FROM `users` WHERE id = ".$data[1]));
			$flag = true;
			for ($i=1; $i <= 5; $i++) if ($nic[$i] == $d[0] || $id_game[$i] == $data[0]) $flag = false;
			if ($flag == true)
			{
				$body .= "
		<a href = 'games.php?theme=".$theme."&canvasLayout=".$data[0]."'>
			<ul class = 'line_game_".$chet."'>
				<li><IMG SRC=\"img/medal_".$Ni.".gif\" alt = 'Медаль'></li>
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
