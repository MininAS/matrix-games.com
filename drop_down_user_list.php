<?php
	require "init.php";

	if ($_SESSION["dopusk"]!="yes" && $_SESSION["dopusk"]!="admin") {
		require ("display_non_authorization.php");
		exit;
	}

	$result = f_mysqlQuery ("SELECT id, login FROM users ORDER BY login;");
		echo ("
			<option value = '0'>"._l('User')."</option>
		");
	if (isset($result))
		while ($data = mysqli_fetch_row($result)) {
			echo ("
				<option value = '".$data[0]."'>".$data[1]."</option>
			");
		}
?>
