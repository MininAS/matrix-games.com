<?php
    $DB_localhost = @mysqli_connect("localhost", "root", "");
	$DB_ru =        @mysqli_connect("matrix-gam.mysql", "matrix-gam_mysql", "C_jrLY4b");
	$DB_Connection = $DB_ru ?: ($DB_localhost ?: false);

	if ($DB_Connection){
	    mysqli_query ($DB_Connection, "SET NAMES 'utf8'");
        $DB = mysqli_select_db ($DB_Connection, "matrix-gam_db");
		if (!$DB){
			log_file ("Не удается подключиться к базе данных - ".mysqli_error($DB_Connection)."/n");
			$instant_message = _l("Database was not connected by some reason.");
		}
	}
	else {
		log_file ("Не удается найти сервер базы данных. ".mysqli_connect_error ());
		$instant_message = _l("Database was not connected by some reason.");
		$DB = false;
	}

	function f_mysqlQuery ($query) {
		global $DB_Connection;
		global $DB;
		if ($DB_Connection && $DB){
		    $result = mysqli_query($DB_Connection, $query);
		    if (!$result){
			    $result = "Запрос: ".$query." - выдал ошибку: ".mysqli_error($DB_Connection)."/n";
			    log_file ($result);
		    }
		    else return $result;
		}
		else return null;
	}

# users ---------------------------------------------------------------

	function getUserLogin($id){
		$result = f_mysqlQuery ("
			SELECT login
			FROM users
			WHERE id=".$id.";"
		);
		$count = isset($result) ? mysqli_num_rows($result) : 0;
		if ($count == 1){
		    $data = mysqli_fetch_row($result);
			return $data[0];
		}
		else
			return "?????";
	}

    function getUserSettings(){
		$result = f_mysqlQuery ("
		    SELECT login, mail, F_mailG, F_mail
		    FROM users
			WHERE id=".$_SESSION["id"].";"
		);
        $data = isset($result) ? mysqli_fetch_row($result) : ["--", "--", 0, 0];
		$array = array (
			"login" =>          $data[0],
			"e_mail" =>         $data[1],
			"flag_game_mess" => $data[2],
			"flag_info_mess" => $data[3],
		);
        return $array;
	}


# games------------------------------------------------------------------------

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
		$count = isset($result) ? mysqli_num_rows($result) : 0;
		return $count;
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
		$data = isset($result) ? mysqli_fetch_row($result) : [0, 0, ""];
		$array = array (
			"id_tema" => $data[0],
			"id_user" => $data[1],
			"text" =>    $data[2],
		);
        return $array;
	}

// Ежедневное резевное сохранение базы данных
	function db_saver(){
		global $DB_Connection;
		global $DB;
		if (!$DB_Connection || !$DB) return;

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
					$text .= "'".mysqli_real_escape_string($DB_Connection, $v)."',";
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
