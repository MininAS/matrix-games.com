<?php
	if ($dir=opendir ("smile"))
	{
		$n=0;
		echo ("
	<TABLE>
	<TR align = 'center'>");
		readdir ($dir); readdir ($dir);
		while ($file=readdir ($dir))
		{
			if (filetype ("smile/$file") == "file")
			{
				$smile_name=str_replace (".gif", "", $file);
				echo ("
		<TD>
			<IMG SRC='smile/".$file."'
				alt = '".$file."' 
				onClick=\"f_parseSmilesAtMessage ('".$smile_name."');\">
		</TD>");	
				$n++;
				if ($n==11) 
				{
					echo ("
	</TR>
	<TR ALIGN=\"center\">
					");
					$n=0;
				}
			}
		}
		echo ("
	</TR>
	</TABLE>");
	}
?>