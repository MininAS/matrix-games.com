<?php
	require ("function.php");
	require ("sess.php");
	$text = "";


// –исуем результат =============================================================
	$text .= "
		<div>
		<table border=1>
			<tr>
				<td class = 'big' colspan = '3' align = 'center' width = '45%'>–екорд</td>
				<td  width = '7%'></td>
				<td class = 'big' colspan = '3' align = 'center' width = '45%'>ћедали</td>
			</tr>";
	$result = f_mysqlQuery ("
		SELECT  `login`, `id_user`, MAX(`score`) AS  `ms`
		FROM  `games_".$theme."_med`, `users`
		WHERE `users`.`id` = `games_".$theme."_med`.`id_user`
		GROUP BY  `id_user`
		ORDER BY  `ms` DESC");
	$result_ = f_mysqlQuery ("
		SELECT  `login`, `id_user`, COUNT(`medal`), SUM(6-`medal`) AS  `mc`
		FROM  `games_".$theme."_med`, `users`
		WHERE `users`.`id` = `games_".$theme."_med`.`id_user`
		GROUP BY  `id_user`
		ORDER BY  `mc` DESC, `medal`");
	$Ni = 0; $Ne = 0;
	while ($data = mysql_fetch_row ($result))
	{
		$data_ = mysql_fetch_row ($result_);
		$text .="
			<tr>
				<td>";
		$Ni++; $Ne++;
		if ($Ni <= 5) $text .= "<p class = 'cup' align = 'center'><IMG SRC='img/cup_".$Ni."_.png' alt = '†убок'></p>";

			$text .= "
				</td>
				<td><p>".$data[0]."</p></td>
				<td><p>".$data[2]."</p></td>
				<td class = 'windowSite'><p class = 'big'>".$Ne."</p></td>
				<td>";
			$result__=f_mysqlQuery ("
				SELECT  `medal`, COUNT(`medal`)
				FROM  `games_".$theme."_med`
				WHERE `id_user` = ".$data_[1]."
				GROUP BY  `medal`
				ORDER BY  `medal`");
			while ($data__ = mysql_fetch_row ($result__))
			{
				for ($i=1;$i<=$data__[1]; $i++)
				$text .= "<p class = 'med'><IMG SRC='img/medal_".$data__[0].".gif' alt = 'Юедаль'></p>";
			}
			$text .= "</td>
				<td><p>".$data_[0]."</p></td>
				<td><p> х ".$data_[2]."</p></td>
			</tr>";
	}
	$text .= "
		</table>
		</div>
		<br>";

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
