<?php
	require ("function.php");
	require ("sess.php");
	$text = "";

	$data=mysql_fetch_row (f_mysqlQuery ("SELECT login, N_ballov FROM users WHERE id=".$user.";"));
// Рисуем результат =============================================================
	$text .= "
		<div id = 'statistic'  align = 'center'>
			<p>
			".f_img (1, $user);

		$text .= "
			</p>
			<p>".$data[0]."</p>
			<p>".$data[1]."</p>
		</div>
		<div id = 'stat_med'><table border=1>
			<tr>
				<td><p class = 'big'></p></td>
				<td><p class = 'big'>"._l("Game")."</p></td>
				<td colspan = 2><p class = 'big'>"._l("Rating/Gold")."</p></td>
				<td colspan = 2><p class = 'big'>"._l("Rating/Silver")."</p></td>
				<td colspan = 2><p class = 'big'>"._l("Rating/Bronze")."</p></td>
				<td colspan = 2><p class = 'big'>"._l("Rating/Copper")."</p></td>
				<td colspan = 2><p class = 'big'>"._l("Rating/Brass")."</p></td>
			</tr>
			<tr>";
		for ($i=1; $i <= 5; $i++) $medal[$i] = 0;
		$flag_OK = false;
		$file=fopen ("games/top.txt", "r");
		$str_theme = fgetcsv($file, 1000, "\t");
		fclose ($file);
		while (list ($key,$theme) = each ($str_theme))
		{
			$data=mysql_fetch_row(f_mysqlQuery ("SELECT COUNT(*) FROM games_".$theme."_med WHERE id_user=".$user.";"));
			if ($data[0] > 0)
			{
				$flag_OK = true;
				$text .= "
			<tr>
				<td>";
				$result = f_mysqlQuery ("
					SELECT  `id_user`, MAX(  `score` ) AS  `ms`
					FROM  `games_".$theme."_med`
					GROUP BY  `id_user`
					ORDER BY  `ms` DESC
					LIMIT 5");
				$Ni = 0;
				while ($data = mysql_fetch_row ($result))
				{
					$Ni++;
					if ($data[0] == $user) $text .= "<p class = 'cup'><IMG SRC=\"img/cup_".$Ni."_.png\" alt = 'Cup'></p>";
				}
				$text .= "
				</td>
				<td><p class = 'big'>"._l("Game names/".$theme)."</p></td>";

				for ($i = 1; $i <= 5; $i++)
				{
					$text .="
				<td>";
					$data=mysql_fetch_row(f_mysqlQuery ("SELECT COUNT(*), MAX(score) FROM games_".$theme."_med WHERE id_user=".$user." AND medal=".$i.";"));
					if ($data[0] != 0) $text .= "
				<IMG SRC = 'img/medal_".$i.".gif' alt = 'Medal'/> х ".$data[0];
					$text .= "</td>
				<td>
				- ".$data[1]."
				</td>";
					$medal[$i] += $data[0];
				}
				$text .= "
			</tr>";
			}
		}
		if ($flag_OK == false) {$text .= "
			<tr>
				<td colspan = 11>
					<br>
					<P>"._l("Rating/The player has not any rewards still.")."</P>
					<br>
				</td>
			</tr>";
		}
		$text .= "
			<tr>
			<td></td>
			<td><hr><p class = 'big'>"._l("Rating/Total").":</p></td>";
		for ($i=1; $i <= 5; $i++)
			$text .= "
				<td class = 'big' align = 'center' colspan = 2><hr>".$medal[$i]."</td>";
		$text .= "</tr>";
		$text .= "</table></div><br>";

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
