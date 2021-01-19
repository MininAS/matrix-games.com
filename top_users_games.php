<?php
	require ("function.php");
	require ("sess.php");

	if ($canvasLayout == "") $canvasLayout = 0;

// Просмотр съигранных игр =====================================================
    if ($_SESSION["dopusk"]=="yes"){
	    $userThemGameAmount = getUserThemeGameAmount ($theme, $_SESSION["id"]);
		echo ("
			<div class = 'gameExistenceCheckboxContainer'>
		");
	    for ($i = 0; $i < 5; $i++){
			$class = ($i < $userThemGameAmount) ? "green" : "";
		    echo ("
			    <ul class = 'gameExistenceCheckbox key'>
				    <li class = '".$class."'></li>
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
    while ($subGame = mysql_fetch_row ($subGames)){
		$winner = mysql_fetch_row (
			f_mysqlQuery ("
			    SELECT id_user, users.login, score
				FROM games_".$theme."_com AS tb, users
				WHERE id_game=".$subGame[0]." AND id_user=users.id
			    ORDER BY score DESC, xod, tb.data, tb.time LIMIT 1;
			")
		);

		$subGameResults = f_mysqlQuery ("
		    SELECT id_user, users.login, score
			FROM games_".$theme."_com AS s1, users
			WHERE id_game=".$subGame[0]." AND id_user=users.id
			ORDER BY s1.data, s1.time;
		");

		$pioneer = mysql_fetch_row ($subGameResults);
		mysql_data_seek ($subGameResults, 0);

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
					<span>".$winner[1]."</span>
					<p class = 'data big'>".$winner[2]."</p>
				</div>
				<div class = 'text'>
		");

		if ($_SESSION["id"] == $winner[0] || $_SESSION["id"] == $pioneer[0]){
			if ($_SESSION["id"] == $pioneer[0])
				echo ("
				<ul class = 'gameExistenceCheckbox key'>
					<li class = 'green'></li>
				</ul>
				");
		    if ($_SESSION["id"] == $winner[0])
			    echo ("
				<ul class = 'gameExistenceCheckbox key'>
					<li class = 'blue'></li>
				</ul>
			    ");
		}

		while ($player = mysql_fetch_row($subGameResults)){
			echo ("
				    <p class = 'small'> ".$player[2]." - ".$player[1]."</p>"
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
