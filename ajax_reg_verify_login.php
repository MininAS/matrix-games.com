<?
	require ("function.php");
	require ("sess.php");
// ѕроверка формы на правильность ввода	 и проверка существует ли пользователь с таким именем
	$qwery = f_mysqlQuery ("SELECT login FROM users;");
	$flag = true;
	while ($data = mysql_fetch_row($qwery)) if (strcasecmp($data[0], $_POST["login"]) == 0) {echo ("false"); $flag = false; break;}
	if ($flag == true) echo ("true")
?>
