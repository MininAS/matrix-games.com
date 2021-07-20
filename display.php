<!DOCTYPE html>
<html>
<head><title>LogicalMatrixGames</title>
	<meta charset = "utf-8">
	<meta name = "DESCRIPTION" content = "Сборник лучших логических игр.
		Соревнуйтесь с другими пользователями избегая подставы фартуны в наилучшем раскладе игрового поля,
		ведь сдесь все поля сохраняются!">
	<meta name = "KEYWORDS" content = "головоломки, головоломка,
		онлайн, online, логические игры, logic game, logical games,
		умные игры, соревнование разума, матрица,
		соревнования на логику, логические матричные игры, логика,
		миниигры, мини игры, MininAS, Logical Matrix Games, Logic,
		Тетрис, Наполнитель, Вышибала, Квантование, короткое замыкание, Числа, Бочка,
		Sapper, Tetris, Bouncer, Filler, Sphere, Number, Barrel, Bridging, Slicing, Tetris, Tetcolor">

	<meta name = "yandex-verification" content = "52be1ad7373487f5" />
	<link rel = "stylesheet" type = "text/css" href = "style.css?lastVersion=19.8">
	<link rel = "SHORTCUT ICON" href = "img/icon.png">
	<script defer type = 'text/javascript' language = "JavaScript" src = 'lang.js?lastVersion=1.3'></script>
	<script defer type = 'text/javascript' language = "JavaScript" src = 'function.js?lastVersion=15'></script>
	<script type = 'text/javascript' language = "JavaScript" src = '//vk.com/js/api/openapi.js?160'></script>
</head>
<body>
	<div id = 'body'>
		<div id = 'menu' class = 'windowSite'>
			<a href='index.php'><img src = 'img/logotip.png' alt = '<? echo (_l('Tooltips/Home'))?>'></a>
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
					<div href = "https://metrika.yandex.ru/stat/?id=12200890&amp;from=informer"
						target = "_blank"
						rel = "nofollow">
					<img src = "https://informer.yandex.ru/informer/12200890/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
						style = "width:88px; height:31px; border:0;"
						alt = "Яндекс.Метрика"
						title = "Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)"
						onclick = "try{Ya.Metrika.informer({i:this,id:12200890,lang:'ru'});return false}catch(e){}" />
					</div>
					<!-- /Yandex.Metrika informer -->
					<!-- Yandex.Metrika counter -->
					<noscript>
						<div>
							<img src = "https://mc.yandex.ru/watch/12200890" style = "position:absolute; left:-9999px;" alt = "" />
						</div>
					</noscript>
					<!-- /Yandex.Metrika counter -->
			</div>
			<div id = 'sateVersion'>
				<p class = 'small'><? echo (_l('Now at site'))?>: <span id = 'onlineUser'></span></p>
				<p class = 'small'><? echo (_l('If you find some bug, please comment it'))?><br/>
				<? echo (_l('Regards Minin Aleksandr!'))?> (v15 - 20.07.2021)</p>
			</div>
		</div>
	</div>

	<div id = "black_glass"></div>

	<div id = 'window_info_popup'>
		<div id = 'info_div' class = 'windowSite'><? echo ($instant_message) ?></div>
	</div>

	<div id = 'text_key' class = 'border_inset toolTip'></div>

</body>
</html>
