var a_block = new Array();
var XxX = 10;
var YyY = 10;
var QqQ = 48;
var WwW = 6;
var flag_PLAY = true; // Используем при переходе к другой игре при незаконченной анимации и от onClickа

e = document.getElementById('game');
e.style.width = XxX * (QqQ + 4) + "px";
e.style.background = '#66a';
e.style.borderRadius = 7 + 'px';
e.style.padding = 2 + 'px';
e.addEventListener('touchmove', function (event) {
	event.preventDefault();
}, false);
e.classList.toggle('noselect');

f_showElementById('k_sound');

function f_createGame() {
	for (i = 1; i <= XxX; i++)
		a_block[i] = new Array();
	for (ii = 1; ii <= YyY; ii++) {
		for (i = 1; i <= XxX; i++) {
			var elem = document.createElement('div');
			document.getElementById('game').appendChild(elem);
			var elm = document.createElement('img');
			elem.appendChild(elm);
			elem.style.display = 'inline-block';
			elem.style.width = QqQ + 'px';
			elem.style.height = QqQ + 'px';
			elem.style.background = '#448';
			elem.style.margin = 2 + 'px';
			elem.style.borderRadius = 5 + 'px';
			elem.style.transition = 'background-color .5s';
			elem.style.WebkitTransition = 'background-color .5s';
			elem.onmouseover = function () { this.style.backgroundColor = '#8aa'; }
			elem.onmouseout = function () { this.style.backgroundColor = '#448'; }
			elm.volume = 1;
			elm.mass = WwW + 1;
			elm.xx = i;
			elm.yy = ii;
			elm.src = 'img/barrel_1.png';
			a_block[i][ii] = elm;
		}
	}
}

function f_game(e) {
	if (flag_PLAY) {
		i_motion++;
		this.mass--;
		this.turbo = false;
		this.style.background = '';
		e_scoreViewer.innerHTML = i_score;
		f_game_(this, 0);
	}
}

function f_game_(e, t) {
	if (e.volume == WwW) {
		e.style.opacity = 0;
		s_name = e.turbo ? 'barrel_mas' : 'barrel_mass';
		f_playSound(s_name);
		e.volume = 9;
		e.parentNode.style.backgroundColor = '#448';
		e.parentNode.onmouseover = null;
		e.parentNode.onmouseout = null;
		e.onclick = null;
		f_scrollScore(e, e.mass)
		i_score += e.mass; // Прибавляем баллы за лопнувшую бочку согласно ее весу
		e_scoreViewer.innerHTML = i_score;

		if (e.xx > 1) f_greatObject(e, 'left');
		if (e.xx < XxX) f_greatObject(e, 'right');
		if (e.yy > 1) f_greatObject(e, 'up');
		if (e.yy < YyY) f_greatObject(e, 'down');

	}
	else {
		if (t >= 1) e.mass++; // Увеличиваем вес после каждого автоперехода
		if (t == 2) e.mass++; // Увеличиваем вес еще если осколок турбо.
		e.volume++;
		e.src = 'img/barrel_' + e.volume + '.png';
	}
}

function f_greatObject(elm, go) {
	flag_ANI++;
	var xy = getOffset(elm);
	var e = document.createElement('div');
	document.getElementById('box_game').appendChild(e);
	e.className = 'border_inset';
	if (elm.turbo) e.style.border = '3px solid blue';
	e.style.position = 'absolute';
	e.style.top = xy.top + 20 + 'px';
	e.style.left = xy.left + 20 + 'px';
	e.style.transition = 'all .2s linear';
	e.style.WebkitTransition = 'all .2s linear';
	if (elm.turbo) e.style.transition = 'all .5s linear';
	e.style.height = '5px';
	e.style.width = '5px';
	e.xx = elm.xx;   // координаты a_block над которым летит e
	e.yy = elm.yy;
	e.go = go;
	e.turbo = elm.turbo;
	e.addEventListener('transitionend', f_aniEnd);
	e.addEventListener('webkitTransitionEnd', f_aniEnd);
	if (go == 'left') e.xx--;
	if (go == 'right') e.xx++;
	if (go == 'up') e.yy--;
	if (go == 'down') e.yy++;
	xy = getOffset(a_block[e.xx][e.yy]);
	e.style.top = xy.top + 20 + 'px';
	e.style.left = xy.left + 20 + 'px';
}

function f_aniEnd() {
	if (a_block[this.xx][this.yy].volume <= WwW && !this.turbo) {
		if (flag_PLAY) f_game_(a_block[this.xx][this.yy], 1);
		f_end(this);
	}
	else {
		if (a_block[this.xx][this.yy].volume <= WwW && this.turbo && flag_PLAY) f_game_(a_block[this.xx][this.yy], 2);
		if ((((this.go == 'left' || this.go == 'right') && this.xx > 1 && this.xx < XxX)
			|| ((this.go == 'up' || this.go == 'down') && this.yy > 1 && this.yy < YyY)) && flag_PLAY == true) {
			if (this.go == 'left') this.xx--;
			if (this.go == 'right') this.xx++;
			if (this.go == 'up') this.yy--;
			if (this.go == 'down') this.yy++;
			xy = getOffset(a_block[this.xx][this.yy]);
			this.style.top = xy.top + 20 + 'px';
			this.style.left = xy.left + 20 + 'px';
		}
		else f_end(this);
	}
}

function f_end(e) {
	flag_ANI--;
	if (flag_ANI == 0) {
		var flag = true;
		for (i = 1; i <= XxX; i++) {
			for (ii = 1; ii <= YyY; ii++) {
				if (a_block[i][ii].volume != 9) flag = false;
			}
		}
		e_scoreViewer.innerHTML = i_score;
		f_saveGame(flag);
	}
	e.style.display = 'none';
	e.remove();
}

function f_newGame() {
	for (ii = 1; ii <= YyY; ii++) {
		for (i = 1; i <= XxX; i++) {
			iii = Math.ceil(Math.random() * WwW);
			a_block[i][ii].volume = iii;
			a_block[i][ii].mass = (WwW - iii + 1);
			a_block[i][ii].src = 'img/barrel_' + iii + '.png';
			if (a_block[i][ii].volume == 1) {
				a_block[i][ii].turbo = true;
				a_block[i][ii].style.background = 'url(img/barrel_0.png)';
			}
			else {
				a_block[i][ii].turbo = false;
				a_block[i][ii].style.background = '';
			}
			i_canvasKeymap += iii;
			a_block[i][ii].parentNode.onmouseover = function () { this.style.backgroundColor = '#8aa'; }
			a_block[i][ii].parentNode.onmouseout = function () { this.style.backgroundColor = '#448'; }
			a_block[i][ii].style.opacity = 1;
			a_block[i][ii].onclick = f_game;
		}
	}
}

function f_oldGame(i_game) {
	str = i_canvasKeymap.substr(0, 1);
	for (ii = 1; ii <= YyY; ii++) {
		for (i = 1; i <= XxX; i++) {
			iii = i_canvasKeymap.substr(((ii - 1) * XxX + i - 1), 1);
			a_block[i][ii].volume = iii;
			if (a_block[i][ii].volume == 1) {
				a_block[i][ii].turbo = true;
				a_block[i][ii].style.background = 'url(img/barrel_0.png)';
			}
			else {
				a_block[i][ii].turbo = false;
				a_block[i][ii].style.background = '';
			}
			a_block[i][ii].mass = (WwW - iii + 1);
			a_block[i][ii].src = 'img/barrel_' + iii + '.png';
			i_canvasKeymap += iii;
			a_block[i][ii].parentNode.onmouseover = function () { this.style.backgroundColor = '#8aa'; }
			a_block[i][ii].parentNode.onmouseout = function () { this.style.backgroundColor = '#448'; }
			a_block[i][ii].style.opacity = 1;
			a_block[i][ii].onclick = f_game;
		}
	}
}

function getOffset(elem) {
	var top = elem.offsetTop + elem.offsetParent.offsetTop;
	var left = elem.offsetLeft + elem.offsetParent.offsetLeft;
	return { top: Math.round(top), left: Math.round(left) }
}
