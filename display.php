<!DOCTYPE html>
<html>
<head><title>LogicalMatrixGames</title>
	<meta charset="utf-8">
	<meta name="DESCRIPTION" content="Сборник лучших логических игр.
		Соревнуйтесь с другими пользователями избегая подставы фартуны в наилучшем раскладе игрового поля,
		ведь сдесь все поля сохраняются!">
	<meta name="KEYWORDS" content="головоломки, головоломка,
		онлайн, online, логические игры, logic game, logical games,
		умные игры, соревнование разума, матрица,
		соревнования на логику, логические матричные игры, логика,
		миниигры, мини игры, MininAS, Logical Matrix Games, Logic,
		Тетрис, Наполнитель, Вышибала, Квантование, короткое замыкание, Числа, Бочка">

	<meta name="yandex-verification" content="52be1ad7373487f5" />
	<link rel="stylesheet" type="text/css" href="style.css?lastVersion=13">
	<link rel="SHORTCUT ICON" href="img/icon.png">
	<script type = 'text/javascript' language = "JavaScript" src = 'function.js?lastVersion=9.2'></script>
	<script type = 'text/javascript' language = "JavaScript" src = '//vk.com/js/api/openapi.js?160'></script>
</head>
<body>
	<div id = 'body'>
		<div id = 'menu' class = 'windowSite'>
			<a href='index.php'><img src = 'img/logotip.png' alt = 'На главную'></a>
				<?require ("menu.php");?>
		</div>
		<div id = 'user_top' class = 'windowSite'>
			<div id = 'user_top_middle'></div>
		</div>
		<div id = 'box_game'>
			<? echo ($body);?>
		</div>
		<div id = 'bottom' class = 'windowSite'>
			<div id = 'index'>
					<!--LiveInternet counter-->
				<div id = 'LiveInternet'></div>
					<!--/LiveInternet-->
					<!-- Yandex.Metrika informer -->
					<div href="https://metrika.yandex.ru/stat/?id=12200890&amp;from=informer"
						target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/12200890/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
						style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)"
						onclick="try{Ya.Metrika.informer({i:this,id:12200890,lang:'ru'});return false}catch(e){}" />
					</div>
					<!-- /Yandex.Metrika informer -->
					<!-- Yandex.Metrika counter -->
					<noscript><div><img src="https://mc.yandex.ru/watch/12200890" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
					<!-- /Yandex.Metrika counter -->
			</div>
			<div id = 'sateVersion'>
				<p id = 'onlineUser' class = 'small'>Сейчас на сайте:</p>
				<p class = 'small'>Обнаруженные технические ошибки просьба коментировать на mininas@sampo.ru.<br/>
				С Уважением Минин Александр. (v10 - 18.04.2020)</p>
			</div>
		</div>
	</div>

<div id="black_glass" onClick = 'window_info();'></div>

<div id = 'window_info' onClick = 'window_info();'>
	<div id = 'info_div' class = 'windowSite'></div>
</div>

<div id = 'text_help'>
	<p align = 'justify'><? if ($_SESSION["page"] == 'game') require ("games/".$theme.".txt");
							else require ("help_".$_SESSION["page"].".txt");?></p>
</div>

<div id = 'text_key' class = 'border_inset toolTip'></div>

<?
	if (isset($text_info)) echo("
	<script type = 'text/javascript' language = 'JavaScript'>
		setTimeout ('window_info (\'text_info\', \"".$text_info."\");', 1000);
	</script>
	");
?>
</body>
</html>
