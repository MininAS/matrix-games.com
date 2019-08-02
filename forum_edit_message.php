<?php
	require ("function.php");
	require ("sess.php");		$_SESSION["page"] = "forum";

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin") {exit;}

	$text=trim($string);
	$status = f_checkLengthMessage($text);
	if ($status != "Alright")
	 	exit($status);

	$text = f_convertSmilesAndTagFormat($text);
	$result = sql ("SELECT id_user, text FROM forum WHERE id=".$mess.";");
	$data = mysql_fetch_row ($result);
	if ($_SESSION["id"] == $data[0]){
		if (sql ("UPDATE forum SET text='".$text."' WHERE id=".$mess.";")){
			$log = "Исправил сообщение id=".$mess." в форуме с текстом (".$data[1].") на (".$text.")."; log_file ($log);
			f_mail (1, $log);
			echo ('
				{
					"res": "200",
					"message": "Сообщение исправлено."
				}
			');
		}
		else
			echo ('
				{
					"res": "100",
					"message": "Не удалось перезаписать сообщение. Попробуйте еще раз. В случае неудачи обратитесь пожалуйста к администратору сайта."
				}
			');
	}
	else
		echo ('
			{
				"res": "003",
				"message": "Сообщение не пренадлежит вам."
			}
		');
?>
