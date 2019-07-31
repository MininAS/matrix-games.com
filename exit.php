<?php	
	require ("function.php");
	require ("sess.php");
// Удаление аккаунта
	if ($regEdit == "9" && $_SESSION["dopusk"] == "yes")
	{
		sql ("UPDATE users SET id_vk=0, login='-----', last_name='', first_name='', mail='',
				N_game=0, N_ballov=0, N_mess=0, N_visit=0, F_mailG=0, F_mail=0, pass='',
				F_bette=0 WHERE id=".$_SESSION["id"].";");
		sql ("DELETE FROM users_mess WHERE id_user=".$_SESSION["id"].";");
		unlink ("avatar/".$_SESSION["id"]."_1.jpeg");
		unlink ("avatar/".$_SESSION["id"]."_2.jpeg");
		unlink ("avatar/".$_SESSION["id"]."_3.jpeg");
		echo ("<center>Аккаунт удален.</center>");
		$log = "Пользователь удалил свой аккаунт."; log_file ($log); 
		$_SESSION["id"] = "";
		$_SESSION["login"] = "";
		$_SESSION["dopusk"] = "no";
	}
?>