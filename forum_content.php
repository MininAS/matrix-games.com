<?php
	require ("function.php");
	require ("sess.php");

$text = "";
	$result = f_mysqlQuery ("SELECT s1.id, s1.id_user, s1.text, s1.time, s1.data, s2.login FROM forum AS s1, users AS s2
					WHERE s1.id_tema=".$theme." AND s1.status=1 AND s1.basket=0 AND s1.id_user=s2.id;");
	$count = mysql_num_rows($result);
	if ($count >= 1) {
		$text .= "
		<ul class = 'messageLists'>";
			while ($data = mysql_fetch_row ($result)){
// Чья тема?
			$text .= "
			<li class = 'forum_theme selectable_list_item' item = ".$data[0].">
				<div class = 'message_autor'>
					<div class = 'avatar'>
				".f_img (3, $data[1]);
			$text .= "</div>
					<span>".$data[5]."</span>
					<p class = 'data text_insignificant'>".$data[3]." / ".$data[4]."</p>
				</div>

				<div class = 'text'>
					<p>".$data[2]."</p>";
			$data_ = mysql_fetch_row (f_mysqlQuery ("SELECT COUNT(*) FROM forum WHERE id_tema=".$data[0]." AND status=1 AND basket=0;"));
			$text .= "
					<span class = 'small'>";
			if ($data_[0]!=0) $text .= "Темы: #".$data_[0]." | ";
			$data_ = mysql_fetch_row (f_mysqlQuery ("SELECT COUNT(*) FROM forum WHERE id_tema=".$data[0]." AND status=0 AND basket=0;"));
			if ($data_[0]!=0)
			{
				$text .= "Сообщений: #".$data_[0];
				$data_ = mysql_fetch_row (f_mysqlQuery ("SELECT s2.login, s1.time, s1.data FROM forum AS s1, users AS s2
													WHERE s1.id IN (SELECT MAX(id) FROM forum WHERE id_tema=".$data[0].") AND s1.id_user=s2.id AND s1.basket=0;"));
				$text .= " ".$data_[0]." ".$data_[1]." ".$data_[2];
			}
			$text .= "</span>
					<div class = 'forum_list_item_buttons'>";
					if (($_SESSION["id"]==$data[1] && $_SESSION["dopusk"]=="yes") || $_SESSION["dopusk"]=="admin"){
						$text .= "
						<a class = 'forum_delete_item_link text_insignificant' href = '#' message = '".$data[0]."'>Удалить</a>";
					}
					$text .= "
					</div>
				</div>
			</li>";
		}
		$text .= "
		</ul>";
	}
	elseif ($theme != 0)
	{
		$data = mysql_fetch_row(f_mysqlQuery('SELECT id_tema FROM forum WHERE id='.$theme.';'));
		if ($data[0] == 0)
			$text .= "
			<p class = 'message_non_existed'>....... Темы отсутствуют .......</p>";
	}

// Просмотр сообщений в теме =================================================================================
	// Кол. сообщений
	$result = f_mysqlQuery ("SELECT s1.id, s1.id_user, s1.text, s1.time, s1.data, s2.login FROM forum AS s1, users AS s2
							WHERE s1.id_tema=".$theme." AND s1.status=0 AND s1.basket=0 AND s1.id_user=s2.id ORDER BY data DESC, time DESC;");
    $count = mysql_num_rows($result);
	if ($count >= 1) {
		$text .= "
			<p>Коментарии к теме</p>
			<ul class = 'messageLists'>";
		while ($data = mysql_fetch_row ($result)) {
			$text .= "
			<li class = 'forum_message' item = ".$data[0].">
				<div class = 'message_autor'>
					<div class = 'avatar'>
				".f_img (3, $data[1]);
			$text .= "</div>
					<span>".$data[5]."</span>
					<p class = 'data text_insignificant'>".$data[3]." / ".$data[4]."</p>
				</div>

			    <div  class = 'text'>
					<p>".$data[2]."</p>
					<div class = 'forum_list_item_buttons'>";
			if (($_SESSION["id"]==$data[1] && $_SESSION["dopusk"]=="yes") || $_SESSION["dopusk"]=="admin")
			{
				$text .= "
				<a href = '#' class = 'text_insignificant forum_redaction_message_link'>Редактировать</a>
				<a href = '#' class = 'text_insignificant forum_delete_item_link'>Удалить</a>";
			}
			$text .="
					</div>
				</div>
			</li>";
	 	}
		$text .= "
		</ul>";
	}
	elseif ($theme != 0) {
		$text .= "
			<p class = 'message_non_existed'>....... Сообщения отсутствуют .......</p>";
	}


$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
