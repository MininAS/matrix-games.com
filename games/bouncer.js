var SquareColorLayer1 = new Array ();
var SquareColorLayer2 = new Array ();
var SquareColorLayer3 = new Array ();
var SquareColorLayer4 = new Array ();
var SquareColorLayer5;
var XxX = 30;
var YyY = 20;
var Nxodov = 0;
var Nballov = 0;
var i_Nballov = 0;
var Nsquare = 0;
var CursorY = 1;
var CursorX = 1;
var CursorZ = 1;
var flag_PLAY = true;
var flag_SCORE = false;

var i_Scroll = 0;
var i_StartScrollX = 0;
var i_StartScrollY = 0;

	var o_ScrollScore = document.createElement ('p');
	document.getElementById('box_center').appendChild(o_ScrollScore);
	o_ScrollScore.id = 'myN_ballov';
	o_ScrollScore.className = 'border_inset';
	o_ScrollScore.style.position = 'absolute';

	<!--Создаем массив игрового поля-->
function f_greateGame ()
{
	document.getElementById('game').style.width = XxX * 24+"px";
	for (i=1; i<=YyY; i++)
	{
		for (ii=1; ii<=XxX; ii++)
		{
			var myElement = document.createElement ('img');
			document.getElementById('game').appendChild(myElement);
			myElement.id = 'mySquareX' + ii + 'Y' + i;
			myElement.onclick = bouncer;
			myElement.onmouseover = visiblSquare;
			myElement.onmouseout = visiblSquare;
			myElement.i_x = ii;
			myElement.i_y = i;
			myElement.src = "img/stone_0.gif";
		}
		document.getElementById('game').appendChild(document.createElement ('br'));
	}
	var myElement = document.createElement ('div');
	document.getElementById('game_block').appendChild(myElement);
	myElement.id = 'nextFigure';
	myElement.classList.add('windowSite');
	myElement.style.position = 'absolute';
	myElement.style.top = 20+'px';
	myElement.style.right = '1%';
	myElement.style.width = '24px';

	var myElement = document.createElement ('p');
	document.getElementById('nextFigure').appendChild(myElement);
	myElement.innerHTML = 'С л е д у ю щ а я<br><br>ф и г у р а<br><br>';

	var myElement = document.createElement ('img');
	document.getElementById('nextFigure').appendChild(myElement);
	myElement.src = "img/stone_0.gif";
	myElement.id = 'nextStone';

	for (i=0; i<=7; i++)  // Предзагрузка
	{
		var myElement = document.createElement ('img');
		document.getElementById('game').appendChild(myElement);
		myElement.style.display = 'none';
		myElement.src = "img/stone_"+i+"2.gif";
	}
	StartLayers ();
	f_ScrollScore ();
}
	<!--Массив цвета файлов 600 изображений и третий фоновый массив для подсчета количества занятых кубов-->
function StartLayers()
{
	for (i=1; i<=XxX; i++)
	{
		SquareColorLayer1[i] = new Array ();
		SquareColorLayer2[i] = new Array ();
		SquareColorLayer3[i] = new Array ();
		SquareColorLayer4[i] = new Array ();
		for (ii=1; ii<=YyY; ii++)
		{
			SquareColorLayer1[i][ii] = "mySquareX"+i+"Y"+ii;
		}
	}
}

						<!--Сдвигаем квадраты вниз и проверяем на конец хода-->
function scroll ()
{

	for (i=1; i<=XxX; i++)
	{
		for (ii=(YyY - 2); ii>=1; ii--)
		{
			SquareColorLayer2[i][ii+1] = SquareColorLayer2[i][ii];
			document.images[SquareColorLayer1[i][ii+1]].src = "img/stone_"+SquareColorLayer2[i][ii+1]+".gif";
		}
	}
	ii=1;
	for (i=1; i<=XxX; i++)
	{

			SquareColorLayer2[i][ii] = SquareColorLayer4[i][CursorZ];
			document.images[SquareColorLayer1[i][ii]].src = "img/stone_"+SquareColorLayer2[i][ii]+".gif";
	}
	if (CursorZ >= YyY) {CursorZ = 1;} else {CursorZ++;}

}
	<!--Стреляем---------------------------------------------------------------------------------------------------------------------------------->
function bouncer (evnt)
{
	if (flag_PLAY == true)
	{
	evnt = evnt || window.event;
	<!--Обнулим подвижные GIF картинки-------------------------------------->
		for (i=1; i<=XxX; i++)
		{
			for (ii=1; ii<=(YyY -1); ii++)
			{
				document.images[SquareColorLayer1[i][ii]].src = "img/stone_"+SquareColorLayer2[i][ii]+".gif";
			}
		}
	<!--Стреляем-------------------------------------------------------------------->
	i_Nballov = 0;
	Nxodov ++;
	i = this.i_x;
	for (ii=(YyY-1); ii>0; ii--)
	{
		document.images[SquareColorLayer1[i][ii]].src = "img/stone_"+SquareColorLayer4[CursorX][CursorY]+".gif";
		document.images[SquareColorLayer1[i][ii+1]].src = "img/stone_0.gif";
		if (SquareColorLayer2[i][ii-1] != 0)
		{

			Nsquare = 0;
			SquareColorLayer2[i][ii] = SquareColorLayer4[CursorX][CursorY];
			Nsquare = addSquare (i, ii);
			if (Nsquare > 2)
			{
				i_Nballov += addSquare (i, ii, 3);

<!-- Расчитываем клетки которые должны отпасть -->

				CopyLayers ();
				for (i=1; i<=XxX; i++) {if (SquareColorLayer2[i][1] != 0) {SquareColorLayer3[i][1] = 8;}}
				var zeroOK = true;
				while (zeroOK == true)
				{
					zeroOK= false;
					for (x=1; x<=XxX; x++)
					{
						for (y=1; y<=YyY; y++)
						{
							if (SquareColorLayer3[x][y] == 8)
							{
								if (x+1 <= XxX) 	{if (SquareColorLayer3[x+1][y] != 0) {SquareColorLayer3[x+1][y] = 8;}}
								if (y+1 <= YyY)	{if (SquareColorLayer3[x][y+1] != 0) {SquareColorLayer3[x][y+1] = 8;}}
								if (x-1 > 0)		{if (SquareColorLayer3[x-1][y] != 0)  {SquareColorLayer3[x-1][y] = 8;}}
								if (y-1 > 0)		{if (SquareColorLayer3[x][y-1] != 0)  {SquareColorLayer3[x][y-1] = 8;}}
								SquareColorLayer3[x][y] = 0;
								zeroOK = true;
							}
						}
					}
				}
				Nsquare = 0;
				for (x=1; x<=XxX; x++)
				{
					for (y=1; y<=YyY; y++)
					{
						if (SquareColorLayer3[x][y] != 0)
						{
							SquareColorLayer2[x][y] = 0;
							document.images[SquareColorLayer1[x][y]].src = "img/stone_"+SquareColorLayer3[x][y]+"2.gif";
							Nsquare ++;
						}
					}
				}
				for (i=1; i<=Nsquare; i++) i_Nballov += i;
			}
			 else {scroll ();}
		break;
		}
	}

		if (i_Nballov != 0)
		{
			i_Scroll = 0;
			i_StartScrollX = (evnt.pageX || evnt.clientX);
			i_StartScrollY = (evnt.pageY || evnt.clientY);
			flag_SCORE = true;
			Nballov += i_Nballov;
		}

		if (CursorX == 30) {CursorX = 1; CursorY++;} else {CursorX++;}
		if (CursorY == 21) CursorY = 1;
		document.getElementById ('myNballov').innerHTML = Nballov;
		document.images[SquareColorLayer1[this.i_x][YyY]].src = "img/stone_"+SquareColorLayer4[CursorX][CursorY]+".gif";
		x=CursorX; y=CursorY;
		if (x == 30) {x = 1; y++;} else {x++;}
		if (y == 21) y = 1;
		document.getElementById ('nextStone').src = "img/stone_"+SquareColorLayer4[x][y]+".gif";

						<!--Проверяем на конец хода-->

		for (i=1; i<=XxX; i++)
		{
			if (SquareColorLayer2[i][YyY-1] != "0") {f_endGame (); break;}
		}
	}
}

function visiblSquare(e)
{
	if (flag_PLAY == true)
	{
		evnt = e || window.event;
		if (evnt.type == 'mouseover')
		{
			document.images[SquareColorLayer1[this.i_x][YyY]].src = "img/stone_"+SquareColorLayer4[CursorX][CursorY]+".gif";
			for (i = 1; i <= (YyY-1); i ++)
			{
				document.images[SquareColorLayer1[this.i_x][i]].style.backgroundColor = '#ddd';
			}
		}
		if (evnt.type == 'mouseout')
		{
			document.images[SquareColorLayer1[this.i_x][YyY]].src = "img/stone_0.gif";
			for (i = 1; i <= (YyY-1); i ++)
			{
				document.images[SquareColorLayer1[this.i_x][i]].style.backgroundColor = null;
			}
		}
	}
}

	<!--Подсчитываем количество кубиков-->
function addSquare (x_X,y_Y, f_F)
{
	CopyLayers ();
	Nsquare=0;
	Color2 = SquareColorLayer3[x_X][y_Y]; SquareColorLayer3[x_X][y_Y] = 8;
	var zeroOK = true;
	while (zeroOK == true)
	{
		zeroOK= false;
		for (x=1; x<=XxX; x++)
		{
			for (y=1; y<=YyY; y++)
			{
				if (SquareColorLayer3[x][y] == 8)
				{
					if (x+1 <= XxX) 	{if (SquareColorLayer3[x+1][y] == Color2) {SquareColorLayer3[x+1][y] = 8;}}
					if (y+1 <= YyY)	{if (SquareColorLayer3[x][y+1] == Color2) {SquareColorLayer3[x][y+1] = 8;}}
					if (x-1 > 0)		{if (SquareColorLayer3[x-1][y] == Color2)  {SquareColorLayer3[x-1][y] = 8;}}
					if (y-1 > 0)		{if (SquareColorLayer3[x][y-1] == Color2)  {SquareColorLayer3[x][y-1] = 8;}}
					SquareColorLayer3[x][y] = 9;
					if (f_F == 1) {document.images[SquareColorLayer1[x][y]].src = "img/stone_"+Color2+"1.gif";}
					if (f_F == 2) {document.images[SquareColorLayer1[x][y]].src = "img/stone_"+Color2+".gif";}
					if (f_F == 3) {document.images[SquareColorLayer1[x][y]].src = "img/stone_"+SquareColorLayer2[x][y]+"2.gif";;
							SquareColorLayer2[x][y] = 0;}
					zeroOK = true;
					Nsquare++;
				}
			}
		}
	}
	return (Nsquare);
}
	<!--Сколл очков в верх-->
function f_ScrollScore ()
{
	if (flag_SCORE == true)
	{
		o_ScrollScore.style.display = 'block';
		o_ScrollScore.style.top = i_StartScrollY - i_Scroll+"px";
		o_ScrollScore.style.left = i_StartScrollX+"px";
		o_ScrollScore.style.opacity =  1 - (i_Scroll / 100);
		o_ScrollScore.style.filter = 'Alpha(opacity=' + (100 - i_Scroll) + ')';
		o_ScrollScore.innerHTML = i_Nballov;
		if (i_Scroll <= 100) { i_Scroll++;}
		else {i_Scroll = 0; o_ScrollScore.style.display = 'none'; flag_SCORE = false;}
	}
	if (flag_PLAY == false)
	{
		for (i = 1; i <= 5; i++)
		{
			i_SquareX = Math.ceil (Math.random ()*XxX);
			i_SquareY = Math.ceil (Math.random ()*YyY);
			document.images[SquareColorLayer1[i_SquareX][i_SquareY]].src = 'img/stone_7.gif';
		}
	}
	setTimeout ("f_ScrollScore ()", 30);
}
	<!--Копируем слой 2 в слой 3-->
function CopyLayers ()
{
	for (CopyLayersX=1; CopyLayersX<=XxX; CopyLayersX++)
	{
		for (CopyLayersY=1; CopyLayersY<=YyY; CopyLayersY++)
		{
			SquareColorLayer3[CopyLayersX][CopyLayersY] = SquareColorLayer2[CopyLayersX][CopyLayersY];
		}
	}
}
	<!--Заполняем случайным образом цвета кубов-->
function f_newGame ()
{
	flag_PLAY = true;
	s_mess = "";
	for (i=1; i<=XxX; i++)
	{
		for (ii=1; ii<=YyY; ii++)
		{
			SquareColorLayer4[i][ii] = Math.ceil (Math.random ()*6);
			while (SquareColorLayer4[i][ii] == 5 || SquareColorLayer4[i][ii] == 3) {SquareColorLayer4[i][ii] = Math.ceil (Math.random ()*6);}
			s_mess +=  SquareColorLayer4[i][ii];
			SquareColorLayer2[i][ii] = 0;
		}
	}
	document.getElementById('canvasState').value = '0';
	document.getElementById('game_sport').style.display = 'none';
	Nballov = 0;
	Nxodov = 0;
	CursorY = 1;
	CursorX = 1;
	CursorZ = 1;
	scroll (); scroll (); scroll ();
}
function f_oldGame(i_game)
{
	var req = getXmlHttp();
	req.onreadystatechange = function()
		{
		 	if (req.readyState == 4)
			{
				if (req.status == 200)
				{
					i_tmp = req.responseText;
					i_canvasState = i_game;
					str = i_tmp.substr (0, 1);
					if (str > 0 && str < 9)
					{
						s_mess = "";
						for (i=1; i<=YyY; i++)
						{
							for (ii=1; ii<=XxX; ii++)
							{
								qq = (ii-1); qq *= YyY; qq += (i-1);
								str = i_tmp.substr (qq, 1);
								SquareColorLayer4[ii][i] = str;
							}
						}
						for (i=1; i<=XxX; i++)
						{
							for (ii=1; ii<=YyY; ii++)
							{
								SquareColorLayer2[i][ii] = Number(0);
								s_mess += SquareColorLayer4[i][ii];
							}
						}
						flag_PLAY = true;
						document.getElementById('canvasState').value = i_canvasState;
						document.getElementById('game_sport').style.display = 'inline';
						document.getElementById('game_sport').innerHTML = '№ ' + document.getElementById('canvasState').value;
						Nballov = 0;
						Nxodov = 0;
						CursorY = 1;
						CursorX = 1;
						CursorZ = 1;
						scroll (); scroll (); scroll ();
					}
					else
					{
						window_info ('text_info', i_tmp);
						flag_PLAY = false;
					}
				}
			}
   		}
	req.open('GET', 'ajax_game_load.php?theme=bouncer&canvasState=' + i_game, true);
	req.send(null);
}
function f_endGame()
{
	flag_PLAY = false;
	document.getElementById('mess').value = s_mess + "\t" + Nxodov + "\t" + Nballov;
	window_info ('text_info');
	f_fetchUpdateContent('info_div', 'ajax_game_save.php', 'mess='+document.getElementById('mess').value+'&theme=bouncer&canvasState='+document.getElementById('canvasState').value);
	setTimeout ("f_fetchUpdateContent('user_top_middle', 'ajax_user_top_game.php', 'theme=bouncer')", 3000);
}
