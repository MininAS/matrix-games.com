<!DOCTYPE html>
<html>
<head><title>LogicalMatrixGames</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css?lastVersion=18">
	<link rel="SHORTCUT ICON" href="img/icon.png">
</head>
<body>
	<div class = 'windowSite'>
		 <?php
            $text = isset($text) ? $text : 'There is needed authorization. You will redirected to home page.';
		    echo (_l($text));
		 ?>
	</div>
	<script type = 'text/javascript' language = 'JavaScript'>
		setTimeout ('window.location.href = "index.php"', 5000);
	</script>
</body>
</html>
