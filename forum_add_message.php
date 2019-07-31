<?php
	require ("function.php");
	require ("sess.php");

if ($_SESSION["dopusk"] == 'yes' || $_SESSION["dopusk"] == 'admin')
{
	$status = f_messSave("forum", $theme, $string);
	echo ($status);
}

