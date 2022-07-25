<?php

    // Проверка существования имени пользователя в БД.

	require ("function.php");
	require ("sess.php");

	$query = f_mysqlQuery ("
	    SELECT id
		FROM users
		WHERE login='".$_POST["login"]."';
	");
	$count = isset($query) ? mysqli_num_rows($query) : 0;
	echo ($count);
?>
