<?php

	$DB_ru = @mysqli_connect("matrix-gam.mysql", "matrix-gam_mysql", "C_jrLY4b");
    $DB_localhost = @mysqli_connect("localhost", "root", "");
	if (!$DB_ru && !$DB_localhost){
		exit("Не удается найти сервер базы данных");
	}
	$DB_Connection = $DB_ru ? $DB_ru : $DB_localhost;
	mysqli_query ($DB_Connection, "SET NAMES 'utf8'");
    mysqli_select_db ($DB_Connection, "matrix-gam_db");

    function db(){
        global $DB_Connection;
		return $DB_Connection;
	}

	function getUserLogin($id){
		$result = f_mysqlQuery ("
			SELECT login
			FROM users
			WHERE id=".$id.";"
		);
		$count = mysqli_num_rows($result);
		if ($count == 1){
		    $data = mysqli_fetch_row($result);
			return $data[0];
		}
		else
			return "?????";
	}

	function f_mysqlQuery ($query) {
		$result = mysqli_query(db(), $query);
		if (!$result) {
			$result = "Запрос: ".$query." - выдал ошибку: ".mysqli_error(db())."/n";
			log_file ($result);
		}
		else return $result;
	}

	function getUserSubgameAmount ($game, $user){
		$result = f_mysqlQuery ("
			SELECT id
			FROM games_".$game."_com
			WHERE id IN (
				SELECT MIN(id)
				FROM games_".$game."_com
			    GROUP BY id_game
			)
			AND id_user=".$user.";"
		);
		if (@mysqli_num_rows($result))
		    return mysqli_num_rows($result);
		else
		    return 0;
	}

	function getSubgameСreator ($game, $subgame){
		$result = f_mysqlQuery ("
			SELECT *
			FROM games_".$game."
			WHERE id_game=".$subgame.";"
		);
		$count = mysqli_num_rows($result);
		if ($count != 1)
            return "none";
		$result = f_mysqlQuery ("
			SELECT id_user
			FROM games_".$game."_com
			WHERE id IN (
				SELECT MIN(id)
				FROM games_".$game."_com
				WHERE id_game=".$subgame."
			);"
		);
		$data = mysqli_fetch_row($result);
		return $data[0];
	}

    function getSubgameBestPlayer ($game, $subgame){
		$result = f_mysqlQuery ("
			SELECT id_user, users.login, score, users.lang
			FROM games_".$game."_com AS tb, users
			WHERE id_game=".$subgame." AND id_user=users.id
			ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;");
		$data = mysqli_fetch_row($result);
		$array = array (
			"id" =>    $data[0],
			"login" => $data[1],
			"score" => $data[2],
			"lang" =>  $data[3],
		);
        return $array;
	}

# forum -------------------------------------------------------------------

    function getForumMessageById ($id){
		$result = f_mysqlQuery ("
			SELECT id_tema, id_user, text
			FROM forum
			WHERE id=".$id.";");
		$data = mysqli_fetch_row($result);
		$array = array (
			"id_tema" => $data[0],
			"id_user" => $data[1],
			"text" =>    $data[2],
		);
        return $array;
	}

// Ежедневное резевное сохранение базы данных
	function db_saver(){
		$result = f_mysqlQuery("SHOW TABLES");
		$tables = array();
		for($i = 0; $i < mysqli_num_rows($result); $i++){
			$row = mysqli_fetch_row($result);
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
			$row = mysqli_fetch_row($result);
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
			for($i = 0; $i < mysqli_num_rows($result2); $i++){
				$row = mysqli_fetch_row($result2);
				if($i == 0) $text .= "\n(";
				else  $text .= ",\n(";
				foreach($row as $v){
					$text .= "'".mysqli_real_escape_string(db(), $v)."',";
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
