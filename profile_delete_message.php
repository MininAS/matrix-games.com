<?php
	require "init.php";

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin") {
		require ("display_non_authorization.php");
		exit;
	}

	$result = f_mysqlQuery ("SELECT id_tema FROM users_mess WHERE id=".$mess.";");
	if (mysqli_num_rows($result) != 1) {
		echo ('
			{
				"res": "100",
				"message": "'._l("Forum/The message are not exist.").'"
			}
		');
		exit;
	}

	$data = mysqli_fetch_row($result);
	if ($_SESSION["id"] != $data[0]) {
		echo ('
			{
				"res": "100",
				"message": "'._l("Forum/The message is not your.").'"
			}
		');
		exit;
	}

	$result = f_mysqlQuery ("
		UPDATE users_mess
		SET basket=1
		WHERE id=".$mess.";
	");
	$count = isset($result) ? mysqli_affected_rows ($DB_Connection) : 0;
	if ($count == 1) {
		log_file ("Строка №".$mess." отмечен как удаленный.");
		echo ('
			{
				"res": "200",
				"message": "'._l("Forum/The message has removed.").'"
			}
		');
	}
	elseif ($count == 0)
		echo ('
			{
				"res": "100",
				"message": "'._l("Forum/The message is not existed.").'"
			}
		');
	else
		echo ('
			{
				"res": "100",
				"message": "'._l("Forum/The message has not removed.").'"
			}
		');
?>
