<?php

	mysql_connect("localhost", "root", "");
	mysql_query ("SET NAMES 'utf8'");
	mysql_select_db ("mininas_db");

    // mysql_connect("matrix-gam.mysql", "matrix-gam_mysql", "C_jrLY4b");
    // mysql_query ("SET NAMES 'utf8'");
    // mysql_select_db ("matrix-gam_db");

	function f_mysqlQuery($query){
		$result = mysql_query($query);
		if (!$result) {
			$result = "Запрос: ".$query." - выдал ошибку: ". mysql_error()."/n";
			log_file ($result);
		}
		else return $result;
	}

	function getUserThemeGameAmount ($theme, $user){
		$result = f_mysqlQuery ("
			SELECT id
			FROM games_".$theme."_com
			WHERE id IN (
				SELECT MIN(id)
				FROM games_".$theme."_com
			    GROUP BY id_game
			)
			AND id_user=".$user.";"
		);
		if (@mysql_num_rows($result)) 
		    return mysql_num_rows($result);
		else
		    return 0;
	}

// Ежедневное резевное сохранение базы данных
	function db_saver(){
		$result = f_mysqlQuery("SHOW TABLES");
		$tables = array();
		for($i = 0; $i < mysql_num_rows($result); $i++){
			$row = mysql_fetch_row($result);
			$tables[] = $row[0];
		}

		$fp = fopen("db_saver/".date ("Y.m.d").".sql","w");
		$text = "
	-- SQL Dump
	-- База дынных сайта LMG
	-- MininAS
	";

		fwrite($fp,$text);
		foreach($tables as $item){
			$text = "
	-- ---------------------------------------------------

	--
	-- Структура таблицы - ".$item."
	--
			";
			fwrite($fp,$text);
			$text = "";
			$sql = "SHOW CREATE TABLE ".$item;
			$result = f_mysqlQuery($sql);
			$row = mysql_fetch_row($result);
			$text .= "\n".$row[1].";";
			fwrite($fp,$text);
			$text = "";
			$text .="

	--
	-- Дамп данных таблицы ".$item."
	--
			";
			$text .= "\nINSERT INTO `".$item."` VALUES";
			fwrite($fp,$text);
			$sql2 = "SELECT * FROM `".$item."`";
			$result2 = f_mysqlQuery($sql2);
			$text = "";
			for($i = 0; $i < mysql_num_rows($result2); $i++){
				$row = mysql_fetch_row($result2);
				if($i == 0) $text .= "\n(";
				else  $text .= ",\n(";
				foreach($row as $v){
					$text .= "'".mysql_real_escape_string($v)."',";
				}
				$text = rtrim($text,",");
				$text .= ")";
				if($i > 10){
					fwrite($fp,$text);
					$text = "";
				}
			}
			$text .= ";\n";
			fwrite($fp,$text);
		}
		fclose($fp);
	}
?>
