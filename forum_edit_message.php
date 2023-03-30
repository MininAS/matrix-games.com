<?php
	require "init.php";
	$_SESSION["page"] = "forum";

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$text=trim($newNotebookItemText);
	$status = f_checkLengthMessage($text);
	if ($status != "Alright")
	 	exit($status);

	$text = f_convertSmilesAndTagFormat($text);
	$result = f_mysqlQuery ("SELECT id_user, text FROM forum WHERE id=".$mess.";");
	$data = mysqli_fetch_row($result);
	if ($_SESSION["id"] == $data[0]) {
		if (f_mysqlQuery ("UPDATE forum SET text='".$text."' WHERE id=".$mess.";")){
			$log = "Исправил сообщение id=".$mess." в форуме с текстом (".$data[1].") на (".$text.")."; log_file ($log);
			f_mail (1, $log);
			echo ('
				{
					"res": "200",
					"message": "'._l("Notebook/The message is edited.").'"
				}
			');
		}
		else
			echo ('
				{
					"res": "100",
					"message": "'._l("Notebook/The message editing is impossible.").'"
				}
			');
	}
	else
		echo ('
			{
				"res": "003",
				"message": "'._l("Notebook/The message is not your.").'"
			}
		');
?>
