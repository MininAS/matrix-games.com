<?
	require ("function.php");
	require ("sess.php");
	if (isset ($_COOKIE["vk_app_2729439"]))
	{
		$member = authOpenAPIMember();
		if($member !== false)
		{
			$qwery = sql ("SELECT `id`, `login`, `dopusk` FROM users WHERE id_vk = ".$member['id'].";");
			if ($data = mysql_fetch_row($qwery))
			{
				$_SESSION["dopusk"] = $data[2]; $_SESSION["id"] = $data[0];
				$_SESSION["login"] = $data[1];
				$log = "Авторизация через VK."; log_file ($log);
				sql ("UPDATE users SET N_visit=N_visit+1, data='".date ("y.m.d")."', time='".date ("H:i")."', ip='".getenv ("REMOTE_ADDR")."' WHERE id=".$_SESSION["id"].";");
				echo ('true');
			}
			else
			{
				echo ('false');
			}
		}
		else
		{
			$text_info = "Не верные данные пользователя.";
		}
	}
?>
