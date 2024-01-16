<?php
	require "init.php";
	$_SESSION["page"] = "forum";

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$text=trim($messageText);
	$status = f_checkLengthMessage($text);
	if ($status != "Alright")
	 	exit($status);

	$text = f_convertSmilesAndTagFormat($text);
	$message_info = getForumMessageById($mess);
	$theme_name   = getForumMessageById($message_info['theme'])['text'];

	if ($_SESSION["id"] == $message_info['author']) {
		if (f_mysqlQuery ("
			UPDATE forum
			SET text='".$text."'
			WHERE id=".$mess.";")
		){
			f_mail (1, "
				На форуме было исправлено сообщение (".$mess.") пользователем: ".getUserLogin($_SESSION["id"])."(".$_SESSION["id"].")
				- в теме: ".$theme_name."(".$message_info['theme'].")
				- старый текст: ".$message_info['text']."
				- новый текст: ".$text
			);
			log_file ("
				Отредактировано сообщение (".$mess."):
				- в теме: ".$theme_name."(".$message_info['theme'].")
				- старый текст: ".$message_info['text']."
				- новый текст: ".$text
			);
			echo ('
				{
					"res": "200",
					"message": "'._l("Forum/The message is edited.").'"
				}
			');
		}
		else
			echo ('
				{
					"res": "100",
					"message": "'._l("Forum/The message editing is impossible.").'"
				}
			');
	}
	else
		echo ('
			{
				"res": "003",
				"message": "'._l("Forum/The message is not your.").'"
			}
		');
?>
