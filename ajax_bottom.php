<?php
	require ("function.php");
	require ("sess.php");
	$text = "";
// Читаем файлы сессий и определяем посетителей за текущие месяц, день и последние пол часа(online)
	$i_onlineUser = "";
	$i_onlineQuest = 0;
	$i_questDay = 0;
	$i_questMonth = 0;
	$dir = opendir ("sess");
	readdir($dir); readdir($dir);
  while (false !== ($filename = readdir($dir)))
	{
 		$file = fopen  ("sess/$filename", "r");
		$string = fgets ($file);
		fclose ($file);
		$str = preg_split ('/\|s:[0-9]+:\"|\";/', $string);
		$i_login = "";
		for ($i = 0; $i < sizeof ($str); $i++)
		{
			if ($str[$i] == "login")
			{
				$i_login = $str[$i+1];
			}
			if ($str[$i] == "last_time")
			{
				$i_time = date ("U") - 1800;
				if ($str[$i+1] > $i_time)
				{
					if ($i_login != "") $i_onlineUser .= $i_login.", ";
					else {$i_onlineQuest++;}
				}

	}	}	}

	if ($i_onlineQuest != 0) $i_onlineUser = $i_onlineUser.". Гостей - ".$i_onlineQuest;

$text = "Сейчас на сайте: ".$i_onlineUser."<br>";

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
