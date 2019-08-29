<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$result = f_mysqlQuery ("SELECT id_tema FROM users_mess WHERE id=".$mess.";");
	if (mysql_num_rows ($result) != 1){
		echo ('
			{
				"res": "100",
				"message": "Сообщения не существует."
			}
		');
		exit;
	}

	$data = mysql_fetch_row ($result);
	if ($_SESSION["id"] != $data[0]){
		echo ('
			{
				"res": "100",
				"message": "Сообщение вам не принадлежит."
			}
		');
		exit;
	}

	$result = f_mysqlQuery ("UPDATE users_mess SET basket=1 WHERE id=".$mess.";");
	$count = mysql_affected_rows ();
	if ($count == 1) {
		log_file ("Строка №".$mess." отмечен как удаленный.");
		echo ('
			{
				"res": "200",
				"message": "Сообщение удалено."
			}
		');
	}
	else
	echo ('
		{
			"res": "100",
			"message": "Не удалось удалить. Попробуйте еще раз. В случае неудачи обратитесь пожалуйста к администратору сайта."
		}
	');
?>
