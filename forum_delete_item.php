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
				"message": "'._l("Notebook/Such topic or message is not exist.").'"
			}
		');
		exit;
	}

	$data = mysql_fetch_row ($result);
	if ($_SESSION["id"] != $data[2]){
		echo ('
			{
				"res": "100",
				"message": "'._l("Notebook/The topic or the message is not your.").'"
			}
		');
		exit;
	}

	if ($data[6] == 1){
		$result = f_mysqlQuery ("SELECT id FROM forum WHERE id_tema=".$theme." AND basket=0;");
		if (mysql_num_rows ($result) != 0){
			echo ('
				{
					"res": "100",
					"message": "'._l("Notebook/The topic must be empty for removing.").'"
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
				"message": "'._l("Notebook/The topic or the message was removed.").'"
			}
		');
	}
	else
	echo ('
		{
			"res": "100",
			"message": "'._l("Notebook/The removing is impossible.").'"
		}
	');
?>
