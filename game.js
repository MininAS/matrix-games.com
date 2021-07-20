const e_gameElementsContaner = document.getElementById('game');
const e_layoutNumber = document.getElementById('game_sport')
const e_scoreViewer = document.getElementById('myNballov')
const e_topWindow = document.getElementById('user_top_middle')
const e_newGameButton = document.getElementById('k_newGame')
var s_theme = document.getElementById('theme').value;
var i_canvasLayout = parseInt (document.getElementById('canvasLayout').value);
var i_score = 0;
var i_motion = 0;
var i_canvasKeymap = "";
var flag_PLAY = false;
var flag_GAMEOVER = false;
var flag_PAUSE = false;
var flag_NEWSTART = false;

function f_gameStart (){
	flag_NEWSTART = true;
	flag_GAMEOVER = false;
	i_score = 0;
	i_motion = 0;
	i_canvasKeymap = "";
	if (e_scoreViewer) e_scoreViewer.innerHTML = 0;
	if (i_canvasLayout == 0){
		e_layoutNumber.style.display = 'none';
		f_newGame ();
	}
	else {
		e_layoutNumber.style.display = 'inline';
		e_layoutNumber.innerHTML = '№ ' + i_canvasLayout;
		f_fetchGameLoading (f_oldGame);
	}
	flag_PLAY = true;
	flag_PAUSE = false;
	f_updateUserTopList ();
}

function f_fetchGameLoading (callback) {
	f_windowInfoPopup ();
	fetch ('game_load.php?theme=' + s_theme + '&canvasLayout=' + i_canvasLayout)
		.then (response => {
			if (response.status == 200) return response.json();
			else f_windowInfoPopup ('info', response.status + " " + response.statusText);
		})
			.then (data => {
				if (data.res.match(/^2/)){
					i_canvasKeymap = data.message;
					flag_PLAY = true;
					flag_PAUSE = false;
				    callback();
					f_windowInfoPopup ('hide_popup');
				}
				else {
					f_windowInfoPopup ('info', data.message);
					flag_PLAY = false;
				}
			})
}

function f_updateUserTopList () {
	f_fetchUpdateContent (
		'user_top_middle',
	    'top_users_games.php?theme=' + s_theme,
		 f_scrollAndSelectSubgameItem);
}

function f_scrollAndSelectSubgameItem (){
	if (i_canvasLayout > 0){
		e = document.getElementById('G'+i_canvasLayout);
		if (e){
			e.scrollIntoView();
	        e.classList.add ('selected_subgame_item');
		}
	}
	autoScrollingChBoxes();
}

function f_endGame() {
	flag_PLAY = false;
	flag_GAMEOVER = true;
	f_fetchSaving ('game_save.php',
		'subGameData=' + i_canvasKeymap + ':' + i_motion + ':' + i_score +
		'&theme=' + s_theme +
		'&canvasLayout=' + i_canvasLayout,
		f_updateUserTopList);
}

e_topWindow.onclick = function (event){
	event = event || window.event;
	elm = event.target;
	while (elm != e_topWindow){
		if (elm.classList.contains('selectable_list_item')){
			i_canvasLayout = parseInt (elm.id.match(/[0-9]+/g));
			f_gameStart ();
			break;
		}
		else elm = elm.parentNode;
	}
}

e_topWindow.onscroll = autoScrollingChBoxes;

function autoScrollingChBoxes(){
	const e_gameChBoxContainer = document.getElementById('gameCheckboxContainer')
	if (!e_gameChBoxContainer) return
	const e_scrollExChBox = Array.from(
		e_gameChBoxContainer.getElementsByTagName('ul')
	);
	const e_listExChBox = Array.from(
		document.querySelectorAll('.messageLists ul.openedGameCheckbox')
	);
    var scrollY = e_topWindow.scrollTop;
	var containerHeight = e_gameChBoxContainer.getBoundingClientRect().height;
	var containerWidth = e_topWindow.getBoundingClientRect().width;
	
	for (i = 0; i < 5; i++){
		if (!e_listExChBox[i]){
			if (i == 0)
				e_scrollExChBox[0].style.top = '22px';
			else {
				y = e_scrollExChBox[i - 1].offsetTop;
				e_scrollExChBox[i].style.top = `${y + 22}px`;
			}
			continue;
		}
		y = e_listExChBox[i].offsetTop - scrollY;
		x = e_listExChBox[i].offsetLeft - containerWidth + 16;
        borderTop = i * 22 + 7;
		borderBottom = containerHeight - ((5 - i) * 22 + 15);

		if (y > borderTop && y < borderBottom){
		    e_scrollExChBox[i].style.top = `${y - 2}px`;
			e_scrollExChBox[i].style.left = `${x}px`;
		}
		else if (y <= borderTop){
		    e_scrollExChBox[i].style.top = `${borderTop}px`;
			e_scrollExChBox[i].style.left = '3px';
		}
		else if (y >= borderBottom){
		    e_scrollExChBox[i].style.top = `${borderBottom}px`;
			e_scrollExChBox[i].style.left = '3px';
	    }
	}
}

e_newGameButton.onclick = function(){
	i_canvasLayout = 0;
	f_gameStart ();
}

function f_scrollScore (elm, i) {
	var o = document.createElement ('p');
	document.getElementById('game_block').appendChild(o);
	o.innerHTML = i;
	o.className = 'border_inset scroll_score';
	o.style.top = elm.offsetTop + 'px';
	o.style.left = elm.offsetLeft + 'px';
	randomOffset = Math.random() * (400 - 100) + 100;
	setTimeout (() => {
		o.style.top = elm.offsetTop - Math.round(randomOffset) + 'px';
		o.style.opacity = 0;}, 10);
	o.addEventListener('transitionend', () => {o.remove();});
	o.addEventListener('webkitTransitionEnd', () => {o.remove();});
}

function f_playSound (name) {
	if (flag_SOUND == 'on'){
		var o = document.createElement('audio');
		o.src = 'sound/' + name + '.wav';
		o.autoplay = 'autoplay';
		setTimeout (() => {o.remove()}, 100)
	}
}

f_greateGame ();
f_gameStart ();
