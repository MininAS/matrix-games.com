<?php
	require "init.php";
// Удаление аккаунта
	if (!$regEdit == "9" || !$_SESSION["dopusk"] == "yes") {
		require ("display_non_authorization.php");
		exit;
	}

	$result = f_mysqlQuery ("SELECT * FROM users WHERE
	    id=".$_SESSION["id"]." AND
	    login='".$_SESSION["login"]."' AND
	    pass='".$_POST["passwordDeletion"]."';
    ");

	if (mysqli_num_rows($result) != 1) {
		$text = 'Invalid user data.';
		$log = ''; log_file ($log);
		require ("display_non_authorization.php");
		exit;
	}

	$result = f_mysqlQuery ("DELETE FROM users WHERE
        id=".$_SESSION["id"]." AND
		login='".$_SESSION["login"]."' AND
		pass='".$_POST["passwordDeletion"]."';
	");

	f_mysqlQuery ("DELETE FROM users_mess WHERE id_user=".$_SESSION["id"].";");
	if (file_exists("avatar/".$_SESSION["id"]."_1.jpeg"))
	    unlink ("avatar/".$_SESSION["id"]."_1.jpeg");
	if (file_exists("avatar/".$_SESSION["id"]."_2.jpeg"))
		unlink ("avatar/".$_SESSION["id"]."_2.jpeg");
	if (file_exists("avatar/".$_SESSION["id"]."_3.jpeg"))
		unlink ("avatar/".$_SESSION["id"]."_3.jpeg");
	$text = "Profile/Account has removed.";
	$log = "Пользователь удалил свой аккаунт."; log_file ($log);
	$_SESSION["id"] = "";
	$_SESSION["login"] = "";
	$_SESSION["dopusk"] = "no";
    require ("display_non_authorization.php");
?>
