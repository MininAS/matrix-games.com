<?
	require ("function.php");
	require ("sess.php");
// ѕроверка формы на правильность ввода	 и проверка существует ли пользователь с таким именем
	$query = f_mysqlQuery ("SELECT login FROM users;");
	$flag = true;
	while ($data = mysqli_fetch_row($query)) if (strcasecmp($data[0], $_POST["login"]) == 0) {echo ("false"); $flag = false; break;}
	if ($flag == true) echo ("true")
?>
