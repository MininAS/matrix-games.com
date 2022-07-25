<?php
	require ("function.php");
	require ("sess.php");
	if (isset ($_COOKIE["vk_app_2729439"]))
	{
		$member = authOpenAPIMember();
		if($member !== false)
		{
			$query = f_mysqlQuery ("SELECT `id`, `login`, `dopusk` FROM users WHERE id_vk = ".$member['id'].";");
			if ($data = mysqli_fetch_row($query))
			{
				$_SESSION["dopusk"] = $data[2]; $_SESSION["id"] = $data[0];
				$_SESSION["login"] = $data[1];
				$log = "VK authorization."; log_file ($log);
				f_mysqlQuery ("UPDATE users SET
					               N_visit=N_visit+1,
					               data='".date ("y.m.d")."',
								   time='".date ("H:i")."',
								   ip='".getenv ("REMOTE_ADDR")."'
							   WHERE id=".$_SESSION["id"].";");
				echo ('true');
			}
			else
			{
				echo ('false');
			}
		}
		else
		{
			$instant_message = _l("Invalid user data.");
		}
	}
?>
