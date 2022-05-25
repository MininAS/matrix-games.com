<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$result = f_mysqlQuery ("SELECT id_tema FROM users_mess WHERE id=".$mess.";");
	if (mysqli_num_rows($result) != 1){
		echo ('
			{
				"res": "100",
				"message": "'._l("Notebook/The message are not exist.").'"
			}
		');
		exit;
	}

	$data = mysqli_fetch_row($result);
	if ($_SESSION["id"] != $data[0]){
		echo ('
			{
				"res": "100",
				"message": "'._l("Notebook/The message is not your.").'"
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
				"message": "'._l("Notebook/The message has removed.").'"
			}
		');
	}
	else
	echo ('
		{
			"res": "100",
			"message": "'._l("Notebook/The message has not removed.").'"
		}
	');
?>
