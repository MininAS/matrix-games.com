var XxX = 15;
var YyY = 12;
var i_ballov_1 = 0;
var i_ballov_2 = 0;
var s_e = String('');
var flag_BRIDGING = false;
var mouseX = 0;
var mouseY = 0;
var s_mess = "";
var event_X, event_Y;

var a_Element = new Array();
for (i = 0; i <= 5; i++) {
	a_Element[i] = new Array();
	for (ii = 1; ii <= 4; ii++) {
		a_Element[i][ii] = new Array();
	}
}

a_Element[0][1] = Array(0, 0, 1, 0); a_Element[0][2] = Array(0, 0, 0, 1); a_Element[0][3] = Array(1, 0, 0, 0); a_Element[0][4] = Array(0, 1, 0, 0);
a_Element[1][1] = Array(1, 0, 1, 0); a_Element[1][2] = Array(0, 1, 0, 1); a_Element[1][3] = Array(1, 0, 1, 0); a_Element[1][4] = Array(0, 1, 0, 1);
a_Element[2][1] = Array(1, 1, 0, 0); a_Element[2][2] = Array(0, 1, 1, 0); a_Element[2][3] = Array(0, 0, 1, 1); a_Element[2][4] = Array(1, 0, 0, 1);
a_Element[3][1] = Array(1, 1, 1, 0); a_Element[3][2] = Array(0, 1, 1, 1); a_Element[3][3] = Array(1, 0, 1, 1); a_Element[3][4] = Array(1, 1, 0, 1);
a_Element[4][1] = Array(1, 1, 1, 1);

function f_createGame() {
	e = document.getElementById('game')
	e.style.width = XxX * 40 + "px";

	// Создаем элементы игрового поля
	for (i = 1; i <= YyY; i++) {
		for (ii = 1; ii <= XxX; ii++) {
			var e = document.createElement('img');
			document.getElementById('game').appendChild(e);
			e.id = 'eX' + ii + 'Y' + i;
			e.style.width = 40 + 'px';
			e.style.height = 40 + 'px';
			e.style.borderRadius = '8px';
			e.style.transition = 'all 0.1s ease-in';
			e.addEventListener('transitionend',  function() {
				f_checkRotateAngle.call(this);
				f_Verify.call(this);
			});
			e.onclick = f_Turn;
		}
	}
	window.cross_blue = document.getElementById('eX1Y1');
	window.cross_red = document.getElementById('eX' + XxX + 'Y' + YyY);
}

// Поворачиваем фигуры
function f_Turn() {
	if (flag_BRIDGING != true && flag_PLAY == true) {
		if (this.nomer == 4) return
		flag_PLAY = false;
		this.angle += 1;
		this.src = 'img/bridging_' + this.nomer + '.jpg';
		this.style.transform = 'rotate(' + ((this.angle - 1) * 90) + 'deg)';
		i_motion++;
	}
}

function f_checkRotateAngle() {
	if (this.angle == 5) {
		this.angle = 1;
		current = this.style.transition;
		this.style.transition = 'none';
		this.style.transform = 'rotate(0deg)';
		setTimeout(() => {
			this.style.transition = current;
		}, 0);
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
	cross_blue.charge = 1;
	cross_red.charge = 3;

	// Проверка центра
	flag_OK = true;
	while (flag_OK == true) {
		flag_OK = false;
		for (i = 1; i <= YyY; i++) {
			for (ii = 1; ii <= XxX; ii++) {
				let e = document.getElementById('eX' + ii + 'Y' + i);
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
						if (a_Element[e.nomer][e.angle][1] == 1
							&& a_Element[eTo.nomer][eTo.angle][3] == 1) {
							i_ballov_1++;
							if (eTo.charge == 0) { eTo.charge = 1; flag_OK = true; }
							if (eTo.charge == 3 || eTo.charge == 4) flag_BRIDGING = true;
						}
					}
					if (i != YyY) {
						eTo = document.getElementById('eX' + ii + 'Y' + (i + 1))
						if (a_Element[e.nomer][e.angle][2] == 1
							&& a_Element[eTo.nomer][eTo.angle][0] == 1) {
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
					if (e.nomer == 0) i_ballov_1 += 7;
				}
				if (e.charge == 3) {
					if (i != 1) {
						let eTo = document.getElementById('eX' + ii + 'Y' + (i - 1))
						if (a_Element[e.nomer][e.angle][0] == 1 && a_Element[eTo.nomer][eTo.angle][2] == 1) {
							i_ballov_2++;
							if (eTo.charge == 0) { eTo.charge = 3; flag_OK = true; }
						}
					}
					if (ii != XxX) {
						let eTo = document.getElementById('eX' + (ii + 1) + 'Y' + i)
						if (a_Element[e.nomer][e.angle][1] == 1 && a_Element[eTo.nomer][eTo.angle][3] == 1) {
							i_ballov_2++;
							if (eTo.charge == 0) { eTo.charge = 3; flag_OK = true; }
						}
					}
					if (i != YyY) {
						let eTo = document.getElementById('eX' + ii + 'Y' + (i + 1))
						if (a_Element[e.nomer][e.angle][2] == 1 && a_Element[eTo.nomer][eTo.angle][0] == 1) {
							i_ballov_2++;
							if (eTo.charge == 0) { eTo.charge = 3; flag_OK = true; }
						}
					}
					if (ii != 1) {
						let eTo = document.getElementById('eX' + (ii - 1) + 'Y' + i)
						if (a_Element[e.nomer][e.angle][3] == 1 && a_Element[eTo.nomer][eTo.angle][1] == 1) {
							i_ballov_2++;
							if (eTo.charge == 0) { eTo.charge = 3; flag_OK = true; }
						}
					}
					e.charge = 4;
					if (e.nomer == 0) i_ballov_2 += 7;
				}
			}
		}
	}
	// Рисуем минусы и плюсы, проверяем на замыкание и считаем баллы
	if (flag_BRIDGING) {
		i_score = i_score + 25;
		document.getElementById('myNballov').innerHTML = i_score;
		for (i = 1; i <= YyY; i++) {
			for (ii = 1; ii <= XxX; ii++) {
				e = document.getElementById('eX' + ii + 'Y' + i);
				if (e.charge != 0 ) e.src = 'img/bridging_' + e.nomer + '_3.jpg';
			}
		}
        f_saveGame(true);
	}
	else {
		i_ballov = i_ballov_1 + i_ballov_2;
		i_score = i_ballov;
		for (i = 1; i <= YyY; i++) {
			for (ii = 1; ii <= XxX; ii++) {
				e = document.getElementById('eX' + ii + 'Y' + i);
				if (e.charge == 2) e.src = 'img/bridging_' + e.nomer + '_1.jpg';
				if (e.charge == 4) e.src = 'img/bridging_' + e.nomer + '_2.jpg';
				if (e.charge == 0) e.src = 'img/bridging_' + e.nomer + '.jpg';
			}
		}
		document.getElementById('myNballov').innerHTML = i_score;
		flag_PLAY = true;
	    f_saveGame();
	}
}
// Обнуляем
function f_newGame() {
	for (i = 1; i <= YyY; i++) {
		for (ii = 1; ii <= XxX; ii++) {
			e = document.getElementById('eX' + ii + 'Y' + i);
			e.nomer = getRandomWithExtremes();
			if (e.nomer == 1) e.angle = Math.ceil(Math.random() * 2);
			else if (e.nomer == 4) e.angle = Number(1);
			else e.angle = Math.ceil(Math.random() * 4);
			e.src = 'img/bridging_' + e.nomer + '.jpg';
			e.charge = Number(0);
			e.style.transform = 'rotate(' + ((e.angle - 1) * 90) + 'deg)';
			i_canvasKeymap = i_canvasKeymap + e.nomer + e.angle;
		}
	}
	flag_BRIDGING = false;
	flag_DOWN = false;
	flag_SHIFT = false;
	cross_blue.nomer = 4;
	cross_red.nomer = 4;
	cross_blue.angle = 1;
	cross_red.angle = 1;
	f_Verify();
}

function getRandomWithExtremes() {
    const r = Math.random();
    // 10% на 0, 5% на 4, остальные 85% равномерно на 1-3
    if (r < 0.1) return 0;
    if (r > 0.95) return 4;
    return Math.floor(Math.random() * 3) + 1;
}

function f_oldGame() {
	for (i = 1; i <= YyY; i++) {
		for (ii = 1; ii <= XxX; ii++) {
			qq = (i - 1) * XxX + (ii - 1); qq *= 2;
			e = document.getElementById('eX' + ii + 'Y' + i);
			e.nomer = Number(i_canvasKeymap.substr(qq, 1));
			e.angle = Number(i_canvasKeymap.substr((qq + 1), 1));
			e.src = 'img/bridging_' + e.nomer + '.jpg';
			e.style.transform = 'rotate(' + ((e.angle - 1) * 90) + 'deg)';
			e.charge = Number(0);
		}
	}
	flag_BRIDGING = false;
	flag_DOWN = false;
	flag_SHIFT = false;
	cross_blue.nomer = 4;
	cross_red.nomer = 4;
	cross_blue.angle = 1;
	cross_red.angle = 1;
	f_Verify();
}
