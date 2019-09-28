var SquareColorLayer1 = new Array ();
var SquareColorLayer2 = new Array ();
var SquareColorLayer3 = new Array ();
var SquareColorLayer4;
var XxX = 30;
var YyY = 20;
var Nsquare = 0;
var flag_CLICK = false;

document.getElementById('game').style.width = XxX * 24+"px";

	<!--Создаем массив игрового поля-->
function f_greateGame ()
{
	for (i=1; i<=YyY; i++)
	{
		for (ii=1; ii<=XxX; ii++)
		{
			var myElement = document.createElement ('img');
			document.getElementById('game').appendChild(myElement);
			myElement.id = 'mySquareX' + ii + 'Y' + i;
			myElement.onmouseover = addSquare;
			myElement.onmouseout = addSquare;
			myElement.onclick = slice;
			myElement.i_x = ii;
			myElement.i_y = i;
			myElement.src = "img/stone_0.gif";
		}
	}
	var myElement = document.createElement ('p');
	document.body.appendChild(myElement);
	myElement.id = 'myN_ballov';
	myElement.className = 'border_inset';
	myElement.style.position = 'absolute';
	myElement.style.backgroundColor = '#fff';
	myElement.style.margin = '5px';
	for (i=0; i<=7; i++)   // Предзагрузка
	{
		var myElement = document.createElement ('img');
		document.getElementById('game').appendChild(myElement);
		myElement.style.display = 'none';
		myElement.src = "img/stone_"+i+"1.gif";
		if (i==0) myElement.src = "img/stone_"+i+".gif";
	}
	StartLayers ();
}

	<!--Массив цвета файлов 600 изображений и третий фоновый массив для подсчета количества занятых кубов-->
function StartLayers()
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
}
						<!--Удаляем квадраты-->
function slice (evnt)
{
	if (flag_PLAY == true && flag_CLICK == true)
	{
		this.onmouseover = new Function ();
		i_motion ++;
		for (i=1; i<=XxX; i++)
		{
			for (ii=1; ii<=YyY; ii++)
			{
				if (SquareColorLayer3[i][ii] == 9)
				{
					for (iii = ii; iii>=1; iii--)
					{
						SquareColorLayer2[i][iii] = SquareColorLayer2[i][iii-1];
						document.images[SquareColorLayer1[i][iii]].src = "img/stone_"+SquareColorLayer2[i][iii-1]+".gif";
					}
					SquareColorLayer3[i][ii] = 0;
					SquareColorLayer2[i][1] = 0;
					document.images[SquareColorLayer1[i][1]].src = "img/stone_0.gif";
				}
			}
		}
						<!--Проверяем на пустые столбцы-->
		zeroOK = false;
		for (i=1; i<=XxX; i++)
		{
			if (SquareColorLayer2[i][YyY] == "0")
			{
				zeroOK = true;
				for (ii=i; ii>=2; ii--) {for (iii=1; iii<=YyY; iii++) {SquareColorLayer2[ii][iii] = SquareColorLayer2[ii - 1][iii];}}
				for (ii=1; ii<=YyY; ii++) {SquareColorLayer2[1][ii] = "0";}
			}
		}
		if (zeroOK == true)
		{
			for (i=1; i<=XxX; i++)
			{
				for (ii=1; ii<=YyY; ii++)
				{
					document.images[SquareColorLayer1[i][ii]].src = "img/stone_"+SquareColorLayer2[i][ii]+".gif";
				}
			}
		}

		for (i=1; i <=Nsquare; i++) {i_score += i;}
		Nsquare = 0;
		document.getElementById ('myNballov').innerHTML = i_score;

						<!--Проверяем на конец хода-->
		zeroOK = false;
		for (i=1; i<=XxX; i++)
		{
			for (ii=1; ii<=YyY; ii++)
			{
				if (SquareColorLayer2[i][ii] != "0")
				{
					if (i+1 <= XxX) 	{if (SquareColorLayer2[i][ii] == SquareColorLayer2[i+1][ii]) {zeroOK = true;}}
					if (ii+1 <= YyY)	{if (SquareColorLayer2[i][ii] == SquareColorLayer2[i][ii+1]) {zeroOK = true;}}
					if (i-1 > 0)		{if (SquareColorLayer2[i][ii] == SquareColorLayer2[i-1][ii])  {zeroOK = true;}}
					if (ii-1 > 0)		{if (SquareColorLayer2[i][ii] == SquareColorLayer2[i][ii-1])  {zeroOK = true;}}
				}
			}
		}
		this.onmousemove = addSquare;
		if (zeroOK == false) f_endGame ();
	}
}

	<!--Подсчитываем количество кубиков-->
function addSquare (evnt)
{
	if (flag_PLAY == true)
	{
	x_X = this.i_x;
	y_Y = this.i_y;
	evnt = evnt || window.event;
	if (evnt.type == 'mousemove'){this.onmouseover = addSquare; this.onmousemove = new Function (); f_F = 1;}
	if (evnt.type == 'mouseover') f_F = 1;
	if (evnt.type == 'mouseout') f_F = 2;
	zeroOK = false;
	if (SquareColorLayer2[x_X][y_Y] != "0")
	{
		if (x_X+1 <= XxX) 	{if (SquareColorLayer2[x_X][y_Y] == SquareColorLayer2[x_X+1][y_Y]) {zeroOK = true;}}
		if (y_Y+1 <= YyY)	{if (SquareColorLayer2[x_X][y_Y] == SquareColorLayer2[x_X][y_Y+1]) {zeroOK = true;}}
		if (x_X-1 > 0)	{if (SquareColorLayer2[x_X][y_Y] == SquareColorLayer2[x_X-1][y_Y])  {zeroOK = true;}}
		if (y_Y-1 > 0)	{if (SquareColorLayer2[x_X][y_Y] == SquareColorLayer2[x_X][y_Y-1])  {zeroOK = true;}}
	}
	if (zeroOK == true)
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
						if (f_F == 1) {document.images[SquareColorLayer1[x][y]].src = "img/stone_"+Color2+"1.gif"; flag_CLICK = true;}
						if (f_F == 2) {document.images[SquareColorLayer1[x][y]].src = "img/stone_"+Color2+".gif"; flag_CLICK = false;}
						zeroOK = true;
						Nsquare++;
					}
				}
			}
		}
		N_ballov = 0;
		for (i=1; i <=Nsquare; i++) {N_ballov += i;}
		document.getElementById ('myN_ballov').innerHTML = N_ballov;
		if (f_F == 1)
		{
			document.getElementById('myN_ballov').style.left = (evnt.pageX || evnt.clientX)+10+'px';
			document.getElementById('myN_ballov').style.top = (evnt.pageY || evnt.clientY)+20+'px';
		}
		if (f_F == 2)
		{
			document.getElementById('myN_ballov').style.left = -1000+'px';
		}
		return (Nsquare);
	}
	}
}
	<!--Копируем слой 2 в слой 3-->
function CopyLayers ()
{
	for (i=1; i<=XxX; i++)
	{
		for (ii=1; ii<=YyY; ii++)
		{
			SquareColorLayer3[i][ii] = SquareColorLayer2[i][ii];
		}
	}
}

	<!--Заполняем случайным образом цвета кубов-->
function f_newGame ()
{
	for (i=1; i<=XxX; i++){
		for (ii=1; ii<=YyY; ii++){
			SquareColorLayer2[i][ii] = Math.ceil (Math.random ()*6);
			while (SquareColorLayer2[i][ii] == 5) {SquareColorLayer2[i][ii] = Math.ceil (Math.random ()*6);}
			document.images[SquareColorLayer1[i][ii]].src = "img/stone_"+SquareColorLayer2[i][ii]+".gif";
			i_canvasKeymap +=  SquareColorLayer2[i][ii];
		}
	}
}

function f_oldGame()
{
	for (i=1; i<=XxX; i++){
		for (ii=1; ii<=YyY; ii++){
			qq = (i-1); qq *= YyY; qq += (ii-1);
			str = i_canvasKeymap.substr (qq, 1);
			SquareColorLayer2[i][ii] = str;
			document.images[SquareColorLayer1[i][ii]].src = 'img/stone_'+SquareColorLayer2[i][ii]+'.gif';
		}
	}
}
