<?php

if (!$DB_Connection) {
	return;
}

$query = f_mysqlQuery ("
	SELECT pass, id, login, dopusk, lang
	FROM users
	WHERE UPPER(login) = UPPER('".$_POST["login"]."');
");

$GLOBALS['INSTANT_MESSAGE'] = _l("Login or password were invalid.");
if ($data = mysqli_fetch_row($query)) {
	if (preg_match("/".$data[2]."/i", $_POST["login"])  && $data[0] == $_POST["pass"]) {
		$_SESSION["dopusk"] = $data[3];
		$_SESSION["id"] = $data[1];
		$_SESSION["login"] = $data[2];
		$_SESSION["lang"] = $_COOKIE["lang"] = $data[4];
		setcookie("lang", $data[4], time()+31536000);
		$log = "Авторизация.";
		log_file ($log);
		f_mysqlQuery ("
			UPDATE users SET N_visit=N_visit+1,
			 	data='".date ("y.m.d")."',
				time='".date ("H:i")."',
				ip='".getenv ("REMOTE_ADDR")."'
			WHERE id=".$_SESSION["id"].";
		");
		$GLOBALS['INSTANT_MESSAGE'] = 'none';
	}
}
?>
