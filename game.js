const e_gameElementsContainer = document.getElementById('game');
const e_layoutNumber = document.getElementById('game_sport')
const e_scoreViewer = document.getElementById('myNballov')
const e_topWindow = document.getElementById('user_top_middle')
const e_newGameButton = document.getElementById('k_newGame')

var s_theme = document.getElementById('theme').value;
var i_canvasLayoutId = parseInt(document.getElementById('canvasLayoutId').value);
var i_transitionalKey = 0

var i_score = 0;
var i_motion = 0;
var i_canvasKeymap = "";
var flag_PLAY = false;
var flag_GAMEOVER = false;
var flag_PAUSE = false;
var flag_NEWSTART = false;
var flag_ANI = 0; // Кол-во запущенных анимашек

function f_gameStart() {
	flag_PLAY = false;
	if (flag_ANI != 0) {
		setTimeout(f_gameStart, 200);
		return;
	}
	flag_NEWSTART = true;
	flag_GAMEOVER = false;
	i_score = 0;
	i_motion = 0;
	i_canvasKeymap = "";
	i_transitionalKey = f_randomId()
	if (e_scoreViewer) e_scoreViewer.innerHTML = 0;
	if (i_canvasLayoutId == 0) {
		e_layoutNumber.style.display = 'none';
		f_newGame();
	}
	else
		f_fetchGameLoading(f_oldGame);
	flag_PLAY = true;
	flag_PAUSE = false;
	f_updateUserTopList();
}

function f_fetchGameLoading(callback) {
	f_windowInfoPopup();
	fetch('game_load.php?theme=' + s_theme + '&canvasLayoutId=' + i_canvasLayoutId)
		.then(response => {
			if (response.status == 200) return response.json();
			else f_windowInfoPopup('info', response.status + " " + response.statusText);
		})
		.then(data => {
			if (data.res.match(/^2/)) {
				i_canvasKeymap = data.message;
				flag_PLAY = true;
				flag_PAUSE = false;
				callback();
				f_windowInfoPopup('hide_popup');
				e_layoutNumber.style.display = 'inline';
				e_layoutNumber.innerHTML = '№ ' + i_canvasLayoutId;
			}
			else {
				f_windowInfoPopup('info', data.message);
				flag_PLAY = false;
			}
		})
}

function f_updateUserTopList() {
	f_fetchUpdateContent(
		'user_top_middle',
		'game_list.php?theme=' + s_theme,
		f_updateUserTopListState);
}

function f_updateUserTopListState() {
	f_scrollAndSelectLayoutItem();
	autoScrollingChBoxes();
	f_showKeyTooltips();
}

function f_scrollAndSelectLayoutItem() {
	if (i_canvasLayoutId > 0) {
		e = document.getElementById('G' + i_canvasLayoutId);
		if (e) {
			e.scrollIntoView();
			e.classList.add('selected_layout_item');
		}
	}
}

function f_saveGame(flag_gameIsFinished = false) {
	parameters =
		'canvasLayoutData=' + i_canvasKeymap +
		'&moves=' + i_motion +
		'&score=' + i_score +
		'&theme=' + s_theme +
		'&canvasLayoutId=' + i_canvasLayoutId +
		'&transitionalKey=' + i_transitionalKey +
		'&gameIsFinished=' + flag_gameIsFinished;
	if (flag_gameIsFinished) {
		flag_PLAY = false;
		flag_GAMEOVER = true;
		f_requestAndHandleForPopup('game_save.php', parameters, f_updateUserTopList);
	}
	else {
		f_requestAndHandle('game_save.php', parameters);
	}
}

e_topWindow.onclick = function (event) {
	event = event || window.event;
	elm = event.target;
	while (elm != e_topWindow) {
		if (elm.classList.contains('selectable_list_item')) {
			i_canvasLayoutId = parseInt(elm.id.match(/[0-9]+/g));
			f_gameStart();
			break;
		}
		else elm = elm.parentNode;
	}
}

e_topWindow.onscroll = autoScrollingChBoxes;

function autoScrollingChBoxes() {
	const e_gameChBoxContainer = document.getElementById('gameCheckboxScrollContainer')
	if (!e_gameChBoxContainer) return
	const e_scrollExChBox = Array.from(
		e_gameChBoxContainer.getElementsByTagName('ul')
	);
	const e_listExChBox = Array.from(
		document.querySelectorAll('.messageLists .openedGameCheckbox')
	);
	var scrollY = e_topWindow.scrollTop;
	var containerHeight = e_gameChBoxContainer.getBoundingClientRect().height;
	var containerWidth = e_topWindow.getBoundingClientRect().width;

	for (i = 0; i < 5; i++) {
		if (!e_listExChBox[i]) {
			if (i == 0)
				e_scrollExChBox[0].style.top = '22px';
			else {
				y = e_scrollExChBox[i - 1].offsetTop;
				e_scrollExChBox[i].style.top = `${y + 22}px`;
			}
			continue;
		}
		y = e_listExChBox[i].offsetTop - scrollY;
		x = e_listExChBox[i].offsetLeft - containerWidth + 12;
		borderTop = i * 22 + 7;
		borderBottom = containerHeight - ((5 - i) * 22 + 15);

		if (y > borderTop && y < borderBottom) {
			e_scrollExChBox[i].style.top = `${y - 6}px`;
			e_scrollExChBox[i].style.left = `${x}px`;
		}
		else if (y <= borderTop) {
			e_scrollExChBox[i].style.top = `${borderTop}px`;
			e_scrollExChBox[i].style.left = '3px';
		}
		else if (y >= borderBottom) {
			e_scrollExChBox[i].style.top = `${borderBottom}px`;
			e_scrollExChBox[i].style.left = '3px';
		}
	}
}

e_newGameButton.onclick = function () {
	i_canvasLayoutId = 0;
	f_gameStart();
}

function f_scrollScore(elm, i) {
	var o = document.createElement('p');
	document.getElementById('game_block').appendChild(o);
	o.innerHTML = i;
	o.className = 'border_inset scroll_score';
	o.style.top = elm.offsetTop + 'px';
	o.style.left = elm.offsetLeft + 'px';
	randomOffset = Math.random() * (400 - 100) + 100;
	setTimeout(() => {
		o.style.top = elm.offsetTop - Math.round(randomOffset) + 'px';
		o.style.opacity = 0;
	}, 10);
	o.addEventListener('transitionend', () => { o.remove(); });
	o.addEventListener('webkitTransitionEnd', () => { o.remove(); });
}

function f_playSound(name) {
	if (flag_SOUND == 'on') {
		var o = document.createElement('audio');
		o.src = 'sound/' + name + '.wav';
		o.autoplay = 'autoplay';
		setTimeout(() => { o.remove() }, 100)
	}
}

f_createGame();
f_gameStart();
