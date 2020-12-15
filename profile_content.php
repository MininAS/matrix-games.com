<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$text = "";
	$result = f_mysqlQuery ("SELECT s1.id, s1.id_user, text, s1.time, s1.data, s2.login FROM users_mess AS s1, users AS s2
							WHERE s1.id_tema=".$_SESSION["id"]." AND s1.basket=0 AND s1.id_user=s2.id ORDER BY s1.data DESC, s1.time DESC;");
	$count = mysql_num_rows($result);
	if ($count >= 1){
		$text .= "
			<ul class = 'messageLists'>";
		while ($data = mysql_fetch_row ($result)){
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
					<div class = 'forum_list_item_buttons'>
				<a href = '#' class = 'text_insignificant profile_delete_item_link'>"._l("Remove")."</a>
					</div>
				</div>
			</li>";
	 	}
		$text .= "
		</ul>";
	}
	else {
		$text .= "
			<p class = 'message_non_existed'>....... "._l("Notebook/No posts")." .......</p>";
	}

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
