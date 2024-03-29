var e_windowInfoPopup = document.getElementById('window_info_popup');
var e_windowInfoShadow = document.getElementById('black_glass');
var e_windowInfoText = document.getElementById('info_div');

var flag_SOUND = getCookie('sound');
var flag_LANG = getCookie('lang');
var f_changeInputFieldDisablement = function (elm, state) {
	elm.disabled = state;
	if (state == true) elm.style.background = '#bbb';
	else elm.style.background = '#fff';
}

e_windowInfoShadow.onclick = () => f_windowInfoPopup('hide_popup');
e_windowInfoPopup.onclick = () => f_windowInfoPopup('hide_popup');

// Включение анимации изображений полей игр
animeWindows = document.getElementsByClassName('winPreshowGameItem');
for (var i = 0; i < animeWindows.length; i++) {
	animeWindows[i].onmouseenter = function () {
		this.getElementsByTagName('img')[0].src = 'img/' + this.getElementsByTagName('img')[0].id + '.gif?lastVersion=4';
	}
	animeWindows[i].onmouseleave = function () {
		this.getElementsByTagName('img')[0].src = 'img/' + this.getElementsByTagName('img')[0].id + '_.gif?lastVersion=4';
	}
}

// Подсказки на кнопках и маркерах
f_showKeyTooltips();

function f_showKeyTooltips() {
	menuButtonTooltip = document.getElementById('text_key');
	menuButtons = document.querySelectorAll('#menu a img');
	gameBoxMarkers = document.getElementsByClassName('gameCheckbox');

	arr = [...menuButtons, ...gameBoxMarkers];
	for (var i = 0; i < arr.length; i++) {
		if (arr[i].hasAttribute("alt")) {
			arr[i].onmousemove = function (e) {
				menuButtonTooltip.innerHTML = this.getAttribute("alt");
				menuButtonTooltip.style.display = 'block';
				e = e || window.e;
				x = e.pageX || e.clientX;
				y = e.pageY || e.clientY;
				menuButtonTooltip.style.left = x - 100 + 'px';
				menuButtonTooltip.style.top = y + 25 + 'px';
			}
			arr[i].onmouseout = () => menuButtonTooltip.style.display = 'none';
		}
	}
}

f_fetchUpdateContent('onlineUser', 'ajax_bottom.php', null);

// Включаем виджет "Мне ндравится ВКонтакте"
if (typeof VK == 'object') {
	VK.init({ apiId: 2729439 });
	VK.Widgets.Like('vk_like', {
		type: "mini",
		pageTitle: "Мини-игры на логику",
		pageDescription: "Игры на логическую тематику, главным смыслом которых, является, соревнования между игроками.",
		pageUrl: "http://matrix-games.ru",
		pageImage: "http://matrix-games.ru/img/icon.png",
		text: "Заходи, посоревнуемся.",
		height: 30
	});
}

if (!window.location.href.match('profile.php') && !window.location.href.match('games.php'))
	f_fetchUpdateContent('user_top_middle', 'top_users.php', null);
// Запуск счетчика
f_counter();
f_isWindowsHeightAlignment();
// Проверяем наличие мгновенного сообщения
setTimeout(() => {
	text = e_windowInfoText.innerHTML;
	if (text != 'none')
		f_windowInfoPopup('info', text);
}, 2000);

// Выравниваем высоту окна user_top по высоте основного блока с играми
function f_isWindowsHeightAlignment() {
	var windowHeightFirst = 0;
	windowUserTop = document.getElementById('user_top');
	windowUserTopMiddle = document.getElementById('user_top_middle');
	var idTime = setInterval(function () {
		if (typeof windowUserTop == 'object') {
			windowHeight = getComputedStyle(document.getElementById('box_game')).height;
			windowUserTop.style.height = (windowHeight.replace(/px/g, '') - 26) + 'px';
			windowUserTopMiddle.style.height = (windowHeight.replace(/px/g, '') - 46) + 'px';
			if (windowHeightFirst == windowHeight) clearInterval(idTime);
			windowHeightFirst = windowHeight;
		}
	}, 1000);
}

// Блок аутентификации через Вконтакте
function authInfo(response) {
	if (response.session) {
		var req = getXmlHttp();
		req.onreadystatechange = function () {
			if (req.readyState == 4)
				if (req.status == 200) {
					if (req.responseText == 'true') {
						if (window.location.href.match('index.php'))
							window.location.href = location.pathname;
						else
							window.location.href = '';
					}
					if (req.responseText == 'false') {
						VK.Api.call(
							'users.get',
							{ user_ids: response.session.mid, fields: 'photo_200, has_photo', v: 5.89 },
							function (r) {
								if (r.response) {
									var str = "?";
									for (k in r.response[0]) {
										str += k + "=" + r.response[0][k] + "&";
									}
									window.location.href = 'reg.php' + str;
								}
								else console.log(r.error);
							}
						);
					}
				}
		}
		req.open('POST', 'ajax_auth_vk.php', true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send(null);
	}
}

function getXmlHttp() {
	var xmlhttp;
	try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); }
	catch (e) {
		try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
		catch (E) { xmlhttp = false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') xmlhttp = new XMLHttpRequest();
	return xmlhttp;
}

// Определение содержимого информационного окна -----------------------------------------------------
function f_windowInfoPopup(s_name, s_text) {
	// if (!e_windowInfoText) return;
	e_windowInfoText.innerHTML = _l("Server response awaiting ...");

	switch (s_name) {
		case 'pause':
			e_windowInfoText.innerHTML = "<center><p class = 'very-big'>" + _l("Pause") + "</p>" + _l("To unpause game, click on this window.") + "<center>";
			break;
		case 'user_top':
			f_fetchUpdateContent('info_div', 'top_user_statistic.php?user=' + s_text, null);
			break;
		case 'user_game':
			f_fetchUpdateContent('info_div', 'ajax_game_statistic.php?theme=' + s_text, null);
			f_counter();
			break;
		case 'text_help':
			let currentPage = window.location.pathname.match(/([a-z]+)/)[0];
			f_fetchUpdateContent('info_div', 'info/' + currentPage + '.php?lang=' + getCookie('lang') + '&theme=' + s_text, null);
			break;
		case 'smile':
			f_fetchUpdateContent('info_div', 'ajax_smile.php', null);
			break;
		case 'info':
			e_windowInfoText.innerHTML = s_text;
			break;
		case 'hide_popup':
			e_windowInfoText.innerHTML = 'none';
			e_windowInfoPopup.style.display = 'none';
			e_windowInfoShadow.style.display = 'none';
			flag_PAUSE = false;
			return;
	}
	e_windowInfoPopup.style.display = 'block';
	e_windowInfoShadow.style.height = Math.max(
		document.body.scrollHeight, document.body.offsetHeight,
		document.documentElement.clientHeight, document.documentElement.scrollHeight,
		document.documentElement.offsetHeight) + 'px';
	e_windowInfoShadow.style.width = Math.max(
		document.body.scrollWidth, document.body.offsetWidth,
		document.documentElement.clientWidth, document.documentElement.scrollWidth,
		document.documentElement.offsetWidth) + 'px';
	e_windowInfoShadow.style.display = 'block';
	flag_PAUSE = true;
}

// Добавление смайла в текст -------------------------------------------------------------
function f_parseSmilesAtMessage(smile) {
	elm = document.querySelector('#formSendMessage textarea')
	if (elm.disabled == true) return;
	elm.value = elm.value + "{[:" + smile + ":]}";
}

// Fetch -------------------------------------------------------------------------

function f_fetchUpdateContent(s_targetBlock, s_loaderFile, callback) {
	var myElement = document.getElementById(s_targetBlock);
	fetch(s_loaderFile)
		.then(response => {
			if (response.status == 200) return response.text()
			else myElement.innerHTML = response.status + " " + response.statusText
		})
		.then(data => {
			myElement.innerHTML = data;
			if (typeof callback == 'function')
				callback();
		})
}

function f_requestAndHandleForPopup(s_handlerFile, s_attributes, callback) {
	f_windowInfoPopup();
	fetch(s_handlerFile, {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: s_attributes
	})
		.then(response => {
			if (response.status == 200) return response.json();
			else f_windowInfoPopup('info', response.status + " " + response.statusText);
		})
		.then(data => {
			f_windowInfoPopup('info', data.message);
			if (data.res.match(/^2/))
				if (typeof callback == 'function') {
					if (data.id) i_canvasLayoutId = data.id
					callback();
				}
		})
}

function f_requestAndHandle(s_handlerFile, s_attributes) {
	fetch(s_handlerFile, {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: s_attributes
	})
		.then(response => {
			if (response.status != 200)
			    f_windowInfoPopup('info', response.status + " " + response.statusText);
		})
		//.then(data => {
		//	if (data.res.match(/^2/))
				// TODO Какая нибудь нотификация о корректном ответе с сервера. Может быть выключать действия на игре пока не пришел ответ.
		//})
}

// Отключение и включение звука
function f_sound_off() {
	var date = new Date(2030, 00, 01);
	if (getCookie('sound') == 'on') {
		document.cookie = "sound=off; expires=" + date.toUTCString();
		flag_SOUND = 'off';
	}
	else {
		document.cookie = "sound=on; expires=" + date.toUTCString();
		flag_SOUND = 'on';
	}
	document.querySelector('#k_sound a img').src = 'img/k_sound_' + flag_SOUND + '.png';
}

// Смена языка
function f_changeLanguage() {
	var date = new Date(2030, 00, 01);
	if (getCookie('lang') == 'rus') {
		document.cookie = "lang=eng; expires=" + date.toUTCString();
		flag_LANG = 'eng';
	}
	else {
		document.cookie = "lang=rus; expires=" + date.toUTCString();
		flag_LANG = 'rus';
	}

	window.location.reload();
}

function f_showElementById(id) {
	let elm = document.getElementById(id);
	let idTime = setInterval(function () {
		if (typeof elm == 'object') {
			elm.style.display = 'inline-block';
			clearInterval(idTime);
		}
	}, 500);
}

// Загрузка аватара с Вконтакте--------------------------------
function redirect_vk_photo_url() {
	VK.Api.call('users.get', { fields: 'photo_200, has_photo', v: 5.89 }, function (r) {
		if (r.response) {
			$photo = encodeURIComponent(r.response[0]['photo_200']);
			console.log($photo);
			window.location.href = 'profile.php?regEdit=3&photo=' + $photo;
		}
	});
}

// Старт счетчиков
function f_counter() {
	// Yandex
	(function (d, w, c) {
		(w[c] = w[c] || []).push(function () {
			try {
				w.yaCounter12200890 = new Ya.Metrika({
					id: 12200890,
					clickmap: true,
					trackLinks: true,
					accurateTrackBounce: true
				});
			} catch (e) { }
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
	document.getElementById('LiveInternet').innerHTML = "<a href='//www.liveinternet.ru/click' " +
		"target=_blank><img src='//counter.yadro.ru/hit?t19.5;r" +
		escape(document.referrer) + ((typeof (screen) == "undefined") ? "" :
			";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
				screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
		";" + Math.random() +
		"' alt='' title='LiveInternet: показано число просмотров за 24" +
		" часа, посетителей за 24 часа и за сегодня' " +
		"border='0' width='88' height='31'><\/a>";
}

function f_randomId() {
	var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
	var idLength = 13
	var id = new Date().getTime() + "_"
	for (var i = 1; i <= idLength; i++) {
		var randomNumber = Math.floor(Math.random() * chars.length)
		id += chars.substring(randomNumber, randomNumber + 1)
	}
	return id
}

