<?php
	require ("function.php");
	require ("sess.php");
	$text = "";
// Читаем файлы сессий и определяем посетителей за текущие месяц, день и последние пол часа(online)
	$i_onlineUser = "";
	$i_amountUsers = 0;
	$i_onlineQuest = 0;
	$i_questDay = 0;
	$i_questMonth = 0;

	$dir = opendir ("sess");
	readdir($dir); readdir($dir);
    while (false !== ($filename = readdir($dir))){
		if (preg_match('/^sess_/', $filename)){
			if ($filename == 'sess_'.session_id())
                continue;
 			$arr = f_getSessionInfo($filename);
			if ($arr['last_time'] > (date ("U") - 1800)){
				if ($arr['login'] == "Guest")
				    $i_onlineQuest++;
				else {
				    $i_amountUsers ++;
					if ($i_amountUsers > 1)
					    $i_onlineUser .= ", ";
				    $i_onlineUser .= $arr['login'];
			    }
			}
		}
	}
    if ($i_amountUsers != 0)
        $i_onlineUser .= ". ";

	if ($i_onlineQuest != 0)
	    $i_onlineUser = $i_onlineUser._l('Amount of quests')." - ".$i_onlineQuest.".";

$text = $i_onlineUser;

$trans_tbl = get_html_translation_table (HTML_ENTITIES);
$trans_tbl = array_flip ($trans_tbl);
$text = strtr ($text, $trans_tbl);
echo ($text);
?>
