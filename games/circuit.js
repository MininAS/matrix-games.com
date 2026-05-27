var XxX = 15;
var YyY = 12;
const layout = new Array();
var i_ballov = 0;
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
	home = document.getElementById('game')
	home.style.width = XxX * 40 + "px";
	home.oncontextmenu = new Function("return false;");

	// Создаем элементы игрового поля
	for (y = 1; y <= YyY; y++) layout[y] = new Array();

	for (y = 1; y <= YyY; y++) {
		for (x = 1; x <= XxX; x++) {
			var e = document.createElement('img');
			home.appendChild(e);
			e.id = 'eX' + x + 'Y' + y;
			e.style.width = 40 + 'px';
			e.style.height = 40 + 'px';
			e.style.borderRadius = '8px';
			e.style.transition = 'all 0.1s ease-in';
			e.addEventListener('transitionend',  function() {
				f_checkRotateAngle.call(this);
				f_Verify.call(this);
			});
			e.onclick = f_Turn;
			e.onmousedown = f_Turn_R;
			layout[y][x] = e;
		}
	}
	window.cross_blue = layout[1][1];
	window.cross_red = layout[YyY][XxX];
}

// Поворачиваем фигуры
function f_Turn() {
	if (flag_BRIDGING != true && flag_PLAY == true) {
		if (this.number == 4) return
		flag_PLAY = false;
		this.angle += 1;
		this.src = 'img/bridging_' + this.number + '.jpg?v=' + version;
		this.style.transform = 'rotate(' + ((this.angle - 1) * 90) + 'deg)';
		i_motion++;
	}
}

function f_Turn_R(e) // Правая кнопка мыши
{
	if (flag_BRIDGING != true && flag_PLAY == true) {
		if (e.button == 2) {
			if (this.number == 4) return
			flag_PLAY = false;
			this.angle -= 1;
			this.src = 'img/bridging_' + this.number + '.jpg?v=' + version;
			this.style.transform = 'rotate(' + ((this.angle - 1) * 90) + 'deg)';
			i_motion++;
			e.stopPropagation();
			e.preventDefault();
			return false;
		}
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
	if (this.angle == 0) {
		this.angle = 4;
		current = this.style.transition;
		this.style.transition = 'none';
		this.style.transform = 'rotate(270deg)';
		setTimeout(() => {
			this.style.transition = current;
		}, 0);
	}
}

function f_Verify() {
	// Обнуляем перед проверкой
	for (y = 1; y <= YyY; y++) {
		for (x = 1; x <= XxX; x++) layout[y][x].charge = 0;
	}
	i_ballov = 0;
	flag_BRIDGING = false;

	// Устанавливаем стартовые элементы
	cross_blue.charge = 1;
	cross_red.charge = 3;

	// Проверка проводников на контакт
	flag_OK = true;
	while (flag_OK == true) {
		flag_OK = false;
		for (y = 1; y <= YyY; y++) {
			for (x = 1; x <= XxX; x++) {
				let e = layout[y][x];
				if ((e.charge == 1 || e.charge == 3) && e.number != 0) {
					if (y != 1 && layout[y - 1][x].number != 0) {
						eTo = layout[y - 1][x]
						if (a_Element[e.number][e.angle][0] == 1 && a_Element[eTo.number][eTo.angle][2] == 1) {
							if (eTo.charge == 0) { eTo.charge = e.charge; flag_OK = true; }
							if (e.charge == 1 && (eTo.charge == 3 || eTo.charge == 4)) flag_BRIDGING = true;
						}
					}
					if (x != XxX && layout[y][x + 1].number != 0) {
						eTo = layout[y][x + 1]
						if (a_Element[e.number][e.angle][1] == 1	&& a_Element[eTo.number][eTo.angle][3] == 1) {
							if (eTo.charge == 0) { eTo.charge = e.charge; flag_OK = true; }
							if (e.charge == 1 && (eTo.charge == 3 || eTo.charge == 4)) flag_BRIDGING = true;
						}
					}
					if (y != YyY && layout[y + 1][x].number != 0) {
						eTo = layout[y + 1][x]
						if (a_Element[e.number][e.angle][2] == 1	&& a_Element[eTo.number][eTo.angle][0] == 1) {
							if (eTo.charge == 0) { eTo.charge = e.charge; flag_OK = true; }
							if (e.charge == 1 && (eTo.charge == 3 || eTo.charge == 4)) flag_BRIDGING = true;
						}
					}
					if (x != 1 && layout[y][x - 1].number != 0) {
						eTo = layout[y][x - 1]
						if (a_Element[e.number][e.angle][3] == 1 && a_Element[eTo.number][eTo.angle][1] == 1) {
							if (eTo.charge == 0) { eTo.charge = e.charge; flag_OK = true; }
							if (e.charge == 1 && (eTo.charge == 3 || eTo.charge == 4)) flag_BRIDGING = true;
						}
					}
					if (e.charge == 1) e.charge = 2;
					if (e.charge == 3) e.charge = 4;
					i_ballov += e.number
				}
			}
		}
	}
	// Проверка лампочек на контакты
	for (y = 1; y <= YyY; y++) {
		for (x = 1; x <= XxX; x++) {
			let e = layout[y][x];
			let factor = 0;
			if (e.number == 0) {
				if (y != 1) {
					eTo = layout[y - 1][x]
					if (eTo.number != 0 && a_Element[eTo.number][eTo.angle][2] == 1 && (eTo.charge == 2 || eTo.charge == 4)) {
						factor += 1;
					}
				}
				if (x != XxX) {
					eTo = layout[y][x + 1]
					if (eTo.number != 0 && a_Element[eTo.number][eTo.angle][3] == 1 && (eTo.charge == 2 || eTo.charge == 4)) {
						factor += 1;
					}
				}
				if (y != YyY) {
					eTo = layout[y + 1][x]
					if (eTo.number != 0 && a_Element[eTo.number][eTo.angle][0] == 1 && (eTo.charge == 2 || eTo.charge == 4)) {
						factor += 1;
					}
				}
				if (x != 1) {
					eTo = layout[y][x - 1]
					if (eTo.number != 0 && a_Element[eTo.number][eTo.angle][1] == 1 && (eTo.charge == 2 || eTo.charge == 4)) {
						factor += 1;
					}
				}
				if (factor != 0) i_ballov += Math.ceil(5 * (2.41 ** (factor - 1)));
				e.factor = factor;
			}
		}
	}
	// Рисуем минусы и плюсы, проверяем на замыкание и считаем баллы
	if (flag_BRIDGING) {
		i_score = i_score + 25;
		document.getElementById('myNballov').innerHTML = i_score;
		for (y = 1; y <= YyY; y++) {
			for (x = 1; x <= XxX; x++) {
				e = layout[y][x];
				if (e.number == 0) {
					e.src = 'img/bridging_0_5.jpg?v=' + version;
					continue;
				}
				if (e.charge != 0 ) e.src = 'img/bridging_' + e.number + '_3.jpg?v=' + version;
			}
		}
        f_saveGame(true);
	}
	else {
		i_score = i_ballov;
		for (y = 1; y <= YyY; y++) {
			for (x = 1; x <= XxX; x++) {
				e = layout[y][x];
				if (e.number == 0) {
					e.src = 'img/bridging_0_' + e.factor + '.jpg?v=' + version;
					continue;
				}
				if (e.charge == 2) e.src = 'img/bridging_' + e.number + '_1.jpg?v=' + version;
				if (e.charge == 4) e.src = 'img/bridging_' + e.number + '_2.jpg?v=' + version;
				if (e.charge == 0) e.src = 'img/bridging_' + e.number + '.jpg?v=' + version;
			}
		}
		document.getElementById('myNballov').innerHTML = i_score;
		flag_PLAY = true;
	    f_saveGame();
	}
}
// Обнуляем
function f_newGame() {
	for (y = 1; y <= YyY; y++) {
		for (x = 1; x <= XxX; x++) {
			e = layout[y][x];
			e.number = getRandomWithExtremes();
			if (e.number == 1) e.angle = Math.ceil(Math.random() * 2);
			else if (e.number == 4) e.angle = Number(1);
			else e.angle = Math.ceil(Math.random() * 4);
			e.src = 'img/bridging_' + e.number + '.jpg?v=' + version;
			e.charge = Number(0);
			e.style.transform = 'rotate(' + ((e.angle - 1) * 90) + 'deg)';
			i_canvasKeymap = i_canvasKeymap + e.number + e.angle;
		}
	}
	flag_BRIDGING = false;
	flag_DOWN = false;
	flag_SHIFT = false;
	cross_blue.number = 4;
	cross_red.number = 4;
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
	for (y = 1; y <= YyY; y++) {
		for (x = 1; x <= XxX; x++) {
			qq = (y - 1) * XxX + (x - 1); qq *= 2;
			e = layout[y][x];
			e.number = Number(i_canvasKeymap.substr(qq, 1));
			e.angle = Number(i_canvasKeymap.substr((qq + 1), 1));
			e.src = 'img/bridging_' + e.number + '.jpg?v=' + version;
			e.style.transform = 'rotate(' + ((e.angle - 1) * 90) + 'deg)';
			e.charge = Number(0);
		}
	}
	flag_BRIDGING = false;
	flag_DOWN = false;
	flag_SHIFT = false;
	cross_blue.number = 4;
	cross_red.number = 4;
	cross_blue.angle = 1;
	cross_red.angle = 1;
	f_Verify();
}
