var a_Element = new Array();
for (i = 0; i <= 5; i++) {
	a_Element[i] = new Array();
	for (ii = 1; ii <= 4; ii++) {
		a_Element[i][ii] = new Array();
	}
}
a_Element[0][1] = Array(0, 0, 0, 0);
a_Element[1][1] = Array(1, 0, 1, 0); a_Element[1][2] = Array(0, 1, 0, 1);
a_Element[2][1] = Array(1, 1, 0, 0); a_Element[2][2] = Array(0, 1, 1, 0); a_Element[2][3] = Array(0, 0, 1, 1); a_Element[2][4] = Array(1, 0, 0, 1);
a_Element[3][1] = Array(1, 1, 1, 0); a_Element[3][2] = Array(0, 1, 1, 1); a_Element[3][3] = Array(1, 0, 1, 1); a_Element[3][4] = Array(1, 1, 0, 1);
a_Element[4][1] = Array(1, 1, 1, 1);
a_Element[5][1] = Array(0, 1, 0, 1);

var XxX = 17;
var YyY = 12;
var i_ballov_1 = 0;
var i_ballov_2 = 0;
var s_e = String('');
var flag_BRIDGING = false;
var flag_SHIFT = false;
var mouseX = 0;
var mouseY = 0;
var s_mess = "";
var event_X, event_Y;

function f_greateGame() {
	e = document.getElementById('game')
	e.style.width = (XxX - 1) * 40 + "px";
	e.addEventListener('touchmove', function (event) {
		event.preventDefault();
	}, false);
	e.addEventListener('touchstart', function (event) {
		event = (event) ? event : window.event;
		event_X = event.targetTouches[0].clientX;
		event_Y = event.targetTouches[0].clientY;
		idXYone = event.target.id.match(/(\d+)/g);
		idXYone[0] = idXYone[0] - 0;
		flag_SHIFT = true;
		return false;
	});
	e.addEventListener('touchmove', f_Shift);
	e.addEventListener('touchend', function () { flag_SHIFT = false; });


	// Создаем элементы игрового поля
	for (i = 1; i <= YyY; i++) {
		var e = document.createElement('img');
		document.getElementById('game').appendChild(e);
		e.id = 'eX1Y' + i;
		e.src = 'img/bridging_5_1.gif';
		for (ii = 2; ii <= (XxX - 1); ii++) {
			var e = document.createElement('img');
			document.getElementById('game').appendChild(e);
			e.id = 'eX' + ii + 'Y' + i;

			if ((ii != 2 || i != YyY) && (ii != (XxX - 1) || i != YyY)) {
				e.onclick = f_Turn;
				e.onmousedown = function () {
					idXYone = this.id.match(/(\d+)/g);
					idXYone[0] = idXYone[0] - 0;
					flag_SHIFT = true;
					return false;
				};
				e.onmouseover = f_Over;
				e.onmousemove = f_Shift;
				e.onmouseout = f_Out;
			}
			e.src = 'img/bridging_0_1.gif';
		}
		var e = document.createElement('img');
		document.getElementById('game').appendChild(e);
		e.id = 'eX' + XxX + 'Y' + i;
		e.src = 'img/bridging_5_1.gif';
	}
	var e = document.createElement('p');
	document.getElementById('game').appendChild(e);
	e.id = 'n_ballov_1';
	e.className = 'border_inset big';
	e.style.position = 'absolute';
	e.style.bottom = '7px';
	e.style.left = '7px';
	var e = document.createElement('p');
	document.getElementById('game').appendChild(e);
	e.id = 'n_ballov_2';
	e.className = 'border_inset big';
	e.style.position = 'absolute';
	e.style.bottom = '7px';
	e.style.right = '7px';
	f_Game();
}

document.onmouseup = new Function("flag_SHIFT = false;");
// Для анимации

function f_Game() {
	if (flag_BRIDGING == true && flag_DOWN == false) {
		if (flag_PAUSE != 0) flag_PAUSE--;
		else { flag_DOWN = true; }
	}
	if (flag_BRIDGING == true && flag_DOWN == true) {
		flag_OK = false;
		for (i = (YyY - 1); i >= 1; i--) {
			for (ii = 2; ii <= (XxX - 1); ii++) {
				if (document.getElementById('eX' + ii + 'Y' + i).nomer != 0 && document.getElementById('eX' + ii + 'Y' + (i + 1)).nomer == 0) {
					document.getElementById('eX' + ii + 'Y' + (i + 1)).src = document.getElementById('eX' + ii + 'Y' + i).src;
					document.getElementById('eX' + ii + 'Y' + (i + 1)).nomer = document.getElementById('eX' + ii + 'Y' + i).nomer;
					document.getElementById('eX' + ii + 'Y' + (i + 1)).angle = document.getElementById('eX' + ii + 'Y' + i).angle;
					document.getElementById('eX' + ii + 'Y' + i).src = 'img/bridging_0_1.gif';
					document.getElementById('eX' + ii + 'Y' + i).nomer = 0;
					document.getElementById('eX' + ii + 'Y' + i).angle = 1;
					flag_OK = true;
				}
			}
		}
		if (flag_OK == false) {
			document.getElementById('k_endGame').style.display = 'inline-block';
			flag_BRIDGING = false;
			flag_DOWN = false;
			document.getElementById('myNballov').innerHTML = i_score;
			document.getElementById('eX2Y' + YyY).nomer = 4;
			document.getElementById('eX' + (XxX - 1) + 'Y' + YyY).nomer = 4;
			document.getElementById('eX2Y' + YyY).angle = 1;
			document.getElementById('eX' + (XxX - 1) + 'Y' + YyY).angle = 1;
			f_Verify();
		}
	}
	setTimeout("f_Game ()", 100);
}
//Поварачиваем фигуры
function f_Turn() {
	if (flag_BRIDGING != true && flag_PLAY == true) {
		if (this.nomer == 1) { if (this.angle == 1) { this.angle = 2; } else { this.angle = 1; } }
		if (this.nomer == 2 || this.nomer == 3) this.angle += 1;
		if (this.angle == 5) this.angle = 1;
		this.src = 'img/bridging_' + this.nomer + '_' + this.angle + '.gif';
		i_motion++;
		f_Verify();
	}
}


//Сдвигаем фигуры
function f_Shift(event) {
	if (flag_BRIDGING != true && flag_SHIFT == true) {
		if (event.type == 'touchmove') {
			idXYtwo = event.target.id.match(/(\d+)/g);
			idXYtwo[0] = idXYone[0];
			event = event.targetTouches[0];
			if (event_X + 20 < event.clientX) idXYtwo[0]++;
			if (event_X - 20 > event.clientX) idXYtwo[0]--;
			t = document.getElementById('eX' + idXYtwo[0] + 'Y' + idXYtwo[1]);
		}
		else {
			idXYtwo = this.id.match(/(\d+)/g);
			t = this;
		}
		if (idXYone[0] != idXYtwo[0] && idXYtwo[0] != 1 && idXYtwo[0] != XxX) {
			if (t.nomer == 0) {
				myElem = document.getElementById('eX' + idXYone[0] + 'Y' + idXYone[1]);
				t.src = myElem.src;
				t.nomer = myElem.nomer;
				t.angle = myElem.angle;
				myElem.src = 'img/bridging_0_1.gif';
				myElem.nomer = 0;
				myElem.angle = 1;
				flag_PAUSE = 0;
				flag_BRIDGING = true;
			}
			flag_SHIFT = false;
		}
	}
}

function f_Over(e) {
	XY = this.id.match(/(\d+)/g);
	if (this.nomer != 0 && flag_SHIFT != true) {
		if (XY[0] != 1) {
			eTo = document.getElementById('eX' + (XY[0] - 1) + 'Y' + XY[1]);
			if (eTo.nomer == 0) eTo.src = 'img/bridging_0_1_1.gif';
		}
		if (XY[0] != XxX) {
			XY[0]++;
			eTo = document.getElementById('eX' + XY[0] + 'Y' + XY[1]);
			if (eTo.nomer == 0) eTo.src = 'img/bridging_0_1_2.gif';
		}
	}
}
function f_Out(e) {
	e = e || window.event;
	XY = this.id.match(/(\d+)/g);
	if (this.nomer != 0) {
		if (XY[0] != 1) {
			eTo = document.getElementById('eX' + (XY[0] - 1) + 'Y' + XY[1]);
			if (eTo.nomer == 0) eTo.src = 'img/bridging_0_1.gif';
		}
		if (XY[0] != XxX) {
			XY[0]++;
			eTo = document.getElementById('eX' + XY[0] + 'Y' + XY[1]);
			if (eTo.nomer == 0) eTo.src = 'img/bridging_0_1.gif';
		}
	}
}
function f_Verify() {
	// Обнуляем перед проверкой
	for (i = 1; i <= YyY; i++) {
		for (ii = 1; ii <= XxX; ii++) document.getElementById('eX' + ii + 'Y' + i).charge = 0;
	}
	i_ballov_1 = 0;
	i_ballov_2 = 0;
	flag_BRIDGING = false;


	// Устанавливаем стартовые элементы

	document.getElementById('eX2Y' + YyY).charge = 1;
	document.getElementById('eX' + (XxX - 1) + 'Y' + YyY).charge = 3;

	// Проверка центра
	flag_OK = true;
	while (flag_OK == true) {
		flag_OK = false;
		for (i = 1; i <= YyY; i++) {
			for (ii = 1; ii <= XxX; ii++) {
				e = document.getElementById('eX' + ii + 'Y' + i);
				if (e.charge == 1) {
					if (i != 1) {
						eTo = document.getElementById('eX' + ii + 'Y' + (i - 1))
						if (a_Element[e.nomer][e.angle][0] == 1 && a_Element[eTo.nomer][eTo.angle][2] == 1) {
							i_ballov_1++;
							if (eTo.charge == 0) { eTo.charge = 1; flag_OK = true; }
							if (eTo.charge == 3 || eTo.charge == 4) flag_BRIDGING = true;
						}
					}
					if (ii != XxX) {
						eTo = document.getElementById('eX' + (ii + 1) + 'Y' + i)
						if (a_Element[e.nomer][e.angle][1] == 1 && a_Element[eTo.nomer][eTo.angle][3] == 1) {
							i_ballov_1++;
							if (eTo.charge == 0) { eTo.charge = 1; flag_OK = true; }
							if (eTo.charge == 3 || eTo.charge == 4) flag_BRIDGING = true;
						}
					}
					if (i != YyY) {
						eTo = document.getElementById('eX' + ii + 'Y' + (i + 1))
						if (a_Element[e.nomer][e.angle][2] == 1 && a_Element[eTo.nomer][eTo.angle][0] == 1) {
							i_ballov_1++;
							if (eTo.charge == 0) { eTo.charge = 1; flag_OK = true; }
							if (eTo.charge == 3 || eTo.charge == 4) flag_BRIDGING = true;
						}
					}
					if (ii != 1) {
						eTo = document.getElementById('eX' + (ii - 1) + 'Y' + i)
						if (a_Element[e.nomer][e.angle][3] == 1 && a_Element[eTo.nomer][eTo.angle][1] == 1) {
							i_ballov_1++;
							if (eTo.charge == 0) { eTo.charge = 1; flag_OK = true; }
							if (eTo.charge == 3 || eTo.charge == 4) flag_BRIDGING = true;
						}
					}
					e.charge = 2;
					if (e.nomer == 5) i_ballov_1 += 7;
				}
				if (e.charge == 3) {
					if (i != 1) {
						eTo = document.getElementById('eX' + ii + 'Y' + (i - 1))
						if (a_Element[e.nomer][e.angle][0] == 1 && a_Element[eTo.nomer][eTo.angle][2] == 1) {
							i_ballov_2++;
							if (eTo.charge == 0) { eTo.charge = 3; flag_OK = true; }
						}
					}
					if (ii != XxX) {
						eTo = document.getElementById('eX' + (ii + 1) + 'Y' + i)
						if (a_Element[e.nomer][e.angle][1] == 1 && a_Element[eTo.nomer][eTo.angle][3] == 1) {
							i_ballov_2++;
							if (eTo.charge == 0) { eTo.charge = 3; flag_OK = true; }
						}
					}
					if (i != YyY) {
						eTo = document.getElementById('eX' + ii + 'Y' + (i + 1))
						if (a_Element[e.nomer][e.angle][2] == 1 && a_Element[eTo.nomer][eTo.angle][0] == 1) {
							i_ballov_2++;
							if (eTo.charge == 0) { eTo.charge = 3; flag_OK = true; }
						}
					}
					if (ii != 1) {
						eTo = document.getElementById('eX' + (ii - 1) + 'Y' + i)
						if (a_Element[e.nomer][e.angle][3] == 1 && a_Element[eTo.nomer][eTo.angle][1] == 1) {
							i_ballov_2++;
							if (eTo.charge == 0) { eTo.charge = 3; flag_OK = true; }
						}
					}
					e.charge = 4;
					if (e.nomer == 5) i_ballov_2 += 7;
				}
			}
		}
	}
	// Рисуем минусы и плюсы, проверяем на замыкание и считаем баллы
	if (flag_BRIDGING == false) {
		for (i = 1; i <= YyY; i++) {
			for (ii = 1; ii <= XxX; ii++) {
				e = document.getElementById('eX' + ii + 'Y' + i);
				if (e.charge == 2) e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '_1.gif';
				if (e.charge == 4) e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '_2.gif';
				if (e.charge == 0) e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '.gif';
			}
		}
	}
	else {
		i_score += i_ballov_1 + i_ballov_2;
		for (i = 1; i <= YyY; i++) {
			for (ii = 2; ii <= (XxX - 1); ii++) {
				e = document.getElementById('eX' + ii + 'Y' + i);
				if (e.charge == 2 || e.charge == 4) {
					e.src = 'img/bridging_0_1.gif';
					e.nomer = 0;
					e.angle = 1;
				}
			}
		}
		flag_PAUSE = 10;
	}

	document.getElementById('n_ballov_1').innerHTML = i_ballov_1;
	document.getElementById('n_ballov_2').innerHTML = i_ballov_2;
}
// Обнуляем
function f_newGame() {
	document.getElementById('k_endGame').style.display = 'none';
	for (i = 1; i <= YyY; i++) {
		e = document.getElementById('eX1Y' + i);
		e.nomer = 5;
		e.angle = 1;
		e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '.gif';
		e.charge = 0;
		for (ii = 2; ii <= (XxX - 1); ii++) {
			e = document.getElementById('eX' + ii + 'Y' + i);
			e.nomer = Math.ceil(Math.random() * 2.3);
			if (e.nomer == 1) e.angle = Math.ceil(Math.random() * 2);
			if (e.nomer == 4) e.angle = Number(1);
			if (e.nomer == 2 || e.nomer == 3) e.angle = Math.ceil(Math.random() * 4);
			e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '.gif';
			e.charge = Number(0);
			i_canvasKeymap = i_canvasKeymap + e.nomer + e.angle;
		}
		e = document.getElementById('eX' + XxX + 'Y' + i);
		e.nomer = 5;
		e.angle = 1;
		e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '.gif';
		e.charge = 0;

	}
	flag_BRIDGING = false;
	flag_DOWN = false;
	flag_SHIFT = false;
	document.getElementById('eX2Y' + YyY).nomer = 4;
	document.getElementById('eX' + (XxX - 1) + 'Y' + YyY).nomer = 4;
	document.getElementById('eX2Y' + YyY).angle = 1;
	document.getElementById('eX' + (XxX - 1) + 'Y' + YyY).angle = 1;
	f_Verify();
}
function f_oldGame() {
	for (i = 1; i <= YyY; i++) {
		e = document.getElementById('eX1Y' + i);
		e.nomer = 5;
		e.angle = 1;
		e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '.gif';
		e.charge = 0;
		for (ii = 2; ii <= (XxX - 1); ii++) {
			qq = (i - 1); qq *= (XxX - 2); qq += (ii - 2); qq *= 2;
			e = document.getElementById('eX' + ii + 'Y' + i);
			e.nomer = Number(i_canvasKeymap.substr(qq, 1));
			e.angle = Number(i_canvasKeymap.substr((qq + 1), 1));
			e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '.gif';
			e.charge = Number(0);
		}
		e = document.getElementById('eX' + XxX + 'Y' + i);
		e.nomer = 5;
		e.angle = 1;
		e.src = 'img/bridging_' + e.nomer + '_' + e.angle + '.gif';
		e.charge = 0;
	}
	flag_BRIDGING = false;
	flag_DOWN = false;
	flag_SHIFT = false;
	document.getElementById('eX2Y' + YyY).nomer = 4;
	document.getElementById('eX' + (XxX - 1) + 'Y' + YyY).nomer = 4;
	document.getElementById('eX2Y' + YyY).angle = 1;
	document.getElementById('eX' + (XxX - 1) + 'Y' + YyY).angle = 1;
	f_Verify();
}
