<?php
	require ("function.php");
	require ("sess.php");		$_SESSION["page"] = "admin";
	$log = "..."; log_file ($log);

	// Переменная сортировка сравнение по массиву
	if (!isset ($_GET["sort"])) $_GET["sort"] = "id";
	else
	{
		$a_sort = array ('id', 'login', 'data DESC, time DESC', 'N_visit DESC', 'N_ballov DESC', 'N_game DESC', 'N_mess DESC');
		if (!in_array ($_GET["sort"], $a_sort))
		{
			f_error('Неверные входящие данные:', ' параметр sort не соответствует допустимому значению. Sort='.$_GET["sort"], 'sess.php', 0);
			$_GET["sort"] = 'id';
		}
	}
// Блок аунтификации // Удаляем по баллу у каждого пользователя
	if ($_SESSION["dopusk"]!="admin")
	{
		echo ("
		Эта страница для администраторов сайта.
		<script type = 'text/javascript' language = 'JavaScript'>
			setTimeout (\"window.location.href='index.php';\", 3000);
		</script>");
		exit;
	}
	else if ($regEdit == "78") sql ("UPDATE users SET N_ballov=N_ballov-1 WHERE N_ballov>0;");

// Сохранение личного сообщения
	if ($regEdit == "1")
	{
		if ($user == "0")
		{
			$result=sql ("SELECT id FROM users;");
			while  ($data = mysql_fetch_row ($result)) f_messSave ('users_mess', $data[0], $string);
		}
		else f_messSave ('users_mess', $user, $string);
	}
// Сохранение личного письма
	if ($regEdit == "2" && $string != null)
	{
		if ($user == "0")
		{
		 	$result=sql ("SELECT id FROM users;");
			while ($data = mysql_fetch_row ($result)) f_mail ($data[0], "Новости с сайта LMG ==>>",$string);
		}
		else f_mail ($user, $string);
	}
// Изменение личных данных
	if ($regEdit == "103" && $_SESSION["dopusk"] == "admin") sql ("UPDATE users SET dopusk='".$_GET["value1"]."' WHERE id=".$user.";");

	$body = "";



	$body .= "
	<div id = 'messageWindow' class = 'windowSite'>
		<ul class = 'windowTitle'><li>На сайте зарегистрированы: </li></ul>
		<table width = '100%' border = '2' cellpadding = '2' cellspacing = '2'>
			<tr align=\"center\">
				<td><A href = 'admin.php?sort='id>ID</A></td>
				<td width = '20%'><A href = 'admin.php?sort=login'>Ник</A></td>
				<td><A href = 'admin.php?sort=N_visit DESC'>Заходил</A></td>
				<td width = '20%'><A href = 'admin.php?sort=data DESC, time DESC'>Был здесь</A></td>
				<td><A href = 'admin.php?sort=N_ballov DESC'>Баллы</A></td>
				<td><A href = 'admin.php?sort=N_game DESC'>Игры</A></td>
				<td><A href = 'admin.php?sort=N_mess DESC'>Сообщения</A></td>
				<td width = '20%'>Регистрация</td>
			</tr>
	</table>
	<div class = 'messageLists'>
	<table width = '100%' border = '2' cellpadding = '2' cellspacing = '2'>";
// Сортируем
	$result = sql("SELECT id, login, N_visit, time, data, N_ballov, N_game, N_mess, time_R, data_R FROM users ORDER BY ".$_GET["sort"].";");
	$i_Users = 0;
	while  ($data = mysql_fetch_row ($result))
	{
			$body .= "
		<TR align=\"center\">
			<td>".$data[0]."</td>
			<td  width = '20%'><P>
			".f_img (3, $data[0]);
			$body .= $data[1]."</td>
			<td><P class = 'big'>".$data[2]."</td>
			<td width = '20%'><P class = 'small'>".$data[3]."<BR>".$data[4]."</td>
			<td><P class = 'big'>".$data[5]."</td>
			<td><P class = 'big'>".$data[6]."</td>
			<td><P class = 'big'>".$data[7]."</td>
			<td width = '20%'><P class = 'small'>".$data[8]."<BR>".$data[9];
			$body .= "</td>
		</TR>\n";
			$i_Users ++;
 	}
	$body .= "
		</table>
	</DIV>
	<P class = 'small'>Всего нас - $i_Users</p>
	</div>";

// Отпарвка сообщений ========================================================================================
	$body .="";
	if ($_SESSION["dopusk"] == "yes" || $_SESSION["dopusk"] == "admin")
	{
		$body .= "
		<div id = 'formSendMessage' class = 'windowSite'>
			<ul class = 'windowTitle'><li>Отправить сообщение</li></ul>
			<form action='admin.php' name='send_mess'>
				<select  name = 'user'>
				<option value = ''>Кому:</option>
				<option value = '0'>Всем</option>\n";
		$result = sql("SELECT id, login FROM users ORDER BY login;");
		while ($data = mysql_fetch_row ($result))
		{
			$body .= "
			<option value = '".$data[0]."'>".$data[1]."</option>\n";
		}
		$body .= "</select>
				<textarea name = 'string' COLS = '40'  ROWS = '4'></textarea>
				<div class = 'k_smile'  onClick=\"window_info('smile');\"></div>
				<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'reset'></div>
				<input TYPE = 'hidden' name = 'regEdit' value=\"1\">
			</form>
		</div>";
	}

// Отпарвка писем ------------------------------------------------------------------------------------------------------------
	if ($_SESSION["dopusk"] == "yes" || $_SESSION["dopusk"] == "admin")
	{
		$body .= "
		<div id = 'formSendMessage' class = 'windowSite'>
			<ul class = 'windowTitle'><li>Отправить писем</li></ul>
			<form action='admin.php' name='send_mess_' >
				<select  name='user'>
				<option value=''>Кому:</option>
				<option value='0'>Всем</option>\n";
		mysql_data_seek ($result, 0);
		while ($data = mysql_fetch_row ($result))
		{
			$body .= "
				<option value = '".$data[0]."'>".$data[1]."</option>\n";
		}

		$body .= "</select>
				<textarea name = 'string' COLS='40'  ROWS='4'></textarea>
				<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'reset'></div>
				<input type = 'hidden' name = 'regEdit' value = '2'>
			</form>
		</div>";
	}
// Меню администратора ---------------------------------------------------------------------------------------------------------------------------------------------
	if (@$_SESSION["dopusk"]=="admin")
	{
		$body .= "
	<div class = 'windowSite'>
		<ul class = 'windowTitle'><li>Изменить статус аккаунта</li></ul>
		<form action = 'admin.php' name = 'users' width = '65%'>
			<select class = 'c_enterText' name = 'user'>
				<option class = 'c_enterText' value = '0'>Кому:</option>\n";
	mysql_data_seek ($result, 0);
	while ($data = mysql_fetch_row ($result))
	{
		$body .= "
				<option value = '".$data[0]."'>".$data[1]."</option>\n";
	}
	$body .= "
			</select>
			<select name='value1'>
				<option value = ''>Выберите статус</option>
				<option value = 'admin'>Администратор</option>
				<option value = 'yes'>Пользователь</option>
				<option value = 'no'>Ограниченный пользователь</option>
				<option value = 'tabu'>Запрещенный пользователь</option>
			</select>
			<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'reset'></div>
			<input type = 'hidden' name = 'regEdit' value = '103'>
		</form>
	</div>
	<div class = 'windowSite'>
		<p>Убавить по баллу у всех играков</p><a class = 'k_enter' href = 'admin.php?regEdit=78'></a>
	</div>";
	}
// Вставляем файл где описан объект из 6-ти ячеек
require ("display.php");
?>
