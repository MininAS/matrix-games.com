<?php
	require ("function.php");
	require ("sess.php");

	$log = "Запрос на удаление сообщения №".$mess.". ";
	if ($_SESSION["dopusk"] == "yes" || $_SESSION["dopusk"] == "admin")
	{
		$data=mysql_fetch_row(sql('SELECT id_user FROM forum WHERE id='.$mess.';'));
		if($data)
		{
			if ($data[0] == $_SESSION['id']){
				$query=sql ("DELETE FROM forum WHERE id=".$mess.";");
				if (mysql_affected_rows () == 1){
					echo ('
						{
							"res": "200",
							"message": "Сообщение было удалено."
						}
					');
					$log .= "Операция выполнена."; log_file ($log);
				}
				else{
					$message = "Ошибка удаления.
					 			Пожалуйста обратитесь к администратору сайта.";
					echo ('
						{
							"res": "100",
							"message": "'.$message.'"
						}
					');
					log_file ($message);
				}
			}
			else{
				$message = "Вы не являетесь автором этого сообщения.";
				echo ('
					{
						"res": "100",
						"message": "'.$message.'"
					}
				');
				log_file ($message);
			}
		}
		else{
			$message = "Сообщение отсутствует. Возможно его кто то удалил только, что.";
			echo ('
				{
					"res": "100",
					"message": "'.$message.'"
				}
			');
			log_file ($message);
		}
	}
?>
