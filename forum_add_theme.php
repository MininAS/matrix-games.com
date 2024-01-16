<?php
	require "init.php";

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin") {
		require ("display_non_authorization.php");
		exit;
	}

	if ($theme != 0) {
		$theme_info = getForumMessageById($theme);
		if ($theme_info['theme'] != 0){
			echo('
				{
					"res": "001",
					"message": "'._l("Forum/The maximum nesting depth is two topics.").'"
				}
			');
			return;
		}
	} else {
		$theme_info['text'] = 'В корне';
	}

	$new_str = trim ($messageText);
	$new_str = f_convertSmilesAndTagFormat($new_str);
	if (empty($new_str)){
		echo('
			{
				"res": "101",
				"message": "'._l("Forum/Cannot create a topic with an empty name.").'"
			}
		');
		return;
	}
	if (strlen($new_str) < 5){
		echo('
			{
				"res": "102",
				"message": "'._l("Forum/Use more long name.").'"
			}
		');
		return;
	}
	if (strlen($new_str) > 150){
		echo('
			{
				"res": "103",
				"message": "'._l("Forum/Use more short name.").'"
			}
		');
		return;
	}

	$result = f_mysqlQuery("
		SELECT *
		FROM forum
		WHERE theme=".$theme."
			AND bin=0
			AND text='".$new_str."';
	");
	$count = mysqli_num_rows($result);
	if ($count != 0){
		echo('
			{
				"res": "104",
				"message": "'._l("Forum/The topic is existed already.").'"
			}
		');
		return;
	}

	$new_str = str_replace ("<", "&#60", $new_str);
	$new_str = str_replace (">", "&#62", $new_str);
	$new_str = str_replace ("\r\n", "<BR>", $new_str);
	if (f_mysqlQuery ("
			INSERT forum (theme, status, author, text, time, date)
			VALUE (
				".$theme.",
				1,
				".$_SESSION["id"].",
				'".$new_str."',
				'".date("H:i")."',
				'".date("y.m.d")."'
			);
		")
	) {
		echo ('
			{
				"res": "200",
				"message": "'._l("Forum/The topic is saved.").'"
			}
		');
		f_mail (1, "
			На форуме была создана новая тема пользователем: ".getUserLogin($_SESSION["id"])."(".$_SESSION["id"].")
			- в теме: ".$theme_info['text']."(".$theme.")
			- название: ".$new_str
		);
		log_file ("
			Создал тему в:
			- в теме: ".$theme_info['text']."(".$theme.")
			- название: ".$new_str
		);
	}
	else
		echo ('
			{
				"res": "100",
				"message": "'._l("Forum/The topic is not saved.").'"
			}
		');
