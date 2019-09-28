var SquareColorLayer1 = new Array ();
var SquareColorLayer2 = new Array ();
var SquareColorLayer3 = new Array ();
var XxX = 30;
var YyY = 20;
var i_squares_of_comp = 0;
var Xcolor = 6;
var Nsquare = 0;
var NNsquare = new Array ();

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
			myElement.onclick = paint;
			myElement.i_x = ii;
			myElement.i_y = i;
			myElement.src = "img/stone_0.gif";
		}
	}
	for (i=0; i<=7; i++) // Предзагрузка
	{
		var myElement = document.createElement ('img');
		document.getElementById('game').appendChild(myElement);
		myElement.style.display = 'none';
		myElement.src = "img/stone_"+i+"1.gif";
		if (i==0) myElement.src = "img/stone_"+i+".gif";
	}
	StartLayers ();
}

	<!--Обнуляем цвета-->
function ColorOnZero ()
{

	for (i=1; i<=7; i++)
	{
		NNsquare[i] = 0;
	}
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

	<!--Закрашиваем кубики игрока (ход пользователя)-->
function paint ()
{
if (flag_PLAY == true)
{
	newNamberColor = SquareColorLayer2[this.i_x][this.i_y];
	namberColor = SquareColorLayer2[XxX][YyY];
	if (newNamberColor != namberColor)
	{
	if (newNamberColor != SquareColorLayer2[1][1])
	{
		i_motion ++;
		SquareColorLayer2[XxX][YyY] = 0;
		var zeroOK = true;
		while (zeroOK == true)
		{
			zeroOK= false;
			for (i=1; i<=XxX; i++)
			{
				for (ii=1; ii<=YyY; ii++)
				{
					if (SquareColorLayer2[i][ii] == 0)
					{
						if (i+1 <= XxX) 	{if (SquareColorLayer2[i+1][ii] == namberColor) {SquareColorLayer2[i+1][ii] = 0;}}
						if (ii+1 <= YyY)	{if (SquareColorLayer2[i][ii+1] == namberColor) {SquareColorLayer2[i][ii+1] = 0;}}
						if (i-1 > 0)		{if (SquareColorLayer2[i-1][ii] == namberColor)  {SquareColorLayer2[i-1][ii] = 0;}}
						if (ii-1 > 0)		{if (SquareColorLayer2[i][ii-1] == namberColor)  {SquareColorLayer2[i][ii-1] = 0;}}
						SquareColorLayer2[i][ii] = newNamberColor;
						zeroOK = true;
					}
				}
			}
		}
	}
	}
	paintPC ();
	CopyLayers ();
	i_score = addSquare (XxX, YyY, 1);
	CopyLayers ();
	i_squares_of_comp = addSquare (1, 1, 1);

	document.getElementById ('myNballov').innerHTML = i_score;
	iii = addSquarePC (1);
	if (iii == 10) f_endGame ();
}
}

	<!--Закрашиваем кубики игрока (ход компа)-->
function paintPC ()
{
	newNamberColor = addSquarePC ();
	namberColor = SquareColorLayer2[1][1];
	if (newNamberColor != namberColor)
	{
	SquareColorLayer2[1][1] = 0;
	var zeroOK = true;
	while (zeroOK == true)
	{
		zeroOK= false;
		for (i=1; i<=XxX; i++)
		{
			for (ii=1; ii<=YyY; ii++)
			{
				if (SquareColorLayer2[i][ii] == 0)
				{
					if (i+1 <= XxX) 	{if (SquareColorLayer2[i+1][ii] == namberColor) {SquareColorLayer2[i+1][ii] = 0;}}
					if (ii+1 <= YyY)	{if (SquareColorLayer2[i][ii+1] == namberColor) {SquareColorLayer2[i][ii+1] = 0;}}
					if (i-1 > 0)		{if (SquareColorLayer2[i-1][ii] == namberColor)  {SquareColorLayer2[i-1][ii] = 0;}}
					if (ii-1 > 0)		{if (SquareColorLayer2[i][ii-1] == namberColor)  {SquareColorLayer2[i][ii-1] = 0;}}
					SquareColorLayer2[i][ii] = newNamberColor;
					zeroOK = true;
				}
			}
		}
	}
	}
}
	<!--Подсчитываем количество кубиков-->
function addSquare (x_X,y_Y, f_F)
{
	Nsquare=0;
	Color2 = SquareColorLayer3[x_X][y_Y]; SquareColorLayer3[x_X][y_Y] = 0;
	var zeroOK = true;
	while (zeroOK == true)
	{
		zeroOK= false;
		for (x=1; x<=XxX; x++)
		{
			for (y=1; y<=YyY; y++)
			{
				if (SquareColorLayer3[x][y] == 0)
				{
					if (x+1 <= XxX) 	{if (SquareColorLayer3[x+1][y] == Color2) {SquareColorLayer3[x+1][y] = 0;}}
					if (y+1 <= YyY)	{if (SquareColorLayer3[x][y+1] == Color2) {SquareColorLayer3[x][y+1] = 0;}}
					if (x-1 > 0)		{if (SquareColorLayer3[x-1][y] == Color2)  {SquareColorLayer3[x-1][y] = 0;}}
					if (y-1 > 0)		{if (SquareColorLayer3[x][y-1] == Color2)  {SquareColorLayer3[x][y-1] = 0;}}
					SquareColorLayer3[x][y] = 9;
					if (f_F == 1) {document.images[SquareColorLayer1[x][y]].src = "img/stone_"+Color2+"1.gif";}
					zeroOK = true;
					Nsquare++;
				}
			}
		}
	}
	return (Nsquare);
}
	<!--Подсчитываем количество кубиков наибольшего цвета вокрук поля компьютера-->
function addSquarePC (f)
{
	ColorOnZero ();
	CopyLayers ();
	if (f) {Color2PC = SquareColorLayer3[XxX][YyY]; SquareColorLayer3[XxX][YyY] = 8;}
	else {Color2PC = SquareColorLayer3[1][1]; SquareColorLayer3[1][1] = 8;}
	var zeroOK = true;
	while (zeroOK == true)
	{
		zeroOK= false;
		for (i=1; i<=XxX; i++)
		{
			for (ii=1; ii<=YyY; ii++)
			{
				if (SquareColorLayer3[i][ii] == 8)
				{
					if (i+1 <= XxX)
					{
						if (SquareColorLayer3[i+1][ii] == Color2PC) {SquareColorLayer3[i+1][ii] = 8;}
						else {if (SquareColorLayer3[i+1][ii] != SquareColorLayer2[XxX][YyY]) {if (SquareColorLayer3[i+1][ii] != 9) {if (SquareColorLayer3[i+1][ii] != 8){ NNsquare[SquareColorLayer3[i+1][ii]] += addSquare (i+1, ii);}}}}
					}
					if (ii+1 <= YyY)
					{
						if (SquareColorLayer3[i][ii+1] == Color2PC) {SquareColorLayer3[i][ii+1] = 8;}
						else {if (SquareColorLayer3[i][ii+1] != SquareColorLayer2[XxX][YyY]) {if (SquareColorLayer3[i][ii+1] != 9){if (SquareColorLayer3[i][ii+1] != 8){ NNsquare[SquareColorLayer3[i][ii+1]] += addSquare (i, ii+1);}}}}
					}
					if (i-1 > 0)
					{
						if (SquareColorLayer3[i-1][ii] == Color2PC)  {SquareColorLayer3[i-1][ii] = 8;}
						else {if (SquareColorLayer3[i-1][ii] != SquareColorLayer2[XxX][YyY]) {if (SquareColorLayer3[i-1][ii] != 9){if (SquareColorLayer3[i-1][ii] != 8){NNsquare[SquareColorLayer3[i-1][ii]] += addSquare (i-1, ii);}}}}
					}
					if (ii-1 > 0)
					{
						if (SquareColorLayer3[i][ii-1] == Color2PC)  {SquareColorLayer3[i][ii-1] = 8;}
						else {if (SquareColorLayer3[i][ii-1] != SquareColorLayer2[XxX][YyY]) {if (SquareColorLayer3[i][ii-1] != 9){if (SquareColorLayer3[i][ii-1] != 8){NNsquare[SquareColorLayer3[i][ii-1]] += addSquare (i, ii-1);}}}}
					}
					SquareColorLayer3[i][ii] = 9;
					zeroOK = true;
				}
			}
		}
	}
	Ncolor = NNsquare[1]; _max = 1;
	for (i=2; i<=6; i++)
	{
		if (NNsquare[i] > Ncolor) {_max = i; Ncolor = NNsquare[i];}
	}
	if (NNsquare[_max] == 0) {_max=10;}
	return (_max);
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
			document.images[SquareColorLayer1[i][ii]].src = "img/stone_"+SquareColorLayer2[i][ii]+".gif";
			i_canvasKeymap +=  SquareColorLayer2[i][ii];
		}
	}
	i_squares_of_comp = 0;
}
function f_oldGame()
{
	for (i=1; i<=XxX; i++){
		for (ii=1; ii<=YyY; ii++){
			qq = (i-1); qq *= 20; qq += (ii-1);
			str = i_canvasKeymap.substr (qq, 1);
			SquareColorLayer2[i][ii] = str;
			document.images[SquareColorLayer1[i][ii]].src = 'img/stone_'+SquareColorLayer2[i][ii]+'.gif';
		}
	}
}
