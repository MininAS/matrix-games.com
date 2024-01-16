<?php
	require "init.php";

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$text=trim($messageText);
	$status = f_checkLengthMessage($text);
	if ($status != "Alright")
	 	exit($status);

	if (empty($theme))
		exit ('
			{
				"res": "002",
				"message": "'._l("Forum/The topic where you want to save the message is absent.").'"
			}
		');

	$text = f_convertSmilesAndTagFormat($text);
	$theme_name = getForumMessageById($theme)['text'];
	if (f_mysqlQuery ("INSERT forum (theme, author, text, time, date)
				VALUE (
					".$theme.",
					".$_SESSION["id"].",
					'".$text."',
					'".date("H:i")."',
					'".date("y.m.d")."'
				);
			")
	){
		f_mysqlQuery ("
			UPDATE users
			SET N_mess=N_mess+1
			WHERE id=".$_SESSION["id"].";"
		);
		f_mail (1, "
			На форуме было добавлено новое сообщение пользователем: ".getUserLogin($_SESSION["id"])."(".$_SESSION["id"].")
			- в теме: ".$theme_name."(".$theme.")
			- текст: ".$text
		);
		log_file ("
			Отправил сообщение:
			- в теме: ".$theme_name."(".$theme.")
		    - текст: ".$text
		);
		echo ('
			{
				"res": "200",
				"message": "'._l("Forum/The message is saved.").'"
			}
		');
	}
	else
		echo ('
			{
				"res": "100",
				"message": "'._l("Forum/The message is not saved.").'"
			}
		');
?>
