<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$text=trim($newNotebookItemText);
	$status = f_checkLengthMessage($text);
	if ($status != "Alright")
	 	exit($status);

	if (empty($theme))
		exit ('
			{
				"res": "002",
				"message": "'._l("Notebook/The topic where you want to save the message is absent.").'"
			}
		');

	$text = f_convertSmilesAndTagFormat($text);
	if (f_mysqlQuery ("INSERT forum (id_tema, id_user, text, time, data)
				VALUE (
					".$theme.",
					".$_SESSION["id"].",
					'".$text."',
					'".date("H:i")."',
					'".date("y.m.d")."'
				);
			")
		){
		f_mysqlQuery ("UPDATE users SET N_mess=N_mess+1 WHERE id=".$_SESSION["id"].";");
		f_mail (1, "На форуме было добавлено новое сообщение: ".$text." в теме = ".$theme);
		$log = "Отправил сообщение в форум в тему №".$theme; log_file ($log);
		echo ('
			{
				"res": "200",
				"message": "'._l("Notebook/The message is saved.").'"
			}
		');
	}
	else
		echo ('
			{
				"res": "100",
				"message": "'._l("Notebook/The message is not saved.").'"
			}
		');
?>
