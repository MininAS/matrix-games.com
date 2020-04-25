var SquareColorLayer1 = new Array ();	<!--Именной  массив игрового поля-->
var SquareColorLayer2 = new Array ();	<!--Цифровой массив игрового поля-->
var SquareColorLayer3 = new Array ();	<!--Цифровой массив игрового поля для подсчета баллов-->
var SquareColorLayer4 = new Array ();	<!--Массив одномерный данных-->
var s_mess;		<!--Строка для сохранения на сервер игры и итоговых баллов-->
var SquareOfFigure = new Array ();	<!--9-мь переменных координат четырех кубиков фигуры-->
SquareOfFigure[1] = new Array ();		<!--3-ре по X-->
SquareOfFigure[2] = new Array ();		<!--3-ре по Y-->
SquareOfFigure[3] = new Array ();		<!--7-ре по Color-->

var XxX = 10;				<!--Ширина игрового поля-->
var YyY = 20;				<!--Высота игрового поля-->
var i_motion = 0;				<!--Количество ходов-->
var i_score = 0;			<!--Количество баллов-->
var CursorX = 1;			<!--Смещение курсора в массиве №4-->
var flag_DOWN = false;		<!--Флаг для падения фигуры-->
var flag_STOP = false;		<!--Флаг для разрешения спуска-->
var flag_LINE = false;		<!--Флаг для распознования целой линий-->
var flag_ROTATE = true;		<!--Флаг для запрета поворота-->
var flag_GAMEOVER = false;	<!--Флаг для запрета завершения игры-->
var flag_NEWGAME = false;	<!--Флаг для сброса игры-->
var flag_OLDGAME = false;	<!--Флаг для старой игры-->
var flag_NEWFIGURE = true;
var flag_PLAY = true;
var i_Speed = 1000;			<!--Скорость спуска фигуры-->
var i_SpeedTmp = 1000;
var i_NLine = 0;			<!--Кол линий удаляемых за один раз-->
var i_V = 0;				<!--Направление движения -->
var i_canvasState = 0;

	<!--Создаем массив игрового поля-->
function f_greateGame ()
{
	document.getElementById('game').style.width = XxX*24+'px';
	for (i=1; i<=YyY; i++)
	{
		for (ii=1; ii<=XxX; ii++)
		{
			var myElement = document.createElement ('img');
			document.getElementById('game').appendChild(myElement);
			myElement.id = 'mySquareX' + ii + 'Y' + i;
			myElement.onclick = f_RotateFigure;
			myElement.onmouseover = f_MoveFigure;
			myElement.i_x = ii;
			myElement.i_y = i;
			myElement.src = "img/stone_0.gif";
		}
	}
			var myElement = document.createElement ('div');
	document.getElementById('game').appendChild(myElement);
	myElement.id = 'nextFigure';
	myElement.style.position = 'absolute';
	myElement.style.top = 100+'px';
	myElement.style.right = '4%';
	myElement.style.width = '200px';
	myElement.className = 'windowSite';

	var myElement = document.createElement ('p');
	document.getElementById('nextFigure').appendChild(myElement);
	myElement.className = 'big';
	myElement.innerHTML = 'Следующая фигура';

	var myElement_ = document.createElement ('div');
	document.getElementById('nextFigure').appendChild(myElement_);
		var myElement = document.createElement ('br');
		myElement_.appendChild(myElement);
	for (i=1; i<=3; i++)
	{
		var myElement = document.createElement ('img');
		myElement_.appendChild(myElement);
		myElement.name = 'mySquareZ' + i;
		myElement.src = "img/stone_0.gif";
	}
	document.getElementById('k_pauseGame').style.display = 'inline-block';
	StartLayers ();
}
			<!--Перенаправляем правую кнопку мыши и вкл.клавиши-->
document.oncontextmenu = new Function ("return false;");
document.onkeydown = f_KeyPress;
document.onmousedown = function f_Click (event)
{
	if (flag_PLAY == true)
	{
		e = (event) ? event : window.event;
		if (e.button == 2 || e.button == 4) {clearTimeout(timeout_id); flag_DOWN = true; f_Tetris (); e.stopPropagation(); e.preventDefault();  return false;}
	}
}
document.onmouseup = function f_Click (event)
{
	if (flag_PLAY == true)
	{
		e = (event) ? event : window.event;
		if (e.button == 2) {flag_DOWN = false; e.stopPropagation(); e.preventDefault();  return false;}
	}
}
function f_KeyPress (event)
{
	if (flag_PLAY == true)
	{
		event = (event) ? event : window.event;
		evt = (event.keyCode) ? event.keyCode : event.which;
		if (evt == 87 || evt == 38) {f_RotateFigure (); return false;}
		if (evt == 83 || evt == 40) {clearTimeout(timeout_id); flag_DOWN = true; f_Tetris (); event.stopPropagation();event.preventDefault();  return false;}
		if (evt == 65 || evt == 37) {i_V = 1; f_MoveFigure (); return false;}
		if (evt == 68 || evt == 39) {i_V = 2; f_MoveFigure (); return false;}
		if (evt == 32) {f_windowInfoPopup('pause'); return false;}
	}
}
document.onkeyup = function f_KeyUp (event)
{
	if (flag_PLAY == true)
	{
		event = (event) ? event : window.event;
		evt = (event.keyCode) ? event.keyCode : event.which;
		if (evt == 1099 || evt == 40) {flag_DOWN = false; event.stopPropagation();event.preventDefault();  return false;}
	}
}
		<!--Определяем массивы-->
function StartLayers ()
{
	for (i=1; i<=XxX; i++)
	{
		SquareColorLayer1[i] = new Array ();
		SquareColorLayer2[i] = new Array ();
		SquareColorLayer3[i] = new Array ();
		for (ii=1; ii<=YyY; ii++)
		{
			SquareColorLayer1[i][ii] = "mySquareX"+i+"Y"+ii;
		}
	}
	setTimeout ("f_Tetris ()", 1000);
}
					<!--Определяем цвета квадратов следующей фигуры-->
function f_Tetris ()
{
	if (flag_PAUSE == false && flag_OLDGAME == false && flag_NEWGAME == false && flag_GAMEOVER == false)
	{
		if (flag_NEWFIGURE == true)
		{
			flag_DOWN = false;
			SquareOfFigure[1][1] = (XxX/2 - 1); 		SquareOfFigure[2][1] = 1; 	SquareOfFigure[3][1] = SquareColorLayer4[CursorX];
			SquareOfFigure[1][2] = XxX/2; 	SquareOfFigure[2][2] = 1; 	SquareOfFigure[3][2] = SquareColorLayer4[CursorX + 1];
			SquareOfFigure[1][3] = (XxX/2 + 1); 	SquareOfFigure[2][3] = 1; 	SquareOfFigure[3][3] = SquareColorLayer4[CursorX + 2];

						<!--Проверяем на удаления линий-->
			i_motion++;
			i_NLine = 0;
			i_Nballov = 0;
			flag_LINE = false;
						<!--Проверка на горизонтальные линии-->
			for (i = 1; i <= YyY; i++)
			{
				for (ii = 1; ii <= (XxX-2); ii++)
				{
					i_N = 1;
					while ((ii + i_N) <= XxX && SquareColorLayer2[ii][i] != 0)
					{
						if (SquareColorLayer2[ii][i] == SquareColorLayer2[ii + i_N][i]) 	i_N ++;
						else {break;}
					}
					if (i_N >= 3)
					{
						for (iii = 1; iii <= i_N; iii++)
						{
							SquareColorLayer3[ii + iii - 1][i] = 9;
							i_Nballov +=  iii;
						}
						i_NLine ++;
						flag_LINE = true;
					}
				}
			}
						<!--Проверка на вертикальные линии-->
			for (i = 1; i <= (YyY - 2); i++)
			{
				for (ii = 1; ii <= XxX; ii++)
				{
					i_N = 1;
					while ((i + i_N) <= YyY && SquareColorLayer2[ii][i] != 0)
					{
						if (SquareColorLayer2[ii][i] == SquareColorLayer2[ii][i + i_N]) 	i_N ++;
						else {break;}
					}
					if (i_N >= 3)
					{
						for (iii = 1; iii <= i_N; iii++)
						{
							SquareColorLayer3[ii][i + iii - 1] = 9;
							i_Nballov +=  iii;
						}
						i_NLine ++;
						flag_LINE = true;
					}
				}
			}
						<!--Проверка на диагональ линии-->
			for (i = 1; i <= (YyY - 2); i++)
			{
				for (ii = 1; ii <= (XxX-2); ii++)
				{
					i_N = 1;
					while ((ii + i_N) <= XxX && (i + i_N) <= YyY && SquareColorLayer2[ii][i] != 0)
					{
						if (SquareColorLayer2[ii][i] == SquareColorLayer2[ii + i_N][i + i_N]) 	i_N ++;
						else {break;}
					}
					if (i_N >= 3)
					{
						for (iii = 1; iii <= i_N; iii++)
						{
							SquareColorLayer3[ii + iii - 1][i + iii - 1] = 9;
							i_Nballov +=  iii;
						}
						i_NLine ++;
						flag_LINE = true;
					}
				}
			}
						<!--Проверка на диагональе линии-->
			for (i = 1; i <= (YyY - 2); i++)
			{
				for (ii = 3; ii <= XxX; ii++)
				{
					i_N = 1;
					while ((ii - i_N) >= 1 && (i + i_N) <= YyY && SquareColorLayer2[ii][i] != 0)
					{
						if (SquareColorLayer2[ii][i] == SquareColorLayer2[ii - i_N][i + i_N]) 	i_N ++;
						else {break;}
					}
					if (i_N >= 3)
					{
						for (iii = 1; iii <= i_N; iii++)
						{
							SquareColorLayer3[ii - iii + 1][i + iii - 1] = 9;
							i_Nballov +=  iii;
						}
						i_NLine ++;
						flag_LINE = true;
					}
				}
			}
						<!--Удаляем линии и раняем квадраты-->
			for (i = 1; i <= YyY; i++)
			{
				for (ii = 1; ii <= XxX; ii++)
				{
					if (SquareColorLayer3[ii][i] == 9)
					{
						SquareColorLayer2[ii][i] = 0;
						SquareColorLayer3[ii][i] = 0;
						document.images[SquareColorLayer1[ii][i]].src = "img/stone_0.gif";
					}
				}
			}
			for (i = 2; i <= (YyY - 1); i++)
			{
				for (ii = 1; ii <= XxX; ii++)
				{
					if (SquareColorLayer2[ii][i] != 0 && SquareColorLayer2[ii][i+1] == 0)
					{
						for  (iii = i; iii >= 2; iii--)
						{
							SquareColorLayer2[ii][iii + 1] = SquareColorLayer2[ii][iii];
							document.images[SquareColorLayer1[ii][iii + 1]].src = "img/stone_"+SquareColorLayer2[ii][iii]+".gif";
						}
					}
				}
			}
			if  (i_NLine == 2) i_Nballov += 10;
			if  (i_NLine == 3) i_Nballov += 25;
			if  (i_NLine == 4) i_Nballov += 50;
			if  (i_NLine >= 5) i_Nballov += 120;
			i_score += i_Nballov;
			if (window.document.getElementById('myNballov'))
			{
				document.getElementById('myNballov').innerHTML = i_score;
			}

					<!--Проверка на конец хода-->
			for (i = 1; i <= 3; i++) {document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_"+SquareOfFigure[3][i]+".gif";}
			for (i = 1; i <= XxX; i++)
			{
				if (SquareColorLayer2[i][1] != 0)
				{
					f_endGame (); break;
				}
			}
					<!--Рисуем подсказку-->
			if (flag_GAMEOVER == false)
			{
				for (i = 1; i <= 3; i++)
				{
					string = "mySquareZ" + i;
					if ((CursorX + 2 + i) <= 600) {document.images[string].src = "img/stone_" + SquareColorLayer4[CursorX + 2 + i] + ".gif";}
					else {document.images[string].src = "img/stone_" + SquareColorLayer4[CursorX + 2 + i - 600] + ".gif";}
				}
			}
			flag_NEWFIGURE = false;
			flag_PLAY = true;
		}
		<!-------------------------------Сдвигаем фигуру вниз и проверяем на конец хода-------------------------------------------------------------------->
		else
		{
						<!--Проверяем на предкновение-->
			flag_STOP = false;
			for (i = 1; i <= 3; i++)
			{
				if (SquareOfFigure[2][i] == YyY || SquareColorLayer2[SquareOfFigure[1][i]][SquareOfFigure[2][i]+1] != 0)
				{
					flag_STOP = true;
					flag_PLAY = false;
				}
			}

			if (flag_STOP == true)				<!--Если упала на дно спускаем следующюю фигуру-->
			{
				for (i = 1; i <= 3; i++)
				{
					SquareColorLayer2[SquareOfFigure[1][i]][SquareOfFigure[2][i]] = SquareColorLayer4[CursorX + i - 1];
				}
				CursorX += 3;
				if (CursorX >= 600) {CursorX = 1;}
				if (i_Speed > 30) i_Speed -= 2;
			flag_NEWFIGURE = true;
			}
			if (flag_STOP == false)					<!--Стираем старую и опускаем на клетку вниз-->
			{
				for (i = 1; i <= 3; i++)
				{
					document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_0.gif";
					SquareOfFigure[2][i]++;
				}
							<!--Рисуем новую-->
				for (i = 1;i <= 3; i++)
				{
					document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_"+SquareColorLayer4[CursorX + i - 1]+".gif";
				}
			}
		}
	}
	if (flag_NEWGAME == true) f_newFigure ();
	else
	{
		if (flag_OLDGAME == true) f_oldFigure (i_canvasState);
		else
		{
			if (flag_DOWN == true) i_SpeedTmp = 30;
			timeout_id = setTimeout ("f_Tetris ()", i_SpeedTmp);
			i_SpeedTmp = i_Speed;
		}
	}
}
			<!------------------------------------Двигаем фигуру---------------------------------------------------------------------------------------------->
function f_MoveFigure (e)
{
	if (flag_GAMEOVER == false && flag_STOP == false)
	{
						<!--Проверяем упор влево в стакан-->
		if (i_V == 0)
		{
			x_X = this.i_x;	y_Y = this.i_y;
		}
		else
		{
			if (i_V == 1) x_X = (SquareOfFigure[1][1] - 1);
			if (i_V == 2) x_X = (SquareOfFigure[1][1] + 1);
			y_Y = SquareOfFigure[2][1];
			i_V =0;
		}
		i_Step = x_X - SquareOfFigure[1][1];
		if (x_X == 0 || x_X == 1)
		{
			i_Square = 1;
			for (i = 1; i <= 3; i++) {if (SquareOfFigure[1][i] < SquareOfFigure[1][i_Square]){i_Square = i;}}
			if ((SquareOfFigure[1][i_Square] + i_Step) == (-1)) {i_Step++; i_Step++;}
			if ((SquareOfFigure[1][i_Square] + i_Step) == 0) {i_Step++;}
		}
						<!--Проверяем упор вправо в стакан-->
		if (x_X == (XxX+1) || x_X == XxX || x_X == (XxX-1))
		{
			i_Square = 1;
			for (i = 1; i <= 3; i++) {if (SquareOfFigure[1][i] > SquareOfFigure[1][i_Square]){i_Square = i;}}
			if ((SquareOfFigure[1][i_Square] + i_Step) == (XxX+2)) {i_Step--; i_Step--;}
			if ((SquareOfFigure[1][i_Square] + i_Step) == (XxX+1)) {i_Step--;}
		}
						<!--Проверяем упор влево в фигуры-->
		for (i = 1; i <= 3; i++) {document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_0.gif";}
		flag_STEP = true;
		if (i_Step < 0)
		{
			for  (i = i_Step; i < 0; i++)
			{
				for (ii = 1; ii <= 3; ii++)
				{

					if (SquareColorLayer2[SquareOfFigure[1][ii] - 1][SquareOfFigure[2][ii]] != 0){flag_STEP = false;}
				}
				if (flag_STEP == true)
				{
					for (ii = 1; ii <= 3; ii++)
					{
						SquareOfFigure[1][ii]--;
					}
				}
				else {break;}
			}
		}
						<!--Проверяем упор вправо в фигуры-->
		if (i_Step > 0)
		{
			for  (i = i_Step; i > 0; i--)
			{
				for (ii = 1; ii <= 3; ii++)
				{
					if (SquareColorLayer2[SquareOfFigure[1][ii] + 1][SquareOfFigure[2][ii]] != 0){flag_STEP = false;}
				}
				if (flag_STEP == true)
				{
					for (ii = 1; ii <= 3; ii++)
					{
						SquareOfFigure[1][ii]++;
					}
				}
				else {break;}
			}
		}
						<!--Рисуем передвинутую фигуру-->
		for (i = 1; i <= 3; i++)
		{
			document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_"+SquareColorLayer4[CursorX + i - 1]+".gif";
		}
	}
}
						<!--Поворачиваем фигуру-->
function f_RotateFigure ()
{
	if (flag_GAMEOVER == false)
	{
	flag_ROTATE = true;
	x_X = SquareOfFigure[1][2]; y_Y = SquareOfFigure[2][2];
	if (SquareColorLayer2[x_X - 1][y_Y] != 0) {flag_ROTATE = false;}
	if (SquareColorLayer2[x_X + 1][y_Y] != 0) {flag_ROTATE = false;}
	if (SquareColorLayer2[x_X][y_Y - 1] != 0) {flag_ROTATE = false;}
	if (SquareColorLayer2[x_X][y_Y + 1] != 0) {flag_ROTATE = false;}
	if (flag_ROTATE == true)
	{
		document.images[SquareColorLayer1[SquareOfFigure[1][1]][SquareOfFigure[2][1]]].src = "img/stone_0.gif";
		document.images[SquareColorLayer1[SquareOfFigure[1][3]][SquareOfFigure[2][3]]].src = "img/stone_0.gif";
		for (i = 1; i <= 3; i += 2)
		{
			x_X = SquareOfFigure[1][i] - SquareOfFigure[1][2];
			y_Y = SquareOfFigure[2][i] - SquareOfFigure[2][2];
			SquareOfFigure[1][i] = SquareOfFigure[1][2] - y_Y;
			SquareOfFigure[2][i] = SquareOfFigure[2][2] + x_X;
			document.images[SquareColorLayer1[SquareOfFigure[1][i]][SquareOfFigure[2][i]]].src = "img/stone_"+SquareColorLayer4[CursorX + i - 1]+".gif";
		}
	}
	}
}

function f_newGame () {flag_NEWGAME = true;}
		<!--Случайным образом генерируются фигуры, записываются в массив №4 и строку №5, обнуляется цифровое и именное массивы №1, 2 -->
function f_newFigure ()
{
	for (i=1; i<=XxX; i++){
		for (ii=1; ii<=YyY; ii++){
			SquareColorLayer2[i][ii] = 0;
			SquareColorLayer3[i][ii] = 0;
			document.images[SquareColorLayer1[i][ii]].src = "img/stone_0.gif";
		}
	}
	for (i=1; i<=600; i++){
		SquareColorLayer4[i] = Math.ceil (Math.random ()*7);
		i_canvasKeymap +=  SquareColorLayer4[i];
	}
	CursorX = 1;
	i_Speed = 1000;
	flag_NEWGAME = false;
	flag_OLDGAME = false;
	flag_PAUSE = false;
	flag_NEWFIGURE = true;
	f_Tetris ();
}

function f_oldGame () {flag_OLDGAME = true;}
function f_oldFigure ()
{
	for (i=1; i<=XxX; i++)
	{
		for (ii=1; ii<=YyY; ii++)
		{
			qq = (i-1); qq *= YyY; qq += (ii-1);
			str = i_canvasKeymap.substr (qq, 1);
			SquareColorLayer4[qq] = str;
			SquareColorLayer2[i][ii] = 0;
			SquareColorLayer3[i][ii] = 0;
			document.images[SquareColorLayer1[i][ii]].src = "img/stone_0.gif";
		}
	}
	CursorY = 1;
	CursorX = 1;
	i_Speed = 1000;
	flag_NEWFIGURE = true;
	flag_NEWGAME = false;
	flag_OLDGAME = false;
	flag_PAUSE = false;
	f_Tetris ();
}
