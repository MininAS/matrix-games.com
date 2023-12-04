var a_block = new Array();
var XxX = 8;
var YyY = 8;
var QqQ = 48;
var i_Nballov = 0;
var i_live = 5;
var s_elm = String('');
var flag_DOWN = false;
var flag_SCORE = false;
var flag_MOVE = false;
var flag_BIG_GAME = false;
var flag_OK;
var event_X, event_Y;

e = document.getElementById('game');
e.style.width = XxX * (QqQ + 4) + "px";
e.style.background = '#ddd';
e.style.padding = 2 + 'px';
e.onmousedown = function (event) {
	event = (event) ? event : window.event;
	flag_DOWN = true;
	event_X = event.clientX;
	event_Y = event.clientY;
}
e.onmouseup = function () { flag_DOWN = false; }
e.onmousemove = f_mousemove;

e.addEventListener('touchstart', function (event) {
	event = (event) ? event : window.event;
	flag_DOWN = true;
	event_X = event.targetTouches[0].clientX;
	event_Y = event.targetTouches[0].clientY;
});
e.addEventListener('touchend', function () { flag_DOWN = false; });
e.addEventListener('touchmove', function (event) {
	event.preventDefault();
}, false);
e.addEventListener('touchmove', f_mousemove);

f_showElementById('k_sound');

//Создаем массив игрового поля
function f_createGame() {
	for (i = 1; i <= (XxX * YyY); i++) {
		var elem = document.createElement('div');
		document.getElementById('game').appendChild(elem);
		elem.style.display = 'inline-block';
		elem.style.position = 'relative';
		elem.style.width = QqQ + 'px';
		elem.style.height = QqQ + 'px';
		elem.style.background = '#fff';
		elem.style.margin = 2 + 'px';
		elem.style.borderRadius = 5 + 'px';
		var elm = document.createElement('p');
		elem.appendChild(elm);
		elm.style.lineHeight = QqQ + 'px';
		elm.number = 0;
		elm.mul = 1;
		elm.live = 0;
		elm.style.fontSize = '12pt';
		elm.style.fontStretch = 'ultra-expanded';
		elm.classList.toggle('noselect');
		elm.style.cursor = 'default';
		var e = document.createElement('i');
		elem.appendChild(e);
		e.style.position = 'absolute';
		e.style.top = 0 + 'px';
		e.style.right = 0 + 'px';
		e.style.display = 'none';
		e.style.fontSize = 8 + 'pt';
		e.style.margin = '0px';
		e.classList.toggle('noselect');
		e.style.cursor = 'default';
	}
	elm = document.getElementById('game');
	a_block = elm.getElementsByTagName('p');
	f_anim();
}

document.onkeydown = f_KeyPress;

function f_KeyPress(event) {
	if (flag_PLAY == true) {
		event = (event) ? event : window.event;
		evt = (event.keyCode) ? event.keyCode : event.which;
		if (evt == 38 || evt == 87) { f_move('up'); return false; }
		if (evt == 40 || evt == 83) { f_move('down'); return false; }
		if (evt == 37 || evt == 65) { f_move('left'); return false; }
		if (evt == 39 || evt == 68) { f_move('right'); return false; }
	}
}

function f_mousemove(event) {
	if (flag_DOWN == true && flag_PLAY == true) {
		event = (event) ? event : window.event;
		if (event.type == 'touchmove') event = event.targetTouches[0];
		if (event_X + 20 < event.clientX) { f_move('right'); flag_DOWN = false; }
		if (event_X - 20 > event.clientX) { f_move('left'); flag_DOWN = false; }
		if (event_Y + 20 < event.clientY) { f_move('down'); flag_DOWN = false; }
		if (event_Y - 20 > event.clientY) { f_move('up'); flag_DOWN = false; }
	}
}

function f_marger(j) {
	var p = j.parentNode;
	var e = document.createElement('div');
	p.appendChild(e);
	e.style.top = 20 + 'px';
	e.style.left = 20 + 'px';
	e.style.width = QqQ - 40 + 'px';
	e.style.height = QqQ - 40 + 'px';
	e.style.position = 'absolute';
	e.style.background = '#888';
	e.style.borderRadius = 5 + 'px';
	e.style.transition = 'all ' + (.3 + Math.random()) + 's linear';
	e.style.WebkitTransition = 'all ' + (.3 + Math.random()) + 's linear';
	e.addEventListener('transitionend', function () { this.remove(); });
	e.addEventListener('webkitTransitionEnd', function () { this.remove(); });
	setTimeout(function () {
		e.style.width = QqQ + 20 + 'px';
		e.style.height = QqQ + 20 + 'px';
		e.style.top = -10 + 'px';
		e.style.left = -10 + 'px';
		e.style.opacity = 0;
	}, 10);
}

function f_move(s_move) {
	flag_PLAY = false;
	i_motion++;
	f_playSound('number_1');
	if (s_move == 'left')
		for (i = 0; i <= (YyY - 1); i++)
			for (ii = 0; ii <= (XxX - 2); ii++) {
				iii = i * XxX + ii;
				f_move_(a_block[iii], a_block[iii + 1]);
			}
	if (s_move == 'right')
		for (i = 0; i <= (YyY - 1); i++)
			for (ii = (XxX - 1); ii >= 1; ii--) {
				iii = i * XxX + ii;
				f_move_(a_block[iii], a_block[iii - 1]);
			}
	if (s_move == 'up')
		for (ii = 0; ii <= (XxX - 1); ii++)
			for (i = 0; i <= (YyY - 2); i++) {
				iii = i * XxX + ii;
				f_move_(a_block[iii], a_block[iii + XxX]);
			}
	if (s_move == 'down')
		for (ii = 0; ii <= (XxX - 1); ii++)
			for (i = (YyY - 1); i >= 1; i--) {
				iii = i * XxX + ii;
				f_move_(a_block[iii], a_block[iii - XxX]);
			}
	flag_MOVE = s_move;
	for (var i = 0; i < a_block.length; i++)
		if (a_block[i].live > 0) a_block[i].live--;
		else a_block[i].mul = 1;
}

function f_move_(a, b) {
	if (a.number != 1 && a.number != 0 && b.number != 0)
		if (a.number == b.number) {
			a.mul = a.mul + b.mul;
			a.live = i_live;
			i_score = i_score + b.number * a.mul;
			a.number = a.number * 2;
			b.number = 0;
			f_marger(a);
		}
}

function f_anim() {
	if (flag_MOVE != false) {
		flag_OK = true;
		if (flag_MOVE == 'left')
			for (i = 0; i <= (YyY - 1); i++)
				for (ii = 1; ii <= (XxX - 1); ii++) {
					iii = i * XxX + ii;
					f_anim_(a_block[iii], a_block[iii - 1]);
				}
		if (flag_MOVE == 'right')
			for (i = 0; i <= (YyY - 1); i++)
				for (ii = (XxX - 2); ii >= 0; ii--) {
					iii = i * XxX + ii;
					f_anim_(a_block[iii], a_block[iii + 1]);
				}
		if (flag_MOVE == 'up')
			for (ii = 0; ii <= (XxX - 1); ii++)
				for (i = (0 + 1); i <= (YyY - 1); i++) {
					iii = i * XxX + ii;
					f_anim_(a_block[iii], a_block[iii - XxX]);
				}
		if (flag_MOVE == 'down')
			for (ii = 0; ii <= (XxX - 1); ii++)
				for (i = (YyY - 2); i >= 0; i--) {
					iii = i * XxX + ii;
					f_anim_(a_block[iii], a_block[iii + XxX]);
				}

		if (flag_OK == true) {
			flag_MOVE = false;
			flag_PLAY = true;
			e_scoreViewer.innerHTML = i_score;
			f_verify();
		}
		f_paint();
	}
	setTimeout("f_anim ()", 70);
}

function f_anim_(a, b) {
	if (a.number != 1 && a.number != 0 && b.number == 0) {
		flag_OK = false;
		b.number = a.number;
		a.number = 0;
		b.mul = a.mul;
		b.live = a.live;
	}
}

function f_paint() {
	for (i = 0; i <= XxX * YyY - 1; i++) {
		e = a_block[i].parentNode;
		if (a_block[i].mul != 1 && a_block[i].number != 0) {
			e.getElementsByTagName('i')[0].style.display = 'block';
			e.getElementsByTagName('i')[0].style.opacity = 0.3 + a_block[i].live / 7;
			e.getElementsByTagName('i')[0].innerHTML = 'х' + a_block[i].mul;
		}
		else e.getElementsByTagName('i')[0].style.display = 'none';

		if (a_block[i].number == 0 || a_block[i].number == 1) a_block[i].innerHTML = '&nbsp';
		else a_block[i].innerHTML = a_block[i].number;
		switch (a_block[i].number) {
			case 2:
				col = '#ffd';
				break;
			case 4:
				col = '#ffb';
				break;
			case 8:
				col = '#fdb';
				break;
			case 16:
				col = '#fbd';
				break;
			case 32:
				col = '#add';
				break;
			case 64:
				col = '#9df';
				break;
			case 128:
				col = '#8fd';
				break;
			case 256:
				col = '#5f5';
				break;
			case 512:
				col = '#44f';
				break;
			case 1024:
				col = '#3aa';
				break;
			case 0:
				col = '#fff';
				break;
			case 1:
				col = '#aaa';
				break;
		}
		o_parent = a_block[i].parentNode;
		o_parent.style.background = col;
	}
}

function f_verify() {
	if (a_block[XxX + 1].number == 0) a_block[XxX + 1].number = 1;
	if (a_block[XxX * YyY - XxX - 2].number == 0) a_block[XxX * YyY - XxX - 2].number = 1;
	var arr = [1];
	flag_OK = true;
	for (var i = 0; i <= XxX * YyY - 1; i++) {
		for (var ii = 0; ii <= arr.length; ii++) {
			if (a_block[i].number != 1 && a_block[i].number != 0) if (a_block[i].number == arr[ii]) { flag_OK = false; break; }
		}
		if (flag_OK == true) arr.push(a_block[i].number);
		else break;
	}
	if (flag_OK == true) f_saveGame();
}

function f_newGame() {
	for (i = 0; i <= (XxX * YyY) - 1; i++) {
		i_int = Math.ceil(Math.random() * 20);
		switch (true) {
			case i_int >= 0 && i_int <= 3:
				a_block[i].number = 2;
				break;
			case i_int >= 4 && i_int <= 7:
				a_block[i].number = 4;
				break;
			case i_int >= 8 && i_int <= 15:
				a_block[i].number = 8;
				break;
			case i_int >= 16 && i_int <= 20:
				a_block[i].number = 16;
				break;
		}
		a_block[i].mul = 1;
		i_canvasKeymap += a_block[i].number + '/';
	}
	f_paint();
}

function f_oldGame() {
	a_gameDB = [];
	a_gameDB = i_canvasKeymap.split("/");
	for (i = 0; i <= (XxX * YyY) - 1; i++) {
		a_block[i].number = a_gameDB[i];
		a_block[i].number = a_block[i].number * 1;
		a_block[i].mul = 1;
	}
	f_paint();
}
