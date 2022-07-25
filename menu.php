<?php
	echo "
		<ul>
	";

	if ($_SESSION["page"] != "index")
		echo "
			<li>
			    <a href = 'index.php'>
				    <img src = 'img/k_home.png' alt='"._l('Tooltips/Home')."'/>
				</a>
			</li>
		";

	echo "
		<li id='k_vk'>
			<a href = '#'>
				<div id='vk_like'></div>
			</a>
		</li>
	";

	if ($_SESSION["page"] != "game"){
		if ($_SESSION["page"] != "forum")
			echo "
			<li>
				<a href = 'forum.php'>
					<img src = 'img/k_book.png' alt='"._l('Tooltips/Note book')."'/>
				</a>
			</li>
			";

		if ($_SESSION["page"] == "profile")
			echo "
			<li>
				<a href = '#' onClick = \"f_windowInfoPopup ('user_top', ".$_SESSION["id"].");\">
					<img src = 'img/k_stat.png' alt='"._l('Tooltips/Your statistic')."'/>
				</a>
			</li>
			";
// если пользователь авторизовался
		if ($_SESSION["page"] != "profile"
		&& ($_SESSION["dopusk"]=="yes" || $_SESSION["dopusk"]=="admin")){
			echo "
			<li id = 'k_profile' alt='"._l('Tooltips/Your profile')."'>
				<a href = 'profile.php'>
			";
			$result = f_mysqlQuery ("SELECT F_bette FROM users WHERE id=".$_SESSION["id"].";");
			$data = isset($result) ? mysqli_fetch_row($result) : [0];
			if ($data[0] == 1)
			    echo "
					<div id = 'letter'></div>
				";
			echo "
					<img src = 'img/k_profile.png' alt='"._l('Tooltips/Your profile')."'/>
				</a>
			</li>
			";
		}
	}
	else{
		echo "
		<li id = 'k_sound'>
			<a href = '#' onClick = 'f_sound_off();'>
				<img src = 'img/k_sound_".$_COOKIE["sound"].".png' alt='"._l('Tooltips/Sound')."'/>
			</a>
		</li>
		<li>
			<a href = '#' onClick = \"f_windowInfoPopup ('user_game', '".$theme."');\">
				<img src = 'img/k_stat.png' alt='"._l('Tooltips/Game statistic')."'/>
			</a>
		</li>
		<li id = 'k_pauseGame'>
			<a href = '#' onClick = \"f_windowInfoPopup ('pause');\">
				<img src = 'img/k_pause.png' alt='"._l('Tooltips/Pause')."'/>
			</a>
		</li>
		<li id = 'k_newGame'>
			<a href = '#'>
				<img src = 'img/k_new_game.png' alt='"._l('Tooltips/New game')."'/>
			</a>
		</li>
		<li id = 'k_revert'>
			<a href = '#' onClick = 'f_revertLastMotion();'>
				<img src = 'img/k_revert.png' alt='"._l('Tooltips/Revert')."'/>
			</a>
		</li>
		<li id = 'k_endGame'>
			<a href = '#' onClick = 'f_endGame ();'>
				<img src = 'img/k_save.png' alt='"._l('Tooltips/Save game')."'/>
			</a>
		</li>
		";
	}
	if ($_SESSION['dopusk'] == 'admin' && $_SESSION["page"] != "admin" && $_SESSION["page"] != "game")
		echo "
		<li>
			<a href = 'admin.php'>
				<img src = 'img/k_admin.png' alt='Admin'/>
			</a>
		</li>
		";

    if ($_SESSION['page'] != 'admin' && $_SESSION["page"] != "reg")
		echo "
			<li id = 'k_help'>
				<a href = '#' onClick = f_windowInfoPopup('text_help');>
					<img src = 'img/k_help.png' alt='"._l('Tooltips/Information')."'/>
				</a>
			</li>
		";

	if ($_SESSION["page"] != "game")
		echo "
		<li id = 'k_lang'>
			<a href = '#' onClick = 'f_changeLanguage();'>
				<img src = 'img/k_lang_".$_COOKIE["lang"].".png' alt='"._l('Tooltips/Language')."'/>
			</a>
		</li>
		";

	if ($_SESSION["page"] != "game" && ($_SESSION["dopusk"]=="yes" || $_SESSION["dopusk"]=="admin")){
		echo "
		<li>
			<a href = 'index.php?exit=true' onClick = 'VK.Auth.logout();'>
				<img src = 'img/k_exit.png' alt='"._l('Tooltips/Exit')."'/>
			</a>
		</li>
		";
	}

	echo "
	</ul>
	";


	echo "
	<div id = 'blockAuth' >
	";

	if ($_SESSION['page'] == 'game' || $_SESSION["dopusk"] == "yes" || $_SESSION["dopusk"] == "admin"){
		echo "
		<i>".$_SESSION["login"]."</i>
		<i id = 'myNballov'>0</i>
		<i class='avatar'>".f_img(2, $_SESSION["id"])."</i>";
	}
	else{
	echo "
	<form method = 'post' action = '' name = 'Registration'>
		<i>"._l('Login')."</i>
		<input class = 'border_inset' type='text' name='login' size='9' MAXLENGTH='15'/>
		<div class = 'k_vk' onclick='VK.Auth.login(authInfo);'></div>
		<i>"._l('Password')."</i>
		<input class = 'border_inset' type='password' name='pass' size='9' MAXLENGTH='15'/>
		<div class = 'k_enter'><input class = 'submit' type = 'submit' value = '.'/></div>
		<a id = 'registration' class = 'k_reg' href = 'reg.php' onClick = 'VK.Auth.logout();'>
			<img src = 'img/k_reg.png' alt='"._l('Tooltips/Reg')."'/>
		</a>
	</form>
	";
}
echo "
    </div>
";
?>
