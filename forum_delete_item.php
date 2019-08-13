<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$result = f_mysqlQuery ("SELECT * FROM forum WHERE id=".$theme.";");
	if (mysql_num_rows ($result) != 1){
		echo ('
			{
				"res": "100",
				"message": "Такой темы или сообщения не существует."
			}
		');
		exit;
	}

	$data = mysql_fetch_row ($result);
	if ($_SESSION["id"] != $data[3]){
		echo ('
			{
				"res": "100",
				"message": "Тема или сообщение вам не принадлежит."
			}
		');
		exit;
	}

	if ($data[1] == 1){
		$result = f_mysqlQuery ("SELECT id FROM forum WHERE id_tema=".$theme." AND basket=0;");
		if (mysql_num_rows ($result) != 0){
			echo ('
				{
					"res": "100",
					"message": "Для удаления тема должна быть пустой."
				}
			');
			exit;
		}
	}

	$result = f_mysqlQuery ("UPDATE forum SET basket=1 WHERE id=".$theme.";");
	$count = mysql_affected_rows ();
	if ($count == 1) {
		log_file ("Строка №".$theme." отмечен как удаленный.");
		echo ('
			{
				"res": "200",
				"message": "Тема или сообщение удалено."
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
