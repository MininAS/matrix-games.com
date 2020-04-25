window.onload = function ()
{
	// Включение анимации изображений полей игр
	animeWindows = document.getElementsByClassName('winPreshowGameItem');
	for(var i=0; i<animeWindows.length; i++)
	{
		animeWindows[i].onmouseenter = function()
		{
			this.getElementsByTagName('img')[0].src = 'img/'+this.getElementsByTagName('img')[0].id+'.gif?lastVersion=2';
		}
		animeWindows[i].onmouseleave = function ()
		{
			this.getElementsByTagName('img')[0].src = 'img/'+this.getElementsByTagName('img')[0].id+'_.gif?lastVersion=2';
		}
	}

// Подсказки на кнопках_______________________________________________________
	menuButtonTooltip = document.getElementById('text_key');
	menuButtons = document.getElementById('menu').getElementsByTagName('a');
	for (var i=0; i < menuButtons.length; i++)
	{
		if (menuButtons[i].getElementsByTagName('img')[0])
		{
			menuButtons[i].onmousemove = function (e)
			{
				menuButtonTooltip.innerHTML = this.getElementsByTagName('img')[0].alt;
				menuButtonTooltip.style.display = 'block';
				e = e || window.e;
				x = e.pageX || e.clientX;
				y = e.pageY || e.clientY;
				menuButtonTooltip.style.left = x-100+'px';
				menuButtonTooltip.style.top = y+25+'px';
			}
			menuButtons[i].onmouseout = function () {menuButtonTooltip.style.display = 'none';}
		}
	}
	f_fetchUpdateContent('onlineUser', 'ajax_bottom.php', null);

// Включаем виджет "Мне ндравится ВКонтакте"
	if (typeof VK == 'object') {
			VK.init({apiId: 2729439});
			VK.Widgets.Like('vk_like', {
				type: "mini",
				pageTitle: "Мини-игры на логику",
				pageDescription: "Игры на логическую тематику, главным смыслом которых, является, соревнования между игроками.",
				pageUrl: "http://matrix-games.ru",
				pageImage: "http://matrix-games.ru/img/icon.png",
				text: "Заходи, посоревнуемся.",
				height: 30});
	}

    if (!window.location.href.match('profile.php') && !window.location.href.match('games.php'))
		f_fetchUpdateContent('user_top_middle', 'top_users.php', null);
	// Запуск счетчика
	f_counter ();
    f_isWindowsHeightAlignment ();
}

// Выравниваем высоту окна user_top по высоте основного блока с играми
function f_isWindowsHeightAlignment () {
	var windowHeightFirst = 0;
	windowUserTop = document.getElementById('user_top_middle');
	var idTime = setInterval( function () {
		if (typeof windowUserTop == 'object') {
			windowHeight = getComputedStyle(document.getElementById('box_game')).height;
			windowHeight = (windowHeight.replace(/px/g, '') - 46) + 'px';
			windowUserTop.style.height = windowHeight;
  		if (windowHeightFirst == windowHeight) clearInterval(idTime);
			windowHeightFirst = windowHeight;
		}
	}, 1000);
}

// Блок аутентификации через Вконтакте
	function authInfo(response) {
		if (response.session)
		{
			var req = getXmlHttp();
			req.onreadystatechange = function()
				{
					if (req.readyState == 4)
						if (req.status == 200)
						{
							if (req.responseText == 'true')
							{
								if (window.location.href.match('index.php'))
									window.location.href = location.pathname;
								else
									window.location.href = '';
							}
							if (req.responseText == 'false')
							{
								VK.Api.call (
									'users.get',
									{user_ids: response.session.mid, fields: 'photo_200, has_photo', v: 5.8},
										function(r) {
											if(r.response)
											{
												var str = "?";
												for(k in r.response[0]) {
													str += k+"="+ r.response[0][k]+"&";
												}
												window.location.href='reg.php'+str;
											}
										});
							}
						}
				}
			req.open('POST', 'ajax_auth_vk.php', true);
			req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			req.send(null);
		}
	}

	function getXmlHttp()
	{
		var xmlhttp;
		try {xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
		catch (e)
		{
			try	{xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
			catch (E){xmlhttp = false;}
		}
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp = new XMLHttpRequest();
		return xmlhttp;
	}

// Определение содержимого информационного окна -----------------------------------------------------
function window_info(s_name, text)
{
	elem =document.getElementById('info_div');
	elem.innerHTML = "Ожидается ответ сервера...";
	if (!text)  text = "Ожидается ответ сервера...";
	flag_PopUp = true;
	switch(s_name) {
		case undefined:
			elem.innerHTML = '';
			flag_PopUp = false;
			break;
		case 'show':
			break;
		case 'text_info':
			elem.innerHTML = text;
			break;
		case 'pause':
			elem.innerHTML = "<p class = 'very-big'>Пауза</p>Для снятия с паузы кликните по этому окну.";
			break;
		case 'user_top':
 			f_fetchUpdateContent('info_div', 'top_user_statistic.php?user='+text, null);
			break;
		case 'user_game':
 			f_fetchUpdateContent('info_div', 'ajax_game_statistic.php?theme='+text, null);
			f_counter (); // Увеличиваем счетчик т.к. окно появляется только при каком-нибудь действии пользователя.
			break;
		case 'text_help':
			elem.innerHTML = document.getElementById('text_help').innerHTML;
			break;
		case 'smile':
			f_fetchUpdateContent('info_div', 'ajax_smile.php', null);
			break;
		case 'reg':
			f_fetchUpdateContent('info_div', 'ajax_reg.php', null);
			break;
		case 'forum_theme_redact':
			elem.innerHTML = document.getElementById('forum_theme_redact').innerHTML;
			break;
		case 'accaunt-delet':
			elem.innerHTML = document.getElementById('accaunt-delet').innerHTML;
			break;
	}
	windowInfo = document.getElementById('window_info');
	blackGlass = document.getElementById('black_glass');
	if (flag_PopUp == true) {
		windowInfo.style.display = 'block';
		blackGlass.style.height = getDocumentHeight()+'px';
		blackGlass.style.display = 'block';
		blackGlass.style.opacity = 0.7;
		flag_PAUSE = true;
		flag_PopUp = false;
	}
	else {
		windowInfo.style.display = 'none';
		blackGlass.style.display = 'none';
		blackGlass.style.opacity = 0;
		flag_PAUSE = false;
	}
}

// Добавление смайла в текст -------------------------------------------------------------
function f_parseSmilesAtMessage (smile)
{
	elm = document.querySelector('#formSendMessage textarea')
	if (elm.disabled == true) return;
	elm.value = elm.value + "{[:" + smile + ":]}";
}

// Fetch -------------------------------------------------------------------------

function f_fetchUpdateContent (s_targetBlock, s_loaderFile, callback) {
	var myElement = document.getElementById(s_targetBlock);
	fetch(s_loaderFile)
		.then (response => {
			if (response.status == 200) return response.text()
			else myElement.innerHTML =  response.status + " " + response.statusText
		})
			.then (data => {
				myElement.innerHTML = data;
				if (typeof callback == 'function')
					callback ();
			})
}

function f_fetchSaving (s_saverFile, s_attributes, callback) {
	fetch(s_saverFile, {
		method: 'POST',
		headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		body: s_attributes
	})
		.then (response => {
			if (response.status == 200) return response.json()
			else window_info ('text_info', response.status + " " + response.statusText)
		})
			.then (data => {
				window_info ('text_info', data.message);
				if (data.res.match(/^2/))
				    if (typeof callback == 'function'){
						if (data.id) i_canvasLayout = data.id
						callback();
					}
			})
}

//CROSS// Определение высоты окна ---------------------------------------------------------------------------------
var ua = navigator.userAgent.toLowerCase();
var isOpera = (ua.indexOf('opera') > -1);
var isIE = (!isOpera && ua.indexOf('msie') > -1);
function getDocumentHeight() {
	return Math.max(document.compatMode != 'CSS1Compat' ? document.body.scrollHeight
	: document.documentElement.scrollHeight, getViewportHeight());
}
function getViewportHeight() {
	return ((document.compatMode || isIE) && !isOpera) ? (document.compatMode == 'CSS1Compat')
	? document.documentElement.clientHeight : document.body.clientHeight
	: (document.parentWindow || document.defaultView).innerHeight;
}

// Возврат значения кука
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

// Отключение и включение звука
var flag_SOUND = getCookie('sound');
function f_sound_off ()
{
	var date = new Date(2030, 00, 01);
	if (getCookie('sound') == 'on')
	{
		document.cookie = "sound=off; expires=" + date.toUTCString();
		flag_SOUND = 'off';
	}
	else
	{
		document.cookie = "sound=on; expires=" + date.toUTCString();
		flag_SOUND = 'on';
	}
	document.querySelector('#k_sound a img').src = 'img/k_sound_'+flag_SOUND+'.png';
}

function f_showSoundButton () {
	e_soundButton = document.getElementById('k_sound');
	var idTime = setInterval( function () {
		if (typeof e_soundButton == 'object') {
			e_soundButton.style.display = 'inline-block';
  		    clearInterval(idTime);
		}
	}, 500);
}

// Загрузка аватара с Вконтакте--------------------------------
function redirect_vk_photo_url()
{
	VK.Api.call('users.get', {fields: 'photo_200, has_photo', v: 5.8}, function(r) {
		if(r.response) window.location.href='profile.php?regEdit=3&photo='+r.response[0]['photo_200'];
	});
}

// Старт счетчиков
function f_counter ()
{
	// Yandex
	(function (d, w, c) {
		(w[c] = w[c] || []).push(function() {
			try {
				w.yaCounter12200890 = new Ya.Metrika({
					id:12200890,
					clickmap:true,
					trackLinks:true,
					accurateTrackBounce:true
				});
			} catch(e) { }
		});
			var n = d.getElementsByTagName("script")[0],
			s = d.createElement("script"),
			f = function () { n.parentNode.insertBefore(s, n); };
			s.type = "text/javascript";
			s.async = true;
			s.src = "https://mc.yandex.ru/metrika/watch.js";
			if (w.opera == "[object Opera]") {
				d.addEventListener("DOMContentLoaded", f, false);
			} else { f(); }
	})
	(document, window, "yandex_metrika_callbacks");
	// Live Internet
	document.getElementById('LiveInternet').innerHTML = "<a href='//www.liveinternet.ru/click' "+
		"target=_blank><img src='//counter.yadro.ru/hit?t19.5;r"+
		escape(document.referrer)+((typeof(screen)=="undefined")?"":
		";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
		screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
		";"+Math.random()+
		"' alt='' title='LiveInternet: показано число просмотров за 24"+
		" часа, посетителей за 24 часа и за сегодня' "+
		"border='0' width='88' height='31'><\/a>";
}

var f_changeInputFieldDisablement = function(elm, state){
	elm.disabled = state;
	if (state == true) elm.style.background = '#bbb';
	else  elm.style.background = '#fff';
}
