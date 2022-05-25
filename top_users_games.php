<?php
	require ("function.php");
	require ("sess.php");

	if ($canvasLayout == "") $canvasLayout = 0;

// Просмотр съигранных игр =====================================================
    if ($_SESSION["dopusk"]=="yes"){
	    $userThemGameAmount = getUserSubgameAmount ($theme, $_SESSION["id"]);
		echo ("
			<div id = 'gameCheckboxContainer'>
		");
	    for ($i = 0; $i < 5; $i++){
			$class = ($i < $userThemGameAmount) ? "openedGameCheckbox" : "";
		    echo ("
			    <ul class = 'gameCheckbox ".$class." key'>
				    <li></li>
				</ul>");
		}
		echo ("
		    </div>
		");
    }

	echo ("
		<ul class = 'windowTitle'>
		    <li>"._l("Rating/Layout сombinations")."</li>
		</ul>
		<ul class = 'messageLists'>
	");

	$subGames = f_mysqlQuery ("
		SELECT id_game, MAX(score) AS sum
		FROM games_".$theme."_com
		GROUP BY id_game
		ORDER BY sum DESC;
    ");
    while ($subGame = mysqli_fetch_row($subGames)){
		$winner = mysqli_fetch_row(
			f_mysqlQuery ("
			    SELECT id_user, score
				FROM games_".$theme."_com
				WHERE id_game=".$subGame[0]."
			    ORDER BY score DESC, xod, data, time LIMIT 1;
			")
		);

		$subGameResults = f_mysqlQuery ("
		    SELECT id_user, score
			FROM games_".$theme."_com
			WHERE id_game=".$subGame[0]."
			ORDER BY data, time;
		");

		$pioneer = mysqli_fetch_row($subGameResults);
		mysqli_data_seek ($subGameResults, 0);

		$selectable = $_SESSION["id"] == $winner[0] || $_SESSION["id"] == $pioneer[0]
		    ? "indicated_list_item"
			: "selectable_list_item";
		echo ("
			<li id = 'G".$subGame[0]."' class = '".$selectable."'>
				<div class = 'message_autor'>
					<div class = 'avatar'>
					".f_img (3, $winner[0])."
					    		<span class = 'top_list_item_number text_insignificant'>
									".$subGame[0]."
								</span>
					</div>
					<span>".getUserLogin($winner[0])."</span>
					<p class = 'data big'>".$winner[1]."</p>
				</div>
				<div class = 'text'>
		");

		if ($_SESSION["id"] == $winner[0] || $_SESSION["id"] == $pioneer[0]){
			if ($_SESSION["id"] == $pioneer[0]){
				echo ("
				<ul class = 'gameCheckbox key openedGameCheckbox'>
					<li></li>
				</ul>
				");
			}
		    if ($_SESSION["id"] == $winner[0])
			    echo ("
				<ul class = 'gameCheckbox key wonGameCheckbox'>
					<li></li>
				</ul>
			    ");
		}

		while ($player = mysqli_fetch_row($subGameResults)){
			echo ("
				    <p class = 'small'> ".$player[1]." - ".getUserLogin($player[0])."</p>"
			);
		}

		if ($_SESSION["dopusk"]=="admin"){
			echo ("
				<div class = 'forum_list_item_buttons'>
					<a class = 'text_insignificant'
						href='games.php?regEdit=4&theme=".$theme."&canvasLayout=".$subGame[0]."'
						>"._l("Notebook/Remove")."</a>
				</div>"
			);
		}
		echo ("
			</li>"
		);
	}
	echo ("
	    </ul>"
    );
?>
