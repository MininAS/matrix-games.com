<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	if (empty($user) || $user == 0)
		exit ('
			{
				"res": "002",
				"message": "Отсутствует имя, кому отправлять сообщение."
			}
		');

    $status = f_saveUserMessage ($user, $string);
	echo ($status);
?>
