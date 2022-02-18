<?php
	require ("function.php");
	require ("sess.php");

$text = "";

	$text .= "
	<ul class = 'messageLists'>";
	$parent = getForumMessageById ($theme);
	if ($theme != 0){
		$text .= "
		<li class = 'forum_topic_header'>
			<div class = 'text'>
				<p>".$parent["text"]."</p>
			</div>
		</li>";
	}

	$result = f_mysqlQuery ("SELECT id, id_user, text, time, data FROM forum
	                         WHERE id_tema=".$theme." AND status=1 AND basket=0;");
    $count = mysql_num_rows($result);
    if ($count > 0){
		while ($data = mysql_fetch_row ($result)){
			$text .= "
				<li class = 'forum_theme selectable_list_item' item = ".$data[0].">
					<div class = 'message_autor'>
						<div class = 'avatar'>
					".f_img (3, $data[1]);
			$text .= "</div>
						<span>".getUserLogin($data[1])."</span>
						<p class = 'data text_insignificant'>".$data[3]." / ".$data[4]."</p>
					</div>

					<div class = 'text'>
						<p>".$data[2]."</p>";
			$data_ = mysql_fetch_row (f_mysqlQuery ("SELECT COUNT(*) FROM forum WHERE id_tema=".$data[0]." AND status=1 AND basket=0;"));
			$text .= "
						<span class = 'small'>";
			if ($data_[0]!=0)
				$text .= _l("Notebook/Topics").": #".$data_[0]." | ";
			$data_ = mysql_fetch_row (f_mysqlQuery ("SELECT COUNT(*) FROM forum WHERE id_tema=".$data[0]." AND status=0 AND basket=0;"));
			if ($data_[0]!=0){
				$text .= _l("Notebook/Messages").": #".$data_[0];
				$data_ = mysql_fetch_row (f_mysqlQuery ("SELECT id_user, time, data FROM forum
														WHERE id IN (SELECT MAX(id) FROM forum WHERE id_tema=".$data[0].")
														AND basket=0;"));
				$text .= " ".getUserLogin($data[0])." ".$data_[1]." ".$data_[2];
			}
			$text .= "
						</span>
						<div class = 'forum_list_item_buttons'>";
			if (($_SESSION["id"]==$data[2] && $_SESSION["dopusk"]=="yes") || $_SESSION["dopusk"]=="admin"){
				$text .= "
							<a class = 'forum_delete_item_link text_insignificant' href = '#' message = '".$data[0]."'>"._l("Notebook/Remove")."</a>";
			}
					$text .= "
						</div>
					</div>
				</li>";
		}
	}
	elseif ($parent["id_tema"] == 0)
		$text .= "
			<p class = 'message_non_existed'>....... "._l("Notebook/Topics are not exist")." .......</p>";
	$text .= "
	</ul>";

// Просмотр сообщений в теме =================================================================================
	// Кол. сообщений
	$result = f_mysqlQuery ("SELECT id, id_user, text, time, data FROM forum
							WHERE id_tema=".$theme." AND status=0 AND basket=0 ORDER BY data DESC, time DESC;");
    $count = mysql_num_rows($result);
	if ($count >= 1) {
		$text .= "
			<p>"._l("Notebook/Topic messages")."</p>
			<ul class = 'messageLists'>";
		while ($data = mysql_fetch_row ($result)) {
			$text .= "
			<li class = 'forum_message' item = ".$data[0].">
				<div class = 'message_autor'>
					<div class = 'avatar'>
				".f_img (3, $data[1]);
			$text .= "</div>
					<span>".getUserLogin($data[1])."</span>
					<p class = 'data text_insignificant'>".$data[3]." / ".$data[4]."</p>
				</div>

			    <div  class = 'text'>
					<p>".$data[2]."</p>
					<div class = 'forum_list_item_buttons'>";
			if (($_SESSION["id"]==$data[1] && $_SESSION["dopusk"]=="yes") || $_SESSION["dopusk"]=="admin")
			{
				$text .= "
				<a href = '#' class = 'text_insignificant forum_redaction_message_link'>"._l("Notebook/Edit")."</a>
				<a href = '#' class = 'text_insignificant forum_delete_item_link'>"._l("Notebook/Remove")."</a>";
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
			<p class = 'message_non_existed'>....... "._l("Notebook/No post")." .......</p>";
	}


$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
