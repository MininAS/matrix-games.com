var SquareColorLayer1 = new Array();	//Именной  массив игрового поля
var SquareColorLayer2 = new Array();	//Цифровой массив игрового поля
var SquareColorLayer3 = new Array();	//Массив для подсказки
var SquareColorLayer31 = new Array();	//Массив для подсказки
var SquareColorLayer4 = new Array();	//Массив игровых данных
var s_mess;		//Строка для сохранения на сервер массива №4 и итоговых баллов
var SquareOfFigure = new Array();	//8-мь переменных координат четырех кубиков фигуры
SquareOfFigure[1] = new Array();		//4-ре по X
SquareOfFigure[2] = new Array();		//4-ре по Y

var XxX = 12;				//Ширина игрового поля
var YyY = 20;				//Высота игрового поля
var CursorY = 1;			//Смещение курсора в массиве №4
var CursorX = 1;			//Смещение курсора в массиве №4
var flag_DOWN = false;		//Флаг для падения фигуры
var flag_STOP = false;		//Флаг для разрешения спуска
var flag_LINE = false;		//Флаг для распознования целой линий
var flag_ROTATE = true;		//Флаг для запрета поворота
var flag_NEWGAME = false;	//Флаг для новой игры
var flag_OLDGAME = false;	//Флаг для старой игры
var flag_NEWFIGURE = true;
var i_Speed = 1000;			//Скорость спуска фигуры
var i_SpeedTmp = 1000;
var i_NLine = 0;			//Кол линий удаляемых за один раз
var i_V = 0;				//Направление движения
var i_canvasState = 0;

//Создаем массив игрового поля
function f_createGame() {
	document.getElementById('game').style.width = XxX * 24 + 'px';
	for (i = 1; i <= YyY; i++) {
		for (ii = 1; ii <= XxX; ii++) {
			var myElement = document.createElement('img');
			document.getElementById('game').appendChild(myElement);
			myElement.id = 'mySquareX' + ii + 'Y' + i;
			myElement.onclick = f_RotateFigure;
			myElement.onmouseover = f_MoveFigure;
			myElement.i_x = ii;
			myElement.i_y = i;
			myElement.src = "img/stone_0.gif";
		}
	}
	var myElement = document.createElement('div');
	document.getElementById('game').appendChild(myElement);
	myElement.className = 'windowSite';
	myElement.id = 'nextFigure';
	myElement.style.position = 'absolute';
	myElement.style.top = 100 + 'px';
	myElement.style.right = '4%';
	myElement.style.width = '200px';

	var myElement = document.createElement('p');
	document.getElementById('nextFigure').appendChild(myElement);
	myElement.className = 'big';
	myElement.innerHTML = _l("The next shape");

	var myElement_ = document.createElement('div');
	document.getElementById('nextFigure').appendChild(myElement_);
	for (i = 101; i <= 102; i++) {
		var myElement = document.createElement('br');
		myElement_.appendChild(myElement);
		for (ii = 101; ii <= 104; ii++) {
			var myElement = document.createElement('img');
			myElement_.appendChild(myElement);
			myElement.name = 'mySquareX' + ii + 'Y' + i;
			myElement.src = "img/stone_0.gif";
		}
	}
	document.getElementById('k_pauseGame').style.display = 'inline-block';
	StartLayers();
}

//Перенаправляем правую кнопку мыши и вкл.клавиши
document.oncontextmenu = new Function("return false;");
document.onkeydown = f_KeyPress;
document.onmousedown = function f_Click(event) {
	if (flag_PLAY == true) {
		e = (event) ? event : window.event;
		if (e.button == 2 || e.button == 4) { clearTimeout(timeout_id); flag_DOWN = true; f_Tetris(); e.stopPropagation(); e.preventDefault(); return false; }
	}
}
document.onmouseup = function f_Click(event) {
	if (flag_PLAY == true) {
		e = (event) ? event : window.event;
		if (e.button == 2) { flag_DOWN = false; e.stopPropagation(); e.preventDefault(); return false; }
	}
}
function f_KeyPress(event) {
	if (flag_PLAY == true) {
		event = (event) ? event : window.event;
		evt = (event.keyCode) ? event.keyCode : event.which;
		if (evt == 87 || evt == 38) { f_RotateFigure(); return false; }
		if (evt == 83 || evt == 40) { clearTimeout(timeout_id); flag_DOWN = true; f_Tetris(); event.stopPropagation(); event.preventDefault(); return false; }
		if (evt == 65 || evt == 37) { i_V = 1; f_MoveFigure(); return false; }
		if (evt == 68 || evt == 39) { i_V = 2; f_MoveFigure(); return false; }
		if (evt == 32) { f_windowInfoPopup('pause'); return false; }
	}
}
document.onkeyup = function f_KeyUp(event) {
	if (flag_PLAY == true) {
		event = (event) ? event : window.event;
		evt = (event.keyCode) ? event.keyCode : event.which;
		if (evt == 1099 || evt == 40) { flag_DOWN = false; event.stopPropagation(); event.preventDefault(); return false; }
	}
}

//Определяем массивы
function StartLayers() {
	for (i = 1; i <= XxX; i++) {
		SquareColorLayer1[i] = new Array();
		SquareColorLayer2[i] = new Array();
		SquareColorLayer4[i] = new Array();
		for (ii = 1; ii <= YyY; ii++) {
			SquareColorLayer1[i][ii] = "mySquareX" + i + "Y" + ii;
		}
	}
	for (i = 1; i <= 4; i++) {
		SquareColorLayer31[i] = new Array();
		for (ii = 1; ii <= 2; ii++) {
			SquareColorLayer31[i][ii] = "mySquareX10" + i + "Y10" + ii;
		}
	}
	setTimeout("f_Tetris ()", 1000);
}
//Определяем координаты квадратов следующей фигуры
function f_Tetris() {
	if (flag_PAUSE == false && flag_OLDGAME == false && flag_NEWGAME == false && flag_GAMEOVER == false) {
		if (flag_NEWFIGURE == true) {
			flag_DOWN = false;
			flag_NEWFIGURE = false;
			if (SquareColorLayer4[CursorX][CursorY] == 1) {
				SquareOfFigure[1][1] = XxX / 2; SquareOfFigure[2][1] = 1;
				SquareOfFigure[1][2] = (XxX / 2 - 1); SquareOfFigure[2][2] = 1;
				SquareOfFigure[1][3] = (XxX / 2 - 2); SquareOfFigure[2][3] = 1;
				SquareOfFigure[1][4] = XxX / 2; SquareOfFigure[2][4] = 2;
			}
			if (SquareColorLayer4[CursorX][CursorY] == 2) {
				SquareOfFigure[1][1] = XxX / 2; SquareOfFigure[2][1] = 1;
				SquareOfFigure[1][2] = (XxX / 2 + 1); SquareOfFigure[2][2] = 1;
				SquareOfFigure[1][3] = (XxX / 2 + 2); SquareOfFigure[2][3] = 1;
				SquareOfFigure[1][4] = XxX / 2; SquareOfFigure[2][4] = 2;
			}
			if (SquareColorLayer4[CursorX][CursorY] == 3) {
				SquareOfFigure[1][1] = XxX / 2; SquareOfFigure[2][1] = 1;
				SquareOfFigure[1][2] = (XxX / 2 - 1); SquareOfFigure[2][2] = 1;
				SquareOfFigure[1][3] = XxX / 2; SquareOfFigure[2][3] = 2;
				SquareOfFigure[1][4] = (XxX / 2 + 1); SquareOfFigure[2][4] = 2;
			}
			if (SquareColorLayer4[CursorX][CursorY] == 4) {
				SquareOfFigure[1][1] = XxX / 2; SquareOfFigure[2][1] = 1;
				SquareOfFigure[1][2] = (XxX / 2 + 1); SquareOfFigure[2][2] = 1;
				SquareOfFigure[1][3] = XxX / 2; SquareOfFigure[2][3] = 2;
				SquareOfFigure[1][4] = (XxX / 2 - 1); SquareOfFigure[2][4] = 2;
			}
			if (SquareColorLayer4[CursorX][CursorY] == 5) {
				SquareOfFigure[1][1] = XxX / 2; SquareOfFigure[2][1] = 1;
				SquareOfFigure[1][2] = (XxX / 2 + 1); SquareOfFigure[2][2] = 1;
				SquareOfFigure[1][3] = (XxX / 2 - 1); SquareOfFigure[2][3] = 1;
				SquareOfFigure[1][4] = XxX / 2; SquareOfFigure[2][4] = 2;
			}
			if (SquareColorLayer4[CursorX][CursorY] == 6) {
				SquareOfFigure[1][1] = XxX / 2; SquareOfFigure[2][1] = 1;
				SquareOfFigure[1][2] = (XxX / 2 + 1); SquareOfFigure[2][2] = 1;
				SquareOfFigure[1][3] = XxX / 2; SquareOfFigure[2][3] = 2;
				SquareOfFigure[1][4] = (XxX / 2 + 1); SquareOfFigure[2][4] = 2;
			}
			if (SquareColorLayer4[CursorX][CursorY] == 7) {
				SquareOfFigure[1][1] = XxX / 2; SquareOfFigure[2][1] = 1;
				SquareOfFigure[1][2] = (XxX / 2 + 1); SquareOfFigure[2][2] = 1;
				SquareOfFigure[1][3] = (XxX / 2 + 2); SquareOfFigure[2][3] = 1;
				SquareOfFigure[1][4] = (XxX / 2 - 1); SquareOfFigure[2][4] = 1;
			}
			//Проверяем на удаления линий и конец хода
			i_localScore = 0;
			i_motion++;
			i_NLine = 0;
			for (i = 1; i <= YyY; i++) {
				flag_LINE = true;
				for (ii = 1; ii <= XxX; ii++) {
					if (SquareColorLayer2[ii][i] == 0) { flag_LINE = false; }
				}
				if (flag_LINE == true) {
					i_NLine++;
					i_localScore += (YyY - i + 1);
					for (iii = i; iii > 1; iii--) {
						for (ii = 1; ii <= XxX; ii++) {
							SquareColorLayer2[ii][iii] = SquareColorLayer2[ii][iii - 1];
							document.images[SquareColorLayer1[ii][iii]].src = document.images[SquareColorLayer1[ii][iii - 1]].src;
						}
					}
				}
			}
			if (i_NLine == 2) i_localScore *= 2;
			if (i_NLine == 3) i_localScore *= 4;
			if (i_NLine == 4) i_localScore *= 8;

			i_score += i_localScore;
			e_scoreViewer.innerHTML = i_score;
			if (i_localScore != 0) f_saveGame();

			//Проверка на конец хода
			for (i = 1; i <= 4; i++)
				document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_" + SquareColorLayer4[CursorX][CursorY] + ".gif";
			for (i = 1; i <= XxX; i++)
				if (SquareColorLayer2[i][1] != 0) {
					f_saveGame(true);
					break;
				}
			//Рисуем подсказку
			if ((CursorX + 1) <= XxX) { iii = SquareColorLayer4[CursorX + 1][CursorY]; }
			else if ((CursorY + 1) <= YyY) { iii = SquareColorLayer4[1][CursorY + 1]; }
			else { iii = SquareColorLayer4[1][1]; }
			if (iii == 1) SquareColorLayer3 = new Array(1, 1, 2, 1, 3, 1, 3, 2);
			if (iii == 2) SquareColorLayer3 = new Array(1, 1, 2, 1, 3, 1, 1, 2);
			if (iii == 3) SquareColorLayer3 = new Array(1, 1, 2, 1, 2, 2, 3, 2);
			if (iii == 4) SquareColorLayer3 = new Array(2, 1, 3, 1, 1, 2, 2, 2);
			if (iii == 5) SquareColorLayer3 = new Array(1, 1, 2, 1, 3, 1, 2, 2);
			if (iii == 6) SquareColorLayer3 = new Array(1, 1, 2, 1, 1, 2, 2, 2);
			if (iii == 7) SquareColorLayer3 = new Array(1, 1, 2, 1, 3, 1, 4, 1);
			for (i = 1; i <= 4; i++) {
				for (ii = 1; ii <= 2; ii++) {
					document.images[SquareColorLayer31[i][ii]].src = "img/stone_0.gif";
				}
			}
			for (i = 0; i <= 7; i += 2) {
				document.images[SquareColorLayer31[SquareColorLayer3[i]][SquareColorLayer3[i + 1]]].src = "img/stone_" + iii + ".gif";
			}
			flag_PLAY = true;
		}
		//-----------------------------Сдвигаем фигуру вниз и проверяем на конец хода------------------------------------------------------------------
		else {
			//Проверяем на предкновение
			flag_STOP = false;
			for (i = 1; i <= 4; i++) {
				if (SquareOfFigure[2][i] == YyY || SquareColorLayer2[SquareOfFigure[1][i]][SquareOfFigure[2][i] + 1] != 0) {
					flag_STOP = true;
					flag_PLAY = false;
				}
			}

			//Если упала на дно спускаем следующюю фигуру
			if (flag_STOP == true) {
				for (i = 1; i <= 4; i++) {
					SquareColorLayer2[SquareOfFigure[1][i]][SquareOfFigure[2][i]] = SquareColorLayer4[CursorX][CursorY];
				}
				if (CursorX == XxX) { CursorX = 1; CursorY++; } else { CursorX++; }
				if (CursorY == (YyY + 1)) { CursorY = 1; }

				if (i_Speed > 30) i_Speed -= 2;
				flag_NEWFIGURE = true;
			}

			//Стираем старую и опускаем на клетку вниз
			if (flag_STOP == false) {
				for (i = 1; i <= 4; i++) {
					document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_0.gif";
					SquareOfFigure[2][i]++;
				}
				//Рисуем новую
				for (i = 1; i <= 4; i++) {
					document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_" + SquareColorLayer4[CursorX][CursorY] + ".gif";
				}
			}
		}
	}
	if (flag_NEWGAME == true) f_newFigure();
	else {
		if (flag_OLDGAME == true) f_oldFigure(i_canvasState);
		else {
			if (flag_DOWN == true) i_SpeedTmp = 30;
			timeout_id = setTimeout("f_Tetris ()", i_SpeedTmp);
			i_SpeedTmp = i_Speed;
		}
	}
}
//-------Двигаем фигуру--------------------------------------------------------------------------------------------
function f_MoveFigure(e) {
	if (flag_GAMEOVER == false && flag_STOP == false) {
		//Проверяем упор влево в стакан
		if (i_V == 0) {
			x_X = this.i_x; y_Y = this.i_y;
		}
		else {
			if (i_V == 1) x_X = (SquareOfFigure[1][1] - 1);
			if (i_V == 2) x_X = (SquareOfFigure[1][1] + 1);
			y_Y = SquareOfFigure[2][1];
			i_V = 0;
		}
		i_Step = x_X - SquareOfFigure[1][1];
		if (x_X == 0 || x_X == 1 || x_X == 2) {
			i_Square = 1;
			for (i = 1; i <= 4; i++) { if (SquareOfFigure[1][i] < SquareOfFigure[1][i_Square]) { i_Square = i; } }
			if ((SquareOfFigure[1][i_Square] + i_Step) == (-1)) { i_Step++; i_Step++; }
			if ((SquareOfFigure[1][i_Square] + i_Step) == 0) { i_Step++; }
		}
		//Проверяем упор вправо в стакан
		if (x_X == (XxX + 1) || x_X == XxX || x_X == (XxX - 1)) {
			i_Square = 1;
			for (i = 1; i <= 4; i++) { if (SquareOfFigure[1][i] > SquareOfFigure[1][i_Square]) { i_Square = i; } }
			if ((SquareOfFigure[1][i_Square] + i_Step) == (XxX + 2)) { i_Step--; i_Step--; }
			if ((SquareOfFigure[1][i_Square] + i_Step) == (XxX + 1)) { i_Step--; }
		}
		//Проверяем упор влево в фигуры
		for (i = 1; i <= 4; i++) { document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_0.gif"; }
		flag_STEP = true;
		if (i_Step < 0) {
			for (i = i_Step; i < 0; i++) {
				for (ii = 1; ii <= 4; ii++) {

					if (SquareColorLayer2[SquareOfFigure[1][ii] - 1][SquareOfFigure[2][ii]] != 0) { flag_STEP = false; }
				}
				if (flag_STEP == true) {
					for (ii = 1; ii <= 4; ii++) {
						SquareOfFigure[1][ii]--;
					}
				}
				else { break; }
			}
		}
		//Проверяем упор вправо в фигуры
		if (i_Step > 0) {
			for (i = i_Step; i > 0; i--) {
				for (ii = 1; ii <= 4; ii++) {

					if (SquareColorLayer2[SquareOfFigure[1][ii] + 1][SquareOfFigure[2][ii]] != 0) { flag_STEP = false; }
				}
				if (flag_STEP == true) {
					for (ii = 1; ii <= 4; ii++) {
						SquareOfFigure[1][ii]++;
					}
				}
				else { break; }
			}
		}
		//Рисуем передвинутую фигуру
		for (i = 1; i <= 4; i++) {
			document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_" + SquareColorLayer4[CursorX][CursorY] + ".gif";
		}
	}
}
//Поворачиваем фигуру
function f_RotateFigure() {
	if (flag_GAMEOVER == false) {
		flag_ROTATE = true;
		for (i = 2; i <= 4; i++) {
			x_X = SquareOfFigure[1][i] - SquareOfFigure[1][1]; y_Y = SquareOfFigure[2][i] - SquareOfFigure[2][1];
			if (SquareColorLayer2[SquareOfFigure[1][1] - y_Y][SquareOfFigure[2][1] + x_X] != 0) { flag_ROTATE = false; }
			if (SquareOfFigure[1][1] - y_Y > XxX || SquareOfFigure[1][1] - y_Y < 1) { flag_ROTATE = false; }
			if (SquareOfFigure[2][1] + x_X > YyY || SquareOfFigure[2][1] + x_X < 1) { flag_ROTATE = false; }

		}
		if (flag_ROTATE == true) {
			for (i = 2; i <= 4; i++) { document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_0.gif"; }
			for (i = 2; i <= 4; i++) {
				x_X = SquareOfFigure[1][i] - SquareOfFigure[1][1];
				y_Y = SquareOfFigure[2][i] - SquareOfFigure[2][1];
				SquareOfFigure[1][i] = SquareOfFigure[1][1] - y_Y;
				SquareOfFigure[2][i] = SquareOfFigure[2][1] + x_X;
				document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_" + SquareColorLayer4[CursorX][CursorY] + ".gif";
			}
		}
	}
}
// Новая игра-----------------------------------------------------------------------------------------
function f_newGame() { flag_NEWGAME = true; }
//Случайным образом генерируются фигуры, записываются в массив №4 и строку №5, обнуляется цифровое и именное массивы №1, 2
function f_newFigure() {
	for (i = 1; i <= XxX; i++) {
		for (ii = 1; ii <= YyY; ii++) {
			SquareColorLayer4[i][ii] = Math.ceil(Math.random() * 7);
			i_canvasKeymap += SquareColorLayer4[i][ii];
			SquareColorLayer2[i][ii] = 0;
			document.images[SquareColorLayer1[i][ii]].src = "img/stone_0.gif";
		}
	}
	CursorY = 1;
	CursorX = 1;
	i_Speed = 1000;
	flag_NEWGAME = false;
	flag_OLDGAME = false;
	flag_PAUSE = false;
	flag_NEWFIGURE = true;
	f_Tetris();
}
//-----------------------------------------------------------------------------------
function f_oldGame() { flag_OLDGAME = true; }
function f_oldFigure() {
	for (i = 1; i <= XxX; i++) {
		for (ii = 1; ii <= YyY; ii++) {
			qq = (i - 1); qq *= YyY; qq += (ii - 1);
			str = i_canvasKeymap.substr(qq, 1);
			SquareColorLayer4[i][ii] = str;
			SquareColorLayer2[i][ii] = Number(0);
			document.images[SquareColorLayer1[i][ii]].src = "img/stone_0.gif";
		}
	}
	CursorY = 1;
	CursorX = 1;
	i_Speed = 1000;
	flag_PLAY = true;
	flag_GAMEOVER = false;
	flag_NEWFIGURE = true;
	flag_PAUSE = false;
	flag_OLDGAME = false;
	flag_NEWGAME = false;
	f_Tetris();
}
