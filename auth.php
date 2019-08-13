<?
$qwery = f_mysqlQuery ("SELECT pass, id, login, dopusk FROM users GROUP BY id;");
$text_info = "Логин или пароль были введены не верно.";
while ($data = mysql_fetch_row($qwery))
{
	if (preg_match("/".$data[2]."/i", $_POST["login"])  && $data[0] == $_POST["pass"])
	{
		$_SESSION["dopusk"] = $data[3]; $_SESSION["id"] = $data[1];
		$_SESSION["login"] = $data[2];
		$log = "Авторизациzs."; log_file ($log);
		f_mysqlQuery ("UPDATE users SET N_visit=N_visit+1, data='".date ("y.m.d")."', time='".date ("H:i")."', ip='".getenv ("REMOTE_ADDR")."' WHERE id=".$_SESSION["id"].";");
		unset ($text_info);
		break;
	}
}
?>
