var SquareColorLayer1 = new Array();
var SquareColorLayer2 = new Array();
var SquareColorLayer3 = new Array();
var SquareColorLayer4 = new Array();
var SquareColorLayer5;
var XxX = 30;
var YyY = 20;
var i_points = 0;
var Nsquare = 0;
var CursorY = 1;
var CursorX = 1;
var CursorZ = 1;
var flag_SCORE = false;
var i_countBackToGame = 0;   // счетчик для блесток к возврату в игру

e = document.getElementById('game');

e.addEventListener('touchstart', function (e) {
	e.preventDefault();
});

e.addEventListener('touchmove', function(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const element = document.elementFromPoint(touch.clientX, touch.clientY);

    if (element && element.id && element.id.startsWith('mySquareX')) {
		clearHighlight();
        setHighlight(element);
    }
}, { passive: false });

e.addEventListener('touchend', function(e) {
    e.preventDefault();
    const touch = e.changedTouches[0];
    const element = document.elementFromPoint(touch.clientX, touch.clientY);
    if (element && element.id && element.id.startsWith('mySquareX')) {
        bouncer.call(element, e);
    }
});

//Создаем массив игрового поля
function f_createGame() {
	e.style.width = XxX * 24 + "px";
	for (i = 1; i <= YyY; i++) {
		for (ii = 1; ii <= XxX; ii++) {
			var myElement = document.createElement('img');
			e.appendChild(myElement);
			myElement.id = 'mySquareX' + ii + 'Y' + i;
			myElement.onclick = bouncer;
			myElement.onpointerover = visiblSquare;
			myElement.onpointerout = visiblSquare;
			myElement.i_x = ii;
			myElement.i_y = i;
			myElement.src = "img/stone_0.gif";
		}
		e.appendChild(document.createElement('br'));
	}

	var layoutSpecific = document.getElementById('canvas_layout_specific')
	layoutSpecific.classList.add('windowTitleTip');

	var myElement = document.createElement('img');
	layoutSpecific.appendChild(myElement);
	myElement.src = "img/stone_0.gif";
	myElement.id = 'nextStone';
	myElement.style.width = '16px';
	myElement.style.height = '16px';

	var myElement = document.createElement('i');
	layoutSpecific.appendChild(myElement);
	myElement.innerHTML = _l(" - next stone", myElement);

	for (i = 0; i <= 7; i++)  // Предзагрузка
	{
		var myElement = document.createElement('img');
		e.appendChild(myElement);
		myElement.style.display = 'none';
		myElement.src = "img/stone_" + i + "2.gif";
	}
	StartLayers();
}
//Массив цвета файлов 600 изображений и третий фоновый массив для подсчета количества занятых кубов
function StartLayers() {
	for (i = 1; i <= XxX; i++) {
		SquareColorLayer1[i] = new Array();
		SquareColorLayer2[i] = new Array();
		SquareColorLayer3[i] = new Array();
		SquareColorLayer4[i] = new Array();
		for (ii = 1; ii <= YyY; ii++) {
			SquareColorLayer1[i][ii] = "mySquareX" + i + "Y" + ii;
		}
	}
}

//Сдвигаем квадраты вниз и проверяем на конец хода
function scroll() {

	for (i = 1; i <= XxX; i++) {
		for (ii = (YyY - 2); ii >= 1; ii--) {
			SquareColorLayer2[i][ii + 1] = SquareColorLayer2[i][ii];
			document.images[SquareColorLayer1[i][ii + 1]].src = "img/stone_" + SquareColorLayer2[i][ii + 1] + ".gif";
		}
	}
	ii = 1;
	for (i = 1; i <= XxX; i++) {

		SquareColorLayer2[i][ii] = SquareColorLayer4[i][CursorZ];
		document.images[SquareColorLayer1[i][ii]].src = "img/stone_" + SquareColorLayer2[i][ii] + ".gif";
	}
	if (CursorZ >= YyY) { CursorZ = 1; } else { CursorZ++; }

}
//Стреляем--------------------------------------------------------------------------------------------------------------------------------
function bouncer(evnt) {
	if (flag_PLAY == true) {
		evnt = evnt || window.event;
		//Обнулим подвижные GIF картинки------------------------------------
		for (i = 1; i <= XxX; i++) {
			for (ii = 1; ii <= (YyY - 1); ii++) {
				document.images[SquareColorLayer1[i][ii]].src = "img/stone_" + SquareColorLayer2[i][ii] + ".gif";
			}
		}
		//Стреляем------------------------------------------------------------------
		i_points = 0;
		i_motion++;
		i = this.i_x;
		for (ii = (YyY - 1); ii > 0; ii--) {
			document.images[SquareColorLayer1[i][ii]].src = "img/stone_" + SquareColorLayer4[CursorX][CursorY] + ".gif";
			document.images[SquareColorLayer1[i][ii + 1]].src = "img/stone_0.gif";
			if (SquareColorLayer2[i][ii - 1] != 0) {
				e_definingCoordinate = document.images[SquareColorLayer1[i][ii]];
				Nsquare = 0;
				SquareColorLayer2[i][ii] = SquareColorLayer4[CursorX][CursorY];
				Nsquare = addSquare(i, ii);
				if (Nsquare > 2) {
					i_points += addSquare(i, ii, 3);

					// Расчитываем клетки которые должны отпасть

					CopyLayers();
					for (i = 1; i <= XxX; i++) { if (SquareColorLayer2[i][1] != 0) { SquareColorLayer3[i][1] = 8; } }
					var zeroOK = true;
					while (zeroOK == true) {
						zeroOK = false;
						for (x = 1; x <= XxX; x++) {
							for (y = 1; y <= YyY; y++) {
								if (SquareColorLayer3[x][y] == 8) {
									if (x + 1 <= XxX) { if (SquareColorLayer3[x + 1][y] != 0) { SquareColorLayer3[x + 1][y] = 8; } }
									if (y + 1 <= YyY) { if (SquareColorLayer3[x][y + 1] != 0) { SquareColorLayer3[x][y + 1] = 8; } }
									if (x - 1 > 0) { if (SquareColorLayer3[x - 1][y] != 0) { SquareColorLayer3[x - 1][y] = 8; } }
									if (y - 1 > 0) { if (SquareColorLayer3[x][y - 1] != 0) { SquareColorLayer3[x][y - 1] = 8; } }
									SquareColorLayer3[x][y] = 0;
									zeroOK = true;
								}
							}
						}
					}
					Nsquare = 0;
					for (x = 1; x <= XxX; x++) {
						for (y = 1; y <= YyY; y++) {
							if (SquareColorLayer3[x][y] != 0) {
								SquareColorLayer2[x][y] = 0;
								document.images[SquareColorLayer1[x][y]].src = "img/stone_" + SquareColorLayer3[x][y] + "2.gif";
								Nsquare++;
							}
						}
					}
					for (i = 1; i <= Nsquare; i++) i_points += i;
				}
				else scroll();
				break;
			}
		}

		if (i_points != 0) {
			i_score += i_points;
			e_scoreViewer.innerHTML = i_score;
			f_scrollScore(e_definingCoordinate, i_points);
			f_saveGame();
	    }
		if (CursorX == 30) { CursorX = 1; CursorY++; } else { CursorX++; }
		if (CursorY == 21) CursorY = 1;
		document.images[SquareColorLayer1[this.i_x][YyY]].src = "img/stone_" + SquareColorLayer4[CursorX][CursorY] + ".gif";
		x = CursorX; y = CursorY;
		if (x == 30) { x = 1; y++; } else { x++; }
		if (y == 21) y = 1;
		document.getElementById('nextStone').src = "img/stone_" + SquareColorLayer4[x][y] + ".gif";

		//Проверяем на конец хода

		for (i = 1; i <= XxX; i++) {
			if (SquareColorLayer2[i][YyY - 1] != "0") { f_saveGame(true); f_gameOver(); break; }
		}
	}
}

function visiblSquare(e) {
	if (!flag_PLAY) return;
	e = e || window.event;
	if (e.type == 'pointerover') setHighlight(this);
	if (e.type == 'pointerout') clearHighlight();
}

function setHighlight(elm) {
    const x = elm.i_x;
    document.images[SquareColorLayer1[x][YyY]].src = "img/stone_" + SquareColorLayer4[CursorX][CursorY] + ".gif";
    for (let y = 1; y <= (YyY - 1); y++) {
        document.images[SquareColorLayer1[x][y]].style.backgroundColor = '#ddd';
    }
}

function clearHighlight() {
	for (let x = 1; x <= XxX; x++) {
        document.images[SquareColorLayer1[x][YyY]].src = "img/stone_0.gif";
        for (let y = 1; y <= YyY - 1; y++) {
            document.images[SquareColorLayer1[x][y]].style.backgroundColor = null;
        }
    }
}

// Подсчитываем количество кубиков
function addSquare(x_X, y_Y, f_F) {
	CopyLayers();
	Nsquare = 0;
	Color2 = SquareColorLayer3[x_X][y_Y]; SquareColorLayer3[x_X][y_Y] = 8;
	var zeroOK = true;
	while (zeroOK == true) {
		zeroOK = false;
		for (x = 1; x <= XxX; x++) {
			for (y = 1; y <= YyY; y++) {
				if (SquareColorLayer3[x][y] == 8) {
					if (x + 1 <= XxX) { if (SquareColorLayer3[x + 1][y] == Color2) { SquareColorLayer3[x + 1][y] = 8; } }
					if (y + 1 <= YyY) { if (SquareColorLayer3[x][y + 1] == Color2) { SquareColorLayer3[x][y + 1] = 8; } }
					if (x - 1 > 0) { if (SquareColorLayer3[x - 1][y] == Color2) { SquareColorLayer3[x - 1][y] = 8; } }
					if (y - 1 > 0) { if (SquareColorLayer3[x][y - 1] == Color2) { SquareColorLayer3[x][y - 1] = 8; } }
					SquareColorLayer3[x][y] = 9;
					if (f_F == 1) { document.images[SquareColorLayer1[x][y]].src = "img/stone_" + Color2 + "1.gif"; }
					if (f_F == 2) { document.images[SquareColorLayer1[x][y]].src = "img/stone_" + Color2 + ".gif"; }
					if (f_F == 3) {
						document.images[SquareColorLayer1[x][y]].src = "img/stone_" + SquareColorLayer2[x][y] + "2.gif";;
						SquareColorLayer2[x][y] = 0;
					}
					zeroOK = true;
					Nsquare++;
				}
			}
		}
	}
	return (Nsquare);
}

// Рандомное закрашивание камней
function f_gameOver() {
	if (flag_GAMEOVER) {
		i_countBackToGame = 50;
		for (i = 1; i <= 5; i++) {
			i_SquareX = Math.ceil(Math.random() * XxX);
			i_SquareY = Math.ceil(Math.random() * YyY);
			i_color = Math.ceil(Math.random() * 6);
			document.images[SquareColorLayer1[i_SquareX][i_SquareY]].src = 'img/stone_' + i_color + '.gif';
		}
		setTimeout("f_gameOver ()", 20);
	}
	else if (i_countBackToGame > 1) {
		i_countBackToGame -= 1;
		for (i = 1; i <= 100; i++) {
			i_SquareX = Math.ceil(Math.random() * XxX);
			i_SquareY = Math.ceil(Math.random() * YyY);
			i_color = Math.ceil(Math.random() * 6);
			document.images[SquareColorLayer1[i_SquareX][i_SquareY]].src = 'img/stone_0.gif';
		}
		setTimeout("f_gameOver ()", 10);
	}
	else {
		flag_PLAY=true;
		CursorY = 1;
		CursorX = 1;
		CursorZ = 1;
		document.getElementById('nextStone').src = "img/stone_" + SquareColorLayer4[2][1] + ".gif";
		for (x = 1; x <= XxX; x++) {
			document.images[SquareColorLayer1[x][YyY]].src = 'img/stone_0.gif';
		}
		clearHighlight();
		if (i_countBackToGame == 0) {
			scroll(); scroll(); scroll();
		}
		else i_countBackToGame = 0
	}
}
// Копируем слой 2 в слой 3
function CopyLayers() {
	for (CopyLayersX = 1; CopyLayersX <= XxX; CopyLayersX++) {
		for (CopyLayersY = 1; CopyLayersY <= YyY; CopyLayersY++) {
			SquareColorLayer3[CopyLayersX][CopyLayersY] = SquareColorLayer2[CopyLayersX][CopyLayersY];
		}
	}
}

// Заполняем случайным образом цвета кубов
function f_newGame() {
	for (i = 1; i <= XxX; i++) {
		for (ii = 1; ii <= YyY; ii++) {
			SquareColorLayer4[i][ii] = Math.ceil(Math.random() * 6);
			while (SquareColorLayer4[i][ii] == 5 || SquareColorLayer4[i][ii] == 3) {
				SquareColorLayer4[i][ii] = Math.ceil(Math.random() * 6);
			}
			i_canvasKeymap += SquareColorLayer4[i][ii];
			SquareColorLayer2[i][ii] = 0;
		}
	}
    flag_PLAY = false;
	f_gameOver()
}

function f_oldGame() {
	for (i = 1; i <= YyY; i++) {
		for (ii = 1; ii <= XxX; ii++) {
			qq = (ii - 1); qq *= YyY; qq += (i - 1);
			str = i_canvasKeymap.substr(qq, 1);
			SquareColorLayer4[ii][i] = str;
		}
	}
	for (i = 1; i <= XxX; i++) {
		for (ii = 1; ii <= YyY; ii++) {
			SquareColorLayer2[i][ii] = Number(0);
		}
	}
	flag_PLAY = false;
	f_gameOver()
}
