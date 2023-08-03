<?php
    $DB_localhost = @mysqli_connect("localhost", "mininas", "3214");
	$DB_ru =        @mysqli_connect("matrix-gam.mysql", "matrix-gam_mysql", "C_jrLY4b");
	$DB_Connection = $DB_ru ?: ($DB_localhost ?: false);

	if ($DB_Connection){
	    mysqli_query ($DB_Connection, "SET NAMES 'utf8'");
        $DB = mysqli_select_db ($DB_Connection, "matrix-gam_db");
		if (!$DB){
			log_to_file ("Не удается подключиться к базе данных - ".mysqli_error($DB_Connection)."/n");
			$GLOBALS['INSTANT_MESSAGE'] = _l("Database was not connected by some reason.");
		}
	} else {
		log_to_file ("Не удается найти сервер базы данных. ".mysqli_connect_error ());
		$GLOBALS['INSTANT_MESSAGE'] = _l("SQL server was not connected by some reason.");
		$DB = false;
	}

	/**
	 * Запрос к БД с логированием ошибок
	 */
	function f_mysqlQuery($query){
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

// Пользователи ----------------------------------------------------------------

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


// games------------------------------------------------------------------------

	/**
	 * Вернуть количество раскладов в игре для игрока.
	 * @param string $game имя игры,
	 * @param int $user идентификатор пользователя.
	 * @return int
	 */
	function getUserLayoutAmount($game, $user){
		$result = f_mysqlQuery ("
			SELECT COUNT(*) AS count
			FROM games_".$game."_com
			WHERE id IN (
				SELECT MIN(id)
				FROM games_".$game."_com
			    GROUP BY id_game
			)
			AND id_user=".$user.";"
		);
		$count = isset($result) ? mysqli_fetch_row($result)[0] : 0;
		return $count;
	}

	/**
	 * Вернуть количество попыток в раскладе игры.
	 * @param string $game имя игры,
	 * @param int $canvasLayout идентификатор слоя.
	 * @return int
	 */
	function getAttemptAmount($game, $canvasLayout) {
		$result = f_mysqlQuery ("
			SELECT COUNT(*) as count
			FROM games_".$game."_com
			WHERE id_game=".$canvasLayout.";"
		);
		$count = isset($result) ? mysqli_fetch_row($result)[0] : 0;
		return $count;
	}

	function getLayoutСreator($game, $canvasLayout) {
		$result = f_mysqlQuery ("
			SELECT *
			FROM games_".$game."
			WHERE id_game=".$canvasLayout.";"
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
				WHERE id_game=".$canvasLayout."
			);"
		);
		$data = mysqli_fetch_row($result);
		return $data[0];
	}

	/**
	 * Возвращает лучшего игрока на поле с данными.
	 * @param string $game наименование игры
	 * @param int $canvasLayout идентификатор игры
	 * @return array id идентификатор,
	 *               login логин,
	 *               lang язык установленный пользователем,
	 *               score сумма очков в этом поле,
	 *               live время жизни в днях с последней выигрышной попытки.
	 */
    function getLayoutBestPlayer($game, $canvasLayout) {
		$result = f_mysqlQuery ("
			SELECT us.id, us.login, us.lang,
			       tb.score, DATEDIFF(NOW(), tb.data) AS live
			FROM games_".$game."_com AS tb, users AS us
			WHERE id_game=".$canvasLayout." AND id_user=us.id
			ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;");
		$count = mysqli_num_rows($result);
		if ($count != 1)
            return "none";
		$data = mysqli_fetch_row($result);
		$array = array (
			"id" =>    $data[0],
			"login" => $data[1],
			"lang" =>  $data[2],
			"score" => $data[3],
			"live" =>  $data[4],
		);
        return $array;
	}

// forum -------------------------------------------------------------------

    function getForumMessageById($id){
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

// Ежедневное резервное сохранение базы данных ----------------------------------

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
