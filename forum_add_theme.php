<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}
	
	if ($theme != 0){
		$data = mysql_fetch_row(f_mysqlQuery('SELECT id_tema FROM forum WHERE id='.$theme.';'));
		if ($data[0] != 0){
			echo('
				{
					"res": "001",
					"message": "Не стоит так далеко углублять вложенные папки."
				}
			');
			return;
		}
	}

	$new_str = trim ($string);
	$new_str = f_convertSmilesAndTagFormat($new_str);
	if (empty($new_str)){
		echo('
			{
				"res": "101",
				"message": "Нельзя создать тему с пустым именем."
			}
		');
		return;
	}
	if (strlen($new_str) < 5){
		echo('
			{
				"res": "102",
				"message": "Слишком короткое имя."
			}
		');
		return;
	}
	if (strlen($new_str) > 150){
		echo('
			{
				"res": "103",
				"message": "Слишком длинное имя."
			}
		');
		return;
	}
	$data = mysql_fetch_row(f_mysqlQuery('SELECT * FROM forum WHERE id_tema='.$theme.' AND basket=0 AND text="'.$new_str.'";'));
	if ($data){
		echo('
			{
				"res": "104",
				"message": "Тема с таким именем уже существует."
			}
		');
		return;
	}

	$new_str = str_replace ("<", "&#60", $new_str);
	$new_str = str_replace (">", "&#62", $new_str);
	$new_str = str_replace ("\r\n", "<BR>", $new_str);
	if (f_mysqlQuery ("INSERT forum (id_tema, status, id_user, text, time, data)
				VALUE (
					".$theme.",
					1,
					".$_SESSION["id"].",
					'".$new_str."',
					'".date("H:i")."',
					'".date("y.m.d")."'
				);
			")
		){
		echo ('
			{
				"res": "200",
				"message": "Новая тема создана."
			}
		');
		$data = mysql_insert_id ();
		$log = "Создание новой темы №".$data; log_file ($log);
		f_mail (1, "На форуме было добавлена тема: ".$new_str." в теме = ".$theme);
	}
	else
		echo ('
			{
				"res": "100",
				"message": "Не удалось создать новую тему. Попробуйте еще раз.
				В случае неудачи обратитесь пожалуйста к администратору сайта."
			}
		');
