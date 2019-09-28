var e_layoutNumber = document.getElementById('game_sport')
var e_scoreViewer = document.getElementById('myNballov')
var e_topWindow = document.getElementById('user_top_middle')
var e_newGameButton = document.getElementById('k_newGame')

var s_theme = document.getElementById('theme').value;
var i_canvasLayout = parseInt (document.getElementById('canvasLayout').value);
var i_score = 0;
var i_motion = 0;
var i_canvasKeymap = "";
var flag_PLAY = false;
var flag_GAMEOVER = false;
var flag_PAUSE = false;

f_greateGame ();
f_gameStart ();

function f_gameStart (){
	flag_GAMEOVER = false;
	i_score = 0;
	i_motion = 0;
	i_canvasKeymap = "";
	if (e_scoreViewer) e_scoreViewer.innerHTML = 0;
	if (i_canvasLayout == 0){
		e_layoutNumber.style.display = 'none';
		flag_PLAY = true;
		flag_PAUSE = false;
		f_newGame ();
	}
	else {
		flag_PLAY = false;
		e_layoutNumber.style.display = 'inline';
		e_layoutNumber.innerHTML = '№ ' + i_canvasLayout;
		f_fetchGameLoading (f_oldGame);
	}
	f_updateUserTopList ();
}

function f_fetchGameLoading (callback) {
	fetch ('game_load.php?theme=' + s_theme + '&canvasLayout=' + i_canvasLayout)
		.then (response => {
			if (response.status == 200) return response.json();
			else window_info ('text_info', response.status + " " + response.statusText);
		})
			.then (data => {
				if (data.res.match(/^2/)){
					i_canvasKeymap = data.message;
					flag_PLAY = true;
					flag_PAUSE = false;
				    callback();
				}
				else
					window_info ('text_info', data.message);
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
		e.scrollIntoView();
        e.classList.add ('selected_subgame_item');
	}
}

function f_endGame() {
	flag_PLAY = false;
	flag_GAMEOVER = true;
	f_fetchSaving ('game_save.php?string='
		+ i_canvasKeymap + ':' + i_motion + ':' + i_score
		+ '&theme=' + s_theme
		+ '&canvasLayout=' + i_canvasLayout,
		f_updateUserTopList);

}

// Запуск старой игры и скрол ДИВа игры и выделение в топ по играм которая была выбрана для игры  -----
e_topWindow.onclick = function (event){
	event = event || window.event;
	elm = event.target;
	while (elm != e_topWindow){
		if (elm.tagName == 'LI') break;
		else elm = elm.parentNode;
	}
	i_canvasLayout = parseInt (elm.id.match(/[0-9]+/g));
	f_gameStart ();
}

e_newGameButton.onclick = function(){
	i_canvasLayout = 0;
	f_gameStart ();
}
