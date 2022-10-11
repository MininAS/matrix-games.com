<?php
	require ("function.php");
	require ("sess.php");

	$_life_exp_of_subgame = 18;    # days
	$_life_exp_block_visual = 6;   # items

	if ($canvasLayout == "") $canvasLayout = 0;

// Просмотр сыгранных игр =====================================================
    if ($_SESSION["dopusk"]=="yes"){
	    $userSubGameAmount = getUserSubGameAmount ($theme, $_SESSION["id"]);
		echo ("
			<div id = 'gameCheckboxScrollContainer'>
		");
	    for ($i = 0; $i < 5; $i++){
			$class = ($i < $userSubGameAmount) ? "openedGameCheckbox" : "";
		    echo ("
			    <ul class = 'gameCheckbox key'>
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
	if (isset($subGames))
    while ($subGame = mysqli_fetch_row($subGames)){
		$winner = getSubGameBestPlayer($theme, $subGame[0]);
		$subSubGameAmount = getSubSubGameAmount($theme, $subGame[0]);
		$subGameResults = f_mysqlQuery ("
		    SELECT id_user, score
			FROM games_".$theme."_com
			WHERE id_game=".$subGame[0]."
			ORDER BY data, time;
		");

		$pioneer = mysqli_fetch_row($subGameResults);

		$selectable = $_SESSION["id"] == $winner["id"] || $_SESSION["id"] == $pioneer[0]
		    ? "indicated_list_item"
			: "selectable_list_item";
		echo ("
			<li id = 'G".$subGame[0]."' class = '".$selectable."'>
				<div class = 'message_autor'>
					<div class = 'avatar'>
					".f_img (3, $winner["id"])."
					    		<span class = 'top_list_item_number text_insignificant'>
									".$subGame[0]."
								</span>
					</div>
					<span>".$winner["login"]."</span>
					<p class = 'data big'>".$winner["score"]."</p>
				</div>
				<div class = 'text small'>
				<div class = 'gameCheckboxContainer'>
		");
		if ($_SESSION["id"] == $pioneer[0])
			echo ("
					<ul class = 'gameCheckbox key'>
						<li class = 'openedGameCheckbox'></li>
					</ul>
			");
		if ($_SESSION["id"] == $winner["id"])
			echo ("
				<ul class = 'gameCheckbox key'>
					<li class = 'wonGameCheckbox'></li>
				</ul>
			");
		if ($subSubGameAmount >= 5){
			echo ("
				<ul class = 'gameCheckbox key'>
			");
			for ($i = 1; $i <= $_life_exp_block_visual; $i++){
				$aggregate = $i * $_life_exp_block_visual / $_life_exp_of_subgame;
				$opacity = ($aggregate <= $winner["live"]) ? (1 / $_life_exp_block_visual * $i) : 1;
				$class =   ($aggregate <= $winner["live"]) ? "deletedGameCheckbox" : "";
				echo ("
					<li class = '".$class."' style='opacity:".$opacity."'></li>
				");
			}
			echo ("
				</ul>
			");
		}
		echo ("
		    </div>
		    <p> ".$pioneer[1]." - ".getUserLogin($pioneer[0])."</p>"
        );

		if ($subSubGameAmount > 4)
			echo ("
				<center> + ".($subSubGameAmount - 3)." + </center>
			");
		for ($i = 2; $player = mysqli_fetch_row($subGameResults); $i++){
            if ($i > ($subSubGameAmount - 2))
				echo ("
					<p> ".$player[1]." - ".getUserLogin($player[0])."</p>"
				);
		}

		if ($_SESSION["dopusk"] == "admin"){
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
