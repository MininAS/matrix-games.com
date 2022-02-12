<?
	require ("function.php");
	require ("sess.php");

	if (isset ($_COOKIE["vk_app_2729439"])){
		$member = authOpenAPIMember();
		if($member !== FALSE){
			if (isset($_POST["login"]) && isset($_POST["pass"]))
					if (preg_match("/.{4,}/", $_POST["pass"])) require ("auth.php");
			if ($_SESSION["dopusk"] == "no" && $_SESSION["id"] == ""){
				if (preg_match("/[a-zA-Z0-9А-Яа-я_\.\-\@\Ё]+/", $_POST["login"]) &&
					preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $_POST["e_mail"])){
					$qwery = f_mysqlQuery ("SELECT login FROM users;");
					$flag = true;
					while ($data = mysql_fetch_row($qwery))
					    if ($data[0] == $_POST["login"]){
							$flag = false; break;
						}
					if ($flag == true)
					{
						$_SESSION["login"] = $_POST["login"];
						$_SESSION["dopusk"] = "yes";
						f_mysqlQuery ("INSERT users (
							               id_vk, login, pass,
										   last_name, first_name,
										   dopusk, time_R, data_R,
										   ip_R, time, data, ip,
										   mail, N_game, N_ballov,
										   N_mess, N_visit, F_mailG,
										   F_mail, F_bette, lang)
									VALUES ('".$member["id"]."',
										    '".$_POST["login"]."',
											'".$_POST["pass"]."',
											'".$_POST["last_name"]."',
										    '".$_POST["first_name"]."',
											'yes',
											'".date ("H:i")."',
											'".date ("y.m.d")."',
											'".getenv ("REMOTE_ADDR")."',
											'".date ("H:i")."',
											'".date ("y.m.d")."',
											'".getenv ("REMOTE_ADDR")."',
											'".$_POST["e_mail"]."',
											0, 0, 0, 0, 1, 1, 0,
											'".$_COOKIE["lang"]."'
									);
						");
						$log = "Регистрация пользователя ".$_SESSION["login"]." через VK."; log_file ($log);
						$_SESSION["id"] = mysql_insert_id();
						if (isset ($_POST["photo"])) save_avatar ($_SESSION["id"], $_POST["photo"]);
						message ();
					}
					else echo ('
							{
								"res": "100",
								"message": "'._l("Profile/The name is already existed, please use another name.").'"
							}
						');
				}
				else echo ('
						{
							"res": "100",
							"message": "'._l("Profile/Some parameter is invalid.").'"
						}
					');
			}
			else {
				f_mysqlQuery ("UPDATE users
					           SET id_vk='".$member["id"]."',
							       last_name='".$_POST['last_name']."',
								   first_name='".$_POST['first_name']."'
							   WHERE id=".$_SESSION["id"].";
				");
				$log = "Слияние аккаунтов пользователя ".$_SESSION["login"]." с VK.";
				log_file ($log);
				if (isset ($_POST["photo"]) && !file_exists('avatar/'.$_SESSION["id"]))
				    save_avatar ($_SESSION["id"], $_POST["photo"]);
				message ();
			}
		}
	}
	else if ($_SESSION["dopusk"] == "no" && $_SESSION["id"] == "")
	{
		$reg = 0;
// Регистрация
		if (preg_match("/[a-zA-Z0-9А-Яа-я_\.\-\@\Ё]+/", $_POST["login"])){
			$qwery = f_mysqlQuery ("SELECT login FROM users;");
			$flag = true;
			while ($data = mysql_fetch_row($qwery)) if ($data[0] == $_POST["login"]) {$flag = false; break;}
			if ($flag == true) $reg++;
		}
		if (preg_match("/.{4,}/", $_POST["pass"])) $reg++;
		if (preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $_POST["e_mail"])) $reg++;
		if ($reg == 3){
			$_SESSION["login"] = $_POST["login"];
			$_SESSION["dopusk"] = "yes";
			f_mysqlQuery ("INSERT users (
				               login, pass, dopusk,
							   time_R, data_R, ip_R,
							   time, data, ip,  mail,
							   N_game, N_ballov, N_mess,
							   N_visit, F_mailG, F_mail,
							   F_bette, lang)
						VALUES ('".$_POST["login"]."',
							    '".$_POST["pass"]."',
								'yes',
								'".date ("H:i")."',
						        '".date ("y.m.d")."',
								'".getenv ("REMOTE_ADDR")."',
								'".date ("H:i")."',
								'".date ("y.m.d")."',
								'".getenv ("REMOTE_ADDR")."',
								'".$_POST["e_mail"]."',
								0, 0, 0, 0, 1, 1, 0,
								'".$_COOKIE["lang"]."'
						);
			");
			$log = "Регистрация пользователя ".$_SESSION["login"];
			log_file ($log);
			$_SESSION["id"] = mysql_insert_id();
			message ();
		}
		else {
			echo ('
				{
					"res": "100",
					"message": "'._l("Profile/The name is already existed, please use another name.").'"
				}
			');
		}
	}
	else {
		echo ('
			{
				"res": "100",
				"message": "'.l("Profile/You are already authorized.").'"
			}
		');
	}

	function message (){
		echo ('
			{
				"res": "200",
				"message": "'._l("Profile/Registration was successful.").'"
			}
		');
		if (isset ($_POST["e_mail"])) f_mail ($_SESSION["id"],
		_l("Mails/Register on the Logic Matrix Games website!")."<br>".
		_l("Mails/Hello!")."<br>".
		_l("Mails/Thank you for visiting our website.")."<br>".
		_l("Mails/If you like simple logic games, we will be glad to see you definitely again and again.")."<br>".
		_l("Mails/Play, save new games and compete with other users by opening recorded games.")."<br>".
		_l("Mails/For a more details use the help in the menu.")."<br><br>".
		_l("Mails/Good luck!"));
	}
?>
