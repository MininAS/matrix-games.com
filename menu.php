<?php
// Если пользователь не зарегестрирован то рисуем регистрацию
	echo ("
	<ul class = 'styleList'>");
	if ($_SESSION["page"] != "index")
	{
		echo ("
		<li><a href = 'index.php'><img src = 'img/k_home.png' alt='На главную'/></a></li>
		");
	}
	echo ("

		<li id='k_vk'><a href = '#'><div id='vk_like'></div></a></li>");
	if ($_SESSION["page"] != "game")
	{
		if ($_SESSION["page"] != "forum")	echo ("
			<li><a href = 'forum.php'><img src = 'img/k_book.png' alt='Книга пожеланий'/></a></li>");
		if ($_SESSION["page"] == "profile")	echo ("
			<li><a href = '#' onClick = \"window_info ('user_top', ".$_SESSION["id"].");\"><img src = 'img/k_stat.png' alt='Ваша статистика'/></a></li>");
// если пользователь авторизовался
		if ($_SESSION["page"] != "profile" && ($_SESSION["dopusk"]=="yes" || $_SESSION["dopusk"]=="admin"))
		{
			echo ("
			<li id = 'k_profile' alt='Ваш профиль'><a href = 'profile.php'>");
			$data = mysql_fetch_row (f_mysqlQuery ("SELECT F_bette FROM users WHERE id=".$_SESSION["id"].";"));
			if ($data[0] == 1) echo ("<div id = 'bette'></div>");
			echo ("<img src = 'img/k_profile.png' alt='Ваш профиль'/></a></li>");
		}
	}
	else
	{
		echo ("
		<li id = 'k_sound'><a href = '#' onClick = 'f_sound_off();'><img src = 'img/k_sound_".$_COOKIE["sound"].".png' alt='Звук'/></a></li>
		<li><a href = '#' onClick = \"window_info ('user_game', '".$theme."');\"><img src = 'img/k_stat.png' alt='Статистика игры'/></a></li>
		<li id = 'k_pauseGame'><a href = '#' onClick = \"window_info ('pause');\"><img src = 'img/k_pause.png' alt='Пауза'/></a></li>
		<li><a href = '#' onClick = 'f_newGame ();'><img src = 'img/k_newgame.png' alt='Новая игра'/></a></li>
		<li id = 'k_endGame'><a href = '#' onClick = 'f_endGame ();'><img src = 'img/k_save.png' alt='Сохранение игры'/></a></li>");
	}
	if ($_SESSION['dopusk'] == 'admin' && $_SESSION["page"] != "admin" && $_SESSION["page"] != "game")
	{
		echo ("
		<li><a href = 'admin.php'><img src = 'img/k_admin.png' alt='Админ'/></A></li>");
	}

	if ($_SESSION["page"] != "game" && ($_SESSION["dopusk"]=="yes" || $_SESSION["dopusk"]=="admin"))
	{
		echo ("
		<li><a href = 'index.php?exit=true' onClick = 'VK.Auth.logout();'><img src = 'img/k_exit.png' alt='Выход'/></a></li>");
	}
	echo ("
		<li id = 'k_help'><a href = '#' onClick = 'window_info(\"text_help\");'><img src = 'img/k_help.png' alt='Инструкция'/></a></li>
		</ul>");

	$text = "
	<div id = 'blockAuth' >
	";


		if ($_SESSION['theme'] == 'game' || $_SESSION["dopusk"]=="yes" || $_SESSION["dopusk"]=="admin")
		{
			$text .= "
		<i>".$_SESSION["login"]."</i>
		<i id = 'myNballov'>0</i>
		<i class='avatar'>".f_img(2, $_SESSION["id"])."</i>";
		}
		else
		{
		$text .= "
		<form method = 'post' action = '' name = 'Registration'>
			<i>Ник</i>
			<input class = 'border_inset' type='text' name='login' size='9' MAXLENGTH='15'/>
			<div class = 'k_vk' onclick='VK.Auth.login(authInfo);'></div>
			<i>Пароль</i>
			<input class = 'border_inset' type='password' name='pass' size='9' MAXLENGTH='15'/>
			<div class = 'k_enter'><INPUT class = 'submit' type = 'submit' value = '.'/></div>
			<a id = 'registration' class = 'k_reg' href = 'reg.php' onClick = 'VK.Auth.logout();'>
				<img src = 'img/k_reg.png' alt='Регистрация'/>
			</a>
		</form>";
	}
	$text .= "
	</div>";
echo ($text);
?>
