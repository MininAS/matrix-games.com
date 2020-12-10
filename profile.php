<?php
	require ("function.php");
	require ("sess.php");
	$_SESSION["page"] = "profile";
	$log = "..."; log_file ($log);

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	f_mysqlQuery ("UPDATE users SET F_bette=0 WHERE id=".$_SESSION["id"].";");

// Запись новового изображения
	if ($regEdit == "3")
	{
		if (isset ($_GET['photo']))
			save_avatar($_SESSION["id"], $_GET['photo']);
		else if (is_uploaded_file ($_FILES ["avatar"]["tmp_name"]))
		{
			$avatar=$_FILES ["avatar"]["tmp_name"];
			$avatar_type=$_FILES ["avatar"]["type"];
			$avatar_size=$_FILES ["avatar"]["size"];
			if ($avatar_size<=20000000 &&
			      ($avatar_type=="image/jpg" || $avatar_type=="image/jpeg"
				|| $avatar_type=="image/png" || $avatar_type=="image/gif"))
					save_avatar($_SESSION["id"],$avatar);
			else $text_info = "Простите изображение не удовлетворяет параметрам.";
		}
		else $text_info = "Не верный формат полученных данных.";
	}

// Изменение личных данных
	if ($regEdit == "102")
	{
		$data = mysql_fetch_row (f_mysqlQuery ("SELECT pass FROM users WHERE id=".$_SESSION["id"].";"));
		if ($_POST["value3"]==$data[0])
		if ($_POST["value1"]==$_POST["value1"])
		if (f_mysqlQuery("UPDATE users SET pass='".$_POST["value1"]."' WHERE id=".$_SESSION["id"].";")) $text_info = "Пароль изменен.";
	}
	if ($regEdit == "111") if (f_mysqlQuery("UPDATE users SET mail='".$_POST["value1"]."' WHERE id=".$_SESSION["id"].";")) $text_info = "Адрес изменен.";
	if ($regEdit == "109")
	{
		if ($_POST["value1"]!=1) $_POST["value1"] = 0;
		if ($_POST["value2"]!=1) $_POST["value2"] = 0;
		if (f_mysqlQuery("UPDATE users SET F_mailG=".$_POST["value1"].", F_mail=".$_POST["value2"]." WHERE id=".$_SESSION["id"].";")) $text_info = "Флаги отсылок изменены.";
	}

	$body = "
	<div class = 'windowSite'>
		<ul class = 'windowTitle'>
			<li>"._l("Notebook/Messages")."</li>
		</ul>
		<div id = 'messageWindow'>
		</div>
	</div>";

// Форма отправки сообщений

	$body .= "
	<div id = 'formSendMessage' class = 'formSendMessage windowSite'>
		<ul class = 'windowTitle'><li>"._l("Notebook/To answer")."</li></ul>
		<select id = 'dropDownUserList' name = 'user'>
			<option value = '0'>Кому:</option>
		</select>
		<textarea id = 'string' name = 'string' cols = '64'  rows = '2'></textarea>
		<div class = 'k_smile' onClick=\"f_windowInfoPopup('smile');\"></div>
		<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'reset'></div>
	</div>";

// Редактирование личных данных -----------------------------------------------------------------------------------------------------------------------
	$data = mysql_fetch_row (f_mysqlQuery ("SELECT login, mail, F_mailG, F_mail FROM users WHERE id=".$_SESSION["id"].";"));
	$body .= "
	<div id = 'windowSettingsProfile' class = 'windowSite'>
		<ul class = 'windowTitle'><li>"._l("Profile/Setting")."</li></ul>
		<form enctype = 'multipart/form-data' method = 'post' name = 'avatara' accept = 'image/*'>
			<div align = 'right'>"._l("Profile/Change image:")." </div>
			<div class = 'small'>"._l("Profile/Files type:")." GIF, JPEG, PNG.</div>
			<div>
				<input type = 'hidden' name = 'MAX_FILE_SIZE' value='20000000'/>
			    <input class = 'border_inset' value = 'Привет' placeholder='Choose File'
				    type='file' name='avatar'
					size='30' maxlenght='30'
					accept = 'image/gif, image/jpeg, image/jpg, image/png'/>
				<input type = 'hidden' name = 'regEdit' value = '3'/>
			</div>";
	if (isset ($_COOKIE["vk_app_2729439"]) && $_SESSION["id"] != "" && ($_SESSION["dopusk"]='yes' || $_SESSION["dopusk"]='admin'))
	{
		$body .= "<div class = 'k_vk' onClick='redirect_vk_photo_url();'></div>";
	}
	$body .= "
			<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'key'/></div>
		</form>

<!--Замена пароля-->
		<form METHOD='POST' ACTION='#' NAME='password'>
			<div>"._l("Profile/Old password:")."<input TYPE='password' NAME='value3' SIZE='10' MAXLENGTH='15'/></div>
			<div>"._l("Profile/New password twice:")."</div>
			<div>
				<input TYPE='password' NAME='value1' SIZE='10' MAXLENGTH='15'/>
				<input TYPE='password' NAME='value2' SIZE='10' MAXLENGTH='15'/>
				<input TYPE='hidden' NAME='regEdit' VALUE='102'/>
			</div>
			<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'key'/></div>
		</form>

<!--Замена e-mail-а-->
		<form METHOD='POST' ACTION='#' NAME='e-mail'>
			<div></div>
			<div>"._l("Profile/Change email:")."</div>
			<div>
				<input TYPE='text' VALUE='".$data[1]."' NAME='value1' SIZE='20' MAXLENGTH='50'/>
				<input TYPE='hidden' NAME='regEdit' VALUE='111'/>
			</div>
			<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'key'/></div>
		</form>

<!--Замена разрешения на отправку почты-->
		<form METHOD='POST' ACTION='#' NAME = 'disable'>
		  <div></div>
			<div>
				<input TYPE = 'checkbox' VALUE = '1' NAME = 'value1' ";
		if ($data[2] == 1) $body .= "checked";
		$body .= "/><i> - "._l("Profile/sending game emails")."</i>
			</div>
			<div>
				<input type = 'hidden' NAME = 'regEdit' VALUE = '109'>
				<input type = 'checkbox' VALUE = '1' NAME = 'value2' ";
		if ($data[3] == 1) $body .= "checked";
		$body .= "/><i> - "._l("Profile/sending service emails")."</i>
			</div>
			<div class = 'k_enter'><input class = 'submit' type = 'submit' name = 'key'/></div>
		</form>

<!--Удаление аккаунта-->
		<div'><a href='#' onClick = \"f_windowInfoPopup ('accaunt-delet');\">"._l("Profile/Remove account")."</a></div>
		<div id = 'accaunt-delet' class = 'invisible_block' style = \"display: none;\">
			<p class = 'big'>"._l("Profile/Are you sure you want to delete your account and leave this site?")."
			<form ACTION = 'exit.php' name = 'exit'>
				<div class = 'k_enter'><input class = 'submit' type = 'submit' NAME='reset'></div>
				<input type = 'hidden' name = 'regEdit' value = '9'>
			</form>
		</div>";

	$body .= "
	<script defer type = 'text/javascript' language = 'JavaScript' src = 'profile.js?lastVersion=11'></script>";
require ("display.php");
?>
