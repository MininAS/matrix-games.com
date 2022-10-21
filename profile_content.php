<?php
	require ("function.php");
	require ("sess.php");

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin"){
		require ("display_non_authorization.php");
		exit;
	}

	$text = "";
	$result = f_mysqlQuery ("
	    SELECT id, id_user, text, time, data, game, subgame
		FROM users_mess
		WHERE id_tema=".$_SESSION["id"]." AND basket=0
		ORDER BY data DESC, time DESC;"
	);
	$count = isset($result) ? mysqli_num_rows($result) : 0;

	if ($count >= 1){
		$text .= "
			<ul class = 'messageLists'>";
		while ($data = mysqli_fetch_row($result)){
			$text .= "
			<li class = 'forum_message' item = ".$data[0].">
				<div class = 'message_autor'>
					<div class = 'avatar'>
				".f_img (3, $data[1]);
			$text .= "</div>
					<span>".getUserLogin($data[1])."</span>
					<p class = 'data text_insignificant'>".$data[3]." / ".$data[4]."</p>
				</div>
			    <div  class = 'text'>";

				if ($data[5] != "" && $data[6] != 0){
					$creator = getSubGameСreator ($data[5], $data[6]);
					$bestPlayer = getSubGameBestPlayer ($data[5], $data[6]);
					if ($creator == "none")
						$text .= "
						<ul class = 'gameCheckbox key' alt='"._l('Tooltips/Removed')."'>
						    <li class = 'deletedGameCheckbox'></li>
					    </ul>";
                    else if ($creator == $_SESSION["id"])
						$text .= "
						<ul class = 'gameCheckbox key' alt='"._l('Tooltips/Your game')."'>
						    <li class = 'openedGameCheckbox'></li>
					    </ul>";
					else if ($bestPlayer["id"] == $_SESSION["id"])
						$text .= "
						<ul class = 'gameCheckbox key' alt='"._l('Tooltips/Your victory')."'>
						    <li class = 'wonGameCheckbox'></li>
					    </ul>";
				}
					$text .= "
					    <p>".$data[2]."</p>
						<div class = 'forum_list_item_buttons'>";

				if ($data[5] != "" && $data[6] != 0)
					if ($creator != "none" && $creator != $_SESSION["id"] && $bestPlayer["id"] != $_SESSION["id"])
						$text .= "
							<a href = './games.php?theme=".$data[5]."&canvasLayout=".$data[6]."'
								class = 'text_insignificant'>"._l("Mails/Replay")."</a>";

				$text .= "
						<a href = '#' class = 'text_insignificant profile_delete_item_link'>"._l("Notebook/Remove")."</a>
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
