<?php
	if ($dir=opendir ("smile")){
		echo ("
	<div id='smiles'>
		");
		readdir ($dir); readdir ($dir);
		while ($file=readdir ($dir)){
			if (filetype ("smile/$file") == "file" && preg_match('/[a-z]{2}.gif/', $file)){
				$smile_name = str_replace (".gif", "", $file);
				echo ("
		<img src='smile/".$file."'
			alt = '".$file."'
			onClick='f_parseSmilesAtMessage (\"".$smile_name."\");'>
				");
			}
		}
		echo ("
	</div>
		");
	}
?>
