<?php
	require "init.php";

	$_life_exp_of_layout = $GLOBALS['LAYOUT_EXPIRY'];    # days
	$_life_exp_block_visual = 6;   # items

	if ($canvasLayout == "") $canvasLayout = 0;

// Просмотр сыгранных игр =====================================================
    if ($_SESSION["dopusk"]=="yes") {
	    $userLayoutAmount = getUserLayoutAmount ($theme, $_SESSION["id"]);
		echo ("
			<div id = 'gameCheckboxScrollContainer'>
		");
	    for ($i = 0; $i < 5; $i++) {
			$class = ($i < $userLayoutAmount) ? "openedGameCheckbox" : "";
			$alt = ($i < $userLayoutAmount) ? _l('Tooltips/Your game')
			    : (5 - $userLayoutAmount)." "._l('Tooltips/games you can save');
		    echo ("
			    <ul class = 'gameCheckbox key' alt = '".$alt."'>
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

	$canvasLayouts = f_mysqlQuery ("
		SELECT id_game, MAX(score) AS sum
		FROM games_".$theme."_com
		GROUP BY id_game
		ORDER BY sum DESC;
    ");
	if (isset($canvasLayouts))
    while ($canvasLayout = mysqli_fetch_row($canvasLayouts)) {
		$winner = getLayoutBestPlayer($theme, $canvasLayout[0]);
		$attemptAmount = getAttemptAmount($theme, $canvasLayout[0]);
		$canvasLayoutResults = f_mysqlQuery ("
		    SELECT id_user, score
			FROM games_".$theme."_com
			WHERE id_game=".$canvasLayout[0]."
			ORDER BY data, time;
		");

		$pioneer = mysqli_fetch_row($canvasLayoutResults);

		$selectable = $_SESSION["id"] == $winner["id"] || $_SESSION["id"] == $pioneer[0]
		    ? "indicated_list_item"
			: "selectable_list_item";
		echo ("
			<li id = 'G".$canvasLayout[0]."' class = '".$selectable."'>
				<div class = 'message_autor'>
					<div class = 'avatar'>
					".f_img (3, $winner["id"])."
					    		<span class = 'top_list_item_number text_insignificant'>
									".$canvasLayout[0]."
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
					<ul class = 'gameCheckbox key' alt='"._l('Tooltips/Your game')."'>
						<li class = 'openedGameCheckbox'></li>
					</ul>
			");
		if ($_SESSION["id"] == $winner["id"])
			echo ("
				<ul class = 'gameCheckbox key' alt='"._l('Tooltips/Your victory')."'>
					<li class = 'wonGameCheckbox'></li>
				</ul>
			");
		if ($attemptAmount >= 5) {
			$left =  ($_life_exp_of_layout - $winner["live"]) >= 0 ? ($_life_exp_of_layout - $winner["live"]) : 0;
			# Если игра живет больше дней чем $winner["live"] - ставим метку 1 - на удаление после полуночи.
			if ($left == 0) {
				f_mysqlQuery ("UPDATE games_".$theme." SET remove=1 WHERE id_game=".$canvasLayout[0].";");
			}
			echo ("
				<ul class = 'gameCheckbox key' alt='".$left." "._l('Tooltips/days until removing')."'>
			");
			$aggregate = $_life_exp_of_layout / $_life_exp_block_visual;
			for ($i = 1; $i <= $_life_exp_block_visual; $i++) {
				$opacity = ($i * $aggregate <= $winner["live"]) ? (1 / $_life_exp_block_visual * $i) : 1;
				$class =   ($i * $aggregate <= $winner["live"]) ? "deletedGameCheckbox" : "";
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

		for ($i = 2; $player = mysqli_fetch_row($canvasLayoutResults); $i++) {
			if ($i == 2) {
			    if ($attemptAmount > 4)
					echo ("
						<center> + ".($attemptAmount - 3)." + </center>
					");
				else
					echo ("
						<p> ".$player[1]." - ".getUserLogin($player[0])."</p>
					");
				continue;
			}
            if ($i > ($attemptAmount - 2))
				echo ("
					<p> ".$player[1]." - ".getUserLogin($player[0])."</p>
				");
		}

		if ($_SESSION["dopusk"] == "admin") {
			echo ("
				<div class = 'forum_list_item_buttons'>
					<a class = 'text_insignificant'
						href='games.php?regEdit=4&theme=".$theme."&canvasLayout=".$canvasLayout[0]."'
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
